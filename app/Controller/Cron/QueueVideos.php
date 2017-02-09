<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 12/8/2016
 * Time: 12:36 PM
 */
namespace App\Controller\Cron {

    use App\Model\MVideo;
    use Carbon\Carbon;
    use Minute\Event\Dispatcher;
    use Minute\Event\Swf2VidEvent;
    use Minute\Swf2Vid\Swf2Vid;

    class QueueVideos {
        const urgentMins = 120;
        /**
         * @var Swf2Vid
         */
        private $swf2Vid;
        /**
         * @var Dispatcher
         */
        private $dispatcher;

        /**
         * QueueVideos constructor.
         *
         * @param Swf2Vid $swf2Vid
         * @param Dispatcher $dispatcher
         */
        public function __construct(Swf2Vid $swf2Vid, Dispatcher $dispatcher) {
            $this->swf2Vid    = $swf2Vid;
            $this->dispatcher = $dispatcher;
        }

        public function queueVideos() {
            $pending = MVideo::where('vid_status', '=', 'pending')->orderBy('created_at')->get();

            /** @var MVideo $video */
            foreach ($pending as $video) {
                $elapsed = Carbon::now()->diffInSeconds(Carbon::parse($video->created_at));

                if ($this->swf2Vid->queueVideo($video, $elapsed > self::urgentMins * 60)) {
                    printf("Queueing project/video: %d / %d | Elapsed: %ds %s\n", $video->project_id, $video->video_id, $elapsed, $elapsed > self::urgentMins * 60 ? '(urgent)' : '');
                    $this->dispatcher->fire(Swf2VidEvent::USER_VIDEO_QUEUED, new Swf2VidEvent($video->user_id, $video->toArray()));
                }
            }
        }
    }
}