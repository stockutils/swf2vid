<div id="fb-root"></div>

<div class="content-wrapper ng-cloak" ng-app="videoConfigApp" ng-controller="videoConfigController as mainCtrl" ng-init="init()">
    <minute-event name="IMPORT_PROJECT_INFO" as="data"></minute-event>
    <div class="padded">
        <div class="container">
            <div class="row" ng-switch on="!!data.project.hidden">
                <div ng-switch-when="true">
                    <div class="col-lg-12">
                        <h3><i class="fa fa-lock"></i> Video is private</h3>
                        <hr>

                        <p>If you are the owner of this video, <a href="" ng-href="/login?redir_to={{session.request.uri}}">please login</a> to view your video.</p>

                        <hr>
                    </div>
                </div>

                <div ng-switch-default="">
                    <div class="col-lg-12" ng-show="!data.video.vid_url">
                        <h3>Video is processing..</h3>
                        <hr>
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped active" style="width: 45%">
                            </div>
                        </div>

                        The video for this project is not ready yet. Please check back after some time.

                        <hr>

                    </div>

                    <div class="col-lg-10" ng-show="data.video.vid_url">
                        <div class="margin-bottom panel panel-default panel-body">
                            <div class="embed-responsive embed-responsive-16by9" class="black">
                                <video class="embed-responsive-item" controls autoplay id="mainVideo">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h3 class="heading"><i class="fa fa-lock" ng-show="data.settings.hidden" data-toggle="tooltip" title="This video can only be viewed by you."></i>
                                            {{data.project.title | truncate:250 | ucfirst }}</h3>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div>
                                            <div class="media">
                                                <div class="media-left media-middle">
                                                    <a href="#">
                                                        <img class="media-object" src="" ng-src="{{data.user.photo_url}}" width="40" height="40">
                                                    </a>
                                                </div>
                                                <div class="media-body">
                                                    <p><b class="media-heading">{{data.user.first_name || 'Anonymous'}}</b> <i class="fa fa-check-square text-muted"></i></p>
                                                    <p>Published {{data.project.created_at | timeAgo}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="pull-right">
                                            <span ng-show="session.user.user_id == data.project.user_id">
                                                <a href="" ng-href="{{data.video.vid_url}}" download><i class="fa fa-download fa-2x fa-fw"></i> <b>Download</b> video (mp4)</a> |
                                                <a href="" ng-click="ctrl.upload()"><i class="fa fa-youtube fa-2x fa-fw"></i> <b>Upload</b> to Youtube&trade;</a> |
                                            </span>
                                            <a class="a2a_dd" href=""><i class="fa fa-share-alt fa-2x fa-fw"></i> <b>Share</b> on social sites</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="comments">
                            <hr />

                            <div class="fb-comments" data-href="{{session.request.url}}" data-width="100%" data-numposts="5" data-colorscheme="light"></div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 left-border visible-lg" ng-show="data.relatedVideos.length > 0">
                    <h4 class="heading margin-bottom">Related videos</h4>
                    <div ng-repeat="video in data.relatedVideos" ng-init="link = '//youtu.be/' + video.id.videoId">
                        <a href="" ng-href="{{link}}" target="_blank">
                            <img src="" ng-src="{{video.snippet.thumbnails.default.url}}" class="thumbnail">
                        </a>
                        <a href="" ng-href="{{link}}" class="text-muted" target="_blank">{{video.snippet.title}}</a>
                        <hr ng-show="!$last">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>