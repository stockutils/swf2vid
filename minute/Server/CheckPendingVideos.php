<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 12/8/2016
 * Time: 1:13 PM
 */
namespace Minute\Server {

    use App\Model\MVideo;
    use Minute\Event\ServerEvent;

    class CheckPendingVideos {
        public function checkPending(ServerEvent $event) {
            $count = MVideo::where('vid_status', '=', 'pending')->count();
            $event->setPendingJobs($count > 0);
        }
    }
}