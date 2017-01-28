<?php
/**
 * User: Sanchit <dev@minutephp.com>
 * Date: 11/30/2016
 * Time: 9:02 AM
 */
namespace Minute\Event {

    class Swf2VidEvent extends UserEvent {
        const USER_VIDEO_QUEUED     = "user.video.queued";
        const USER_VIDEO_PASS       = "user.video.pass";
        const USER_VIDEO_FAIL       = "user.video.fail";
    }
}