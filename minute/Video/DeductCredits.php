<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 1/16/2017
 * Time: 3:15 PM
 */
namespace Minute\Video {

    use App\Model\MVideo;
    use Carbon\Carbon;
    use Illuminate\Database\Eloquent\Builder;
    use Minute\Config\Config;
    use Minute\Event\Dispatcher;
    use Minute\Event\Swf2VidEvent;
    use Minute\Event\UserCreditEvent;
    use Minute\Swf2Vid\Swf2Vid;

    class DeductCredits {
        /**
         * @var Config
         */
        private $config;
        /**
         * @var Swf2Vid
         */
        private $swf2Vid;
        /**
         * @var Dispatcher
         */
        private $dispatcher;

        /**
         * DeductCredits constructor.
         *
         * @param Config $config
         * @param Dispatcher $dispatcher
         * @param Swf2Vid $swf2Vid
         */
        public function __construct(Config $config, Dispatcher $dispatcher, Swf2Vid $swf2Vid) {
            $this->config     = $config;
            $this->dispatcher = $dispatcher;
            $this->swf2Vid    = $swf2Vid;
        }

        public function deductCredits(Swf2VidEvent $event) {
            if ($data = $event->getUserData()) {
                if ($video = $data['video'] ?? null) {
                    /** @var Builder $builder */
                    $time    = time();
                    $user_id = $event->getUserId();
                    $builder = MVideo::where('video_id', '<', $video['video_id'])->where('project_id', '=', $video['project_id'])->where('vid_status', '=', 'pass')->orderBy('video_id', 'DESC');

                    if ($last = $builder->first()) {
                        $time = $last->updated_at;
                    }

                    $grace   = $this->config->get(Swf2Vid::Key . '/grace_period', 2 * 60 * 60);
                    $elapsed = Carbon::now()->diffInSeconds(Carbon::parse($time));

                    if ($elapsed > $grace) {
                        $credits     = $this->swf2Vid->getVideoCredits($user_id);
                        $creditEvent = new UserCreditEvent($event->getUserId(), $credits['group_name'] ?? 'video', $event->getUserData());
                        $this->dispatcher->fire(UserCreditEvent::USER_DEDUCT_CREDITS, $creditEvent);
                        $handled = $creditEvent->isHandled();

                    }
                }
            }

            return $handled ?? false; //stops propagation
        }
    }
}