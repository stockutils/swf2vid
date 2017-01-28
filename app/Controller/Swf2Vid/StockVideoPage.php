<?php
/**
 * Created by: MinutePHP framework
 */
namespace App\Controller\Swf2Vid {

    use App\Model\MProject;
    use App\Model\MVideo;
    use App\Model\User;
    use Minute\Config\Config;
    use Minute\Event\ImportEvent;
    use Minute\Model\CollectionEx;
    use Minute\Model\ModelEx;
    use Minute\Routing\RouteEx;
    use Minute\Session\Session;
    use Minute\Swf2Vid\Swf2Vid;
    use Minute\View\Helper;
    use Minute\View\View;
    use Symfony\Component\Routing\Exception\ResourceNotFoundException;

    class StockVideoPage {
        /**
         * @var Session
         */
        private $session;
        /**
         * @var Config
         */
        private $config;

        /**
         * StockVideoPage constructor.
         *
         * @param Config $config
         * @param Session $session
         */
        public function __construct(Config $config, Session $session) {
            $this->session = $session;
            $this->config  = $config;
        }

        public function index(CollectionEx $project) {
            if (count($project) > 0) {
                return (new View('ActiveTheme/StockVideoPage'))->with(new Helper('AngularFilters'))->with(new Helper('Moment'));
            }

            throw new ResourceNotFoundException("No such video");
        }

        public function getProjectInfo(ImportEvent $event) {
            //this will be replaced with import
            if ($slug = $event->getParams()['title_slug'] ?? '') {

                /** @var ModelEx $project */
                if ($project = MProject::where('title_slug', '=', $slug)->first()) {
                    /** @var ModelEx $video */
                    $video = MVideo::where('project_id', '=', $project->project_id)->where('vid_url', '<>', '')->where('vid_status', '=', 'pass')->orderBy('updated_at', true)->first();
                    /** @var ModelEx $user */
                    $user = User::where('user_id', '=', $project->user_id)->first();

                    $output = ['project' => $project->toArray(), 'video' => $video ? $video->toArray() : [], 'user' => ['first_name' => $user->first_name, 'photo_url' => $user->photo_url]];

                    if (($project->public == 'false')) {
                        if ($project->user_id != $this->session->getLoggedInUserId()) {
                            $output = ['project' => ['hidden' => true]];
                        }
                    }

                    $event->setContent($output);
                }
            }
        }
    }
}