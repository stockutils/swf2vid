<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 1/18/2017
 * Time: 12:19 PM
 */
namespace Minute\Video {

    use Minute\Event\Dispatcher;
    use Minute\Event\Swf2VidEvent;
    use Minute\Event\UploaderEvent;

    class AutomaticUpload {
        /**
         * @var Dispatcher
         */
        private $dispatcher;

        /**
         * AutomaticUpload constructor.
         *
         * @param Dispatcher $dispatcher
         */
        public function __construct(Dispatcher $dispatcher) {
            $this->dispatcher = $dispatcher;
        }

        public function upload(Swf2VidEvent $event) {
            $data = $event->getUserData();

            if ($url = $data['video']['vid_url'] ?? null) {
                $project = json_decode($data['project']['data_json'] ?? '{}', true);
                $upload  = $project['upload'] ?? [];

                if ($upload['enabled'] == 'true') {
                    $uploaderEvent = new UploaderEvent($event->getUserId(), $url, 'youtube', $upload, $data);

                    $this->dispatcher->fire(UploaderEvent::USER_UPLOADER_CREATE, $uploaderEvent);
                }
            }
        }
    }
}