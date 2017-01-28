/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />

let a2a_config;

module App {
    export class VideoConfigController {
        constructor(public $scope: any, public $minute: any, public $ui: any, public $timeout: ng.ITimeoutService,
                    public gettext: angular.gettext.gettextFunction, public gettextCatalog: angular.gettext.gettextCatalog) {

            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.data = {processors: [], tabs: {}};
            $scope.config = $scope.configs[0] || $scope.configs.create().attr('type', 'video').attr('data_json', {});
            $scope.settings = $scope.config.attr('data_json');
            $scope.settings.page = angular.isObject($scope.settings.page) ? $scope.settings.page : {share: true, comments: true, youtubeApiKey: 'AIzaSyBCaDivrcL_ivWRsuA_rw-UWH0Xr9K0iLQ'};

            $scope.$watch('data.video.vid_url', (vid_url) => {
                if (vid_url) {
                    let title = $scope.data.project.title || 'untitled';
                    $('#mainVideo').append('<source src="' + vid_url + '" type="video/mp4">');

                    a2a_config = {onclick: 1, linkname: 'Check this out: ' + title + '. Do you agree?', linkurl: $scope.session.request.url};

                    let s = document.createElement("script");
                    s.type = "text/javascript";
                    s.src = "https://static.addtoany.com/menu/page.js";
                    $("head").append(s);

                    this.getVideos(title);
                }
            });

        }

        upload = () => {
            window.open('/members/youtube-auth?redir=/members/youtube-upload/' + this.$scope.data.project.project_id, 'upload', 'width=640,height=600');
        };

        getVideos = (searchTerm) => {
            $.getJSON('https://www.googleapis.com/youtube/v3/search', {part: 'snippet', key: this.$scope.settings.page.youtubeApiKey, q: searchTerm, type: 'video'}, (data) => {
                this.$timeout(() => this.$scope.data.relatedVideos = data.items);
            });
        };

        save = () => {
            this.$scope.config.save(this.gettext('Video saved successfully'));
        };
    }

    angular.module('videoConfigApp', ['MinuteFramework', 'MinuteFilters', 'angular.filter', 'gettext'])
        .controller('videoConfigController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', VideoConfigController]);

    (function (d, s, id) {
        let js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
}
