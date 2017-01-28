<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 11/29/2016
 * Time: 7:06 AM
 */
namespace Minute\Swf2Vid {

    use App\Model\MVideo;
    use Carbon\Carbon;
    use Minute\Config\Config;
    use Minute\Error\Swf2VidError;
    use Minute\Event\Dispatcher;
    use Minute\Event\UserCreditEvent;
    use Minute\Instance\Manager;
    use Minute\Model\ModelEx;
    use Minute\User\UserInfo;
    use Minute\Utils\HttpUtils;

    class Swf2Vid {
        const Key = 'swf2vid';

        const ConverterUrl = 'https://www.stockutils.com/static/local/flash/converter/converter.swf';
        /**
         * @var Config
         */
        private $config;
        /**
         * @var UserInfo
         */
        private $userInfo;
        /**
         * @var Dispatcher
         */
        private $dispatcher;
        /**
         * @var HttpUtils
         */
        private $httpUtils;
        /**
         * @var Manager
         */
        private $manager;

        /**
         * Swf2Vid constructor.
         *
         * @param Config $config
         * @param UserInfo $userInfo
         * @param Dispatcher $dispatcher
         * @param HttpUtils $httpUtils
         * @param Manager $manager
         */
        public function __construct(Config $config, UserInfo $userInfo, Dispatcher $dispatcher, HttpUtils $httpUtils, Manager $manager) {
            $this->config     = $config;
            $this->userInfo   = $userInfo;
            $this->dispatcher = $dispatcher;
            $this->httpUtils  = $httpUtils;
            $this->manager    = $manager;
        }

        /**
         * @param ModelEx $project
         * @param bool $force
         *
         * @return mixed
         * @throws Swf2VidError
         */
        public function queueProject(ModelEx $project, $force = false) {
            if (($credits = $this->getVideoCredits($project->user_id)) || $force) {
                MVideo::unguard();
                MVideo::where('project_id', '=', $project->project_id)->where('vid_status', '=', 'pending')->update(['vid_status' => 'ignore']);

                $now   = Carbon::now();
                $video = MVideo::create(['created_at' => $now, 'updated_at' => $now, 'user_id' => $project->user_id, 'project_id' => $project->project_id, 'vid_status' => 'pending']);

                return $video;
            } else {
                $creditEvent = new UserCreditEvent($project->user_id, '', $project->toArray());
                $this->dispatcher->fire(UserCreditEvent::USER_NO_CREDITS, $creditEvent);
                throw new Swf2VidError("Out of credits");
            }
        }

        public function queueVideo(ModelEx $video, $urgent = false) {
            $host = $this->config->getPublicVars('host');
            $host = preg_replace('/^http/', 'https', $host);

            $player_url  = $this->httpUtils->prefixHostName($this->config->get(self::Key . '/player-url', '/static/local/flash/player/player.swf'));
            $project_url = sprintf('%s/%d', $this->httpUtils->prefixHostName($this->config->get(self::Key . '/project-url', '/members/projects/data')), $video->project_id);

            if ($instance = $this->manager->findAvailable('qPointer')) {
                $update_url = sprintf('%s/swf2vid/update/%d', $host, $video->video_id);

                if ($response = $this->manager->queryInstance($instance, ['cmd' => 'record', 'player_url' => $player_url, 'project_url' => $project_url, 'update_url' => $update_url])) {
                    $video->vid_status = $response['status'] == 'ok' ? 'processing' : 'fail';

                    return $video->save();
                }
            } else {
                $this->manager->addInstance($urgent);
            }

            return false;
        }

        public function getVideoCredits(int $user_id) {
            $groups  = $this->getVideoGroups();
            $credits = $this->userInfo->getUserGroups($user_id, true, true);

            foreach ($groups as $group) {
                foreach ($credits as $credit) {
                    if ($credit['group_name'] == $group) {
                        if ($remaining = $credit['credits'] ?? 0) {
                            return ['group_name' => $group, 'credits' => $remaining];
                        }
                    }
                }
            }

            return false;
        }

        protected function getVideoGroups() {
            if ($levels = $this->config->get(self::Key . '/video-levels')) {
                return $levels;
            } else {
                $groups = $this->config->get('groups');

                return array_keys($groups['groups'] ?? ['power' => 1, 'business' => 1]);
            }
        }
    }
}