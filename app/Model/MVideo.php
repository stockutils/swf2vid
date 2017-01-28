<?php
/**
 * Created by: MinutePHP Framework
 */
namespace App\Model {

    use Minute\Model\ModelEx;

    class MVideo extends ModelEx {
        protected $table      = 'm_videos';
        protected $primaryKey = 'video_id';
    }
}