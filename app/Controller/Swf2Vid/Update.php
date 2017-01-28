<?php
/**
 * Created by: MinutePHP framework
 */
namespace App\Controller\Swf2Vid {

    use App\Model\MProject;
    use Minute\Event\Dispatcher;
    use Minute\Event\Swf2VidEvent;
    use Minute\Model\CollectionEx;

    class Update {
        /**
         * @var Dispatcher
         */
        private $dispatcher;

        /**
         * Update constructor.
         *
         * @param Dispatcher $dispatcher
         */
        public function __construct(Dispatcher $dispatcher) {
            $this->dispatcher = $dispatcher;
        }

        public function index(CollectionEx $video, string $vid_status, string $vid_url = '') {
            if (count($video) && !empty($vid_status)) {
                /** @var MProject $project */
                $video   = $video[0];
                $project = MProject::where('project_id', '=', $video['project_id'])->first();

                $video->vid_status = $vid_status;
                $video->vid_url    = $vid_url;
                $video->save();

                $eventName = $vid_status == 'pass' ? Swf2VidEvent::USER_VIDEO_PASS : Swf2VidEvent::USER_VIDEO_FAIL;
                $this->dispatcher->fire($eventName, new Swf2VidEvent($video->user_id, ['video' => $video->toArray(), 'project' => $project->toArray()]));
            }

            return 'OK';
        }
    }
}