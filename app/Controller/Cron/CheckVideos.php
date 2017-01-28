<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 12/8/2016
 * Time: 1:07 PM
 */
namespace App\Controller\Cron {

    use App\Model\MProject;
    use App\Model\MVideo;
    use Carbon\Carbon;
    use Minute\Event\Dispatcher;
    use Minute\Event\Swf2VidEvent;
    use Minute\Log\LoggerEx;
    use Minute\Swf2Vid\Swf2Vid;

    class CheckVideos {
        /**
         * @var Swf2Vid
         */
        private $swf2Vid;
        /**
         * @var Dispatcher
         */
        private $dispatcher;
        /**
         * @var LoggerEx
         */
        private $logger;

        /**
         * QueueVideos constructor.
         *
         * @param Swf2Vid $swf2Vid
         * @param Dispatcher $dispatcher
         * @param LoggerEx $logger
         */
        public function __construct(Swf2Vid $swf2Vid, Dispatcher $dispatcher, LoggerEx $logger) {
            $this->swf2Vid    = $swf2Vid;
            $this->dispatcher = $dispatcher;
            $this->logger     = $logger;
        }

        public function removeFailed() {
            $failed = MVideo::where('vid_status', '=', 'processing')->where('updated_at', '<', Carbon::now()->subHour(1))->get();
            /** @var MVideo $video */
            foreach ($failed as $video) {
                $video->vid_status = 'fail';
                $video->save();

                /** @var MProject $project */
                if ($project = MProject::find($video->project_id)) {
                    $this->dispatcher->fire(Swf2VidEvent::USER_VIDEO_FAIL, new Swf2VidEvent($video->user_id, ['video' => $video->toArray(), 'project' => $project->toArray()]));
                }
            }
        }

        public function healthCheck() {
            foreach (['pending', 'processing'] as $type) {
                $failed = MVideo::where('vid_status', '=', $type)->where('updated_at', '<', Carbon::now()->subHour(1))->count();

                if (count($failed) > 20) {
                    $this->logger->critical("More than 20 $type videos in last 1 hour!");
                }
            }
        }
    }
}