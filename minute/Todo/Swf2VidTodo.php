<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 11/5/2016
 * Time: 11:04 AM
 */
namespace Minute\Todo {

    use Minute\Config\Config;
    use Minute\Event\ImportEvent;
    use Minute\Swf2Vid\Swf2Vid;

    class Swf2VidTodo {
        /**
         * @var TodoMaker
         */
        private $todoMaker;
        /**
         * @var Config
         */
        private $config;

        /**
         * MailerTodo constructor.
         *
         * @param TodoMaker $todoMaker - This class is only called by TodoEvent (so we assume TodoMaker is be available)
         * @param Config $config
         */
        public function __construct(TodoMaker $todoMaker, Config $config) {
            $this->todoMaker = $todoMaker;
            $this->config    = $config;
        }

        public function getTodoList(ImportEvent $event) {
            $config = $this->config->get(Swf2Vid::Key, []);

            $todos[] = ['name' => 'Create video levels', 'description' => 'Create list of levels (array) from which to deduct user credits',
                        'status' => $config['video_levels'] ?? null ? 'complete' : 'incomplete', 'link' => '/admin/config'];

            $event->addContent(['Swf2Vid' => $todos]);
        }
    }
}