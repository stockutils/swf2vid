<?php

/** @var Binding $binding */
use App\Controller\Swf2Vid\StockVideoPage;
use Minute\Event\Binding;
use Minute\Event\ImportProjectEvent;
use Minute\Event\ServerEvent;
use Minute\Event\TodoEvent;
use Minute\Server\CheckPendingVideos;
use Minute\Todo\Swf2VidTodo;
use Minute\Video\AutomaticUpload;
use Minute\Video\DeductCredits;

$binding->addMultiple([
    //static event listeners go here
    ['event' => ImportProjectEvent::IMPORT_PROJECT_INFO, 'handler' => [StockVideoPage::class, 'getProjectInfo'], 'priority' => 0],
    ['event' => ServerEvent::SERVER_CHECK_PENDING_JOBS, 'handler' => [CheckPendingVideos::class, 'checkPending'], 'priority' => 0],

    //deduct credits, send email, etc
    ['event' => \Minute\Event\Swf2VidEvent::USER_VIDEO_PASS, 'handler' => [DeductCredits::class, 'deductCredits'], 'priority' => 99],
    ['event' => \Minute\Event\Swf2VidEvent::USER_VIDEO_PASS, 'handler' => [AutomaticUpload::class, 'upload']],

    //tasks
    ['event' => TodoEvent::IMPORT_TODO_ADMIN, 'handler' => [Swf2VidTodo::class, 'getTodoList']],
]);