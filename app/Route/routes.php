<?php

/** @var Router $router */
use Minute\Model\Permission;
use Minute\Routing\Router;

/** @var Router $router */
$router->get('/swf2vid/update/{video_id}', 'Swf2Vid/Update.php', false, 'm_videos[video_id] as video')
       ->setReadPermission('video', Permission::EVERYONE)->setDefault('_noView', true);

$router->get('/swf2vid/player/{project_id}', 'Swf2Vid/FlashPlayer.php', false);

$router->get('/videos/{title_slug}', 'Swf2Vid/StockVideoPage.php', false, 'm_projects[title_slug][1] as project', 'm_configs[type] as configs')
       ->setReadPermission('project', Permission::EVERYONE)->setReadPermission('configs', Permission::EVERYONE)->setDefault('type', 'video')
       ->setDefault('_noView', true)->setCached(18000);

$router->get('/members/youtube-upload/{project_id}', null, true, 'm_projects[project_id] as project', 'm_videos[project.project_id] as video order by video_id desc')
       ->addConstraint('video', ['vid_status', '=', 'pass']);

$router->post('/members/youtube-upload/{project_id}', 'Members/YouTubeUpload.php@upload', true);