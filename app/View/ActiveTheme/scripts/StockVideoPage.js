/// <reference path="../../../../../../../public/static/bower_components/minute/_all.d.ts" />
var a2a_config;
var App;
(function (App) {
    var VideoConfigController = (function () {
        function VideoConfigController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            var _this = this;
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            this.upload = function () {
                window.open('/members/youtube-auth?redir=/members/youtube-upload/' + _this.$scope.data.project.project_id, 'upload', 'width=640,height=600');
            };
            this.getVideos = function (searchTerm) {
                $.getJSON('https://www.googleapis.com/youtube/v3/search', { part: 'snippet', key: _this.$scope.settings.page.youtubeApiKey, q: searchTerm, type: 'video' }, function (data) {
                    _this.$timeout(function () { return _this.$scope.data.relatedVideos = data.items; });
                });
            };
            this.save = function () {
                _this.$scope.config.save(_this.gettext('Video saved successfully'));
            };
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
            $scope.data = { processors: [], tabs: {} };
            $scope.config = $scope.configs[0] || $scope.configs.create().attr('type', 'video').attr('data_json', {});
            $scope.settings = $scope.config.attr('data_json');
            $scope.settings.page = angular.isObject($scope.settings.page) ? $scope.settings.page : { share: true, comments: true, youtubeApiKey: 'AIzaSyBCaDivrcL_ivWRsuA_rw-UWH0Xr9K0iLQ' };
            $scope.$watch('data.video.vid_url', function (vid_url) {
                if (vid_url) {
                    var title = $scope.data.project.title || 'untitled';
                    $('#mainVideo').append('<source src="' + vid_url + '" type="video/mp4">');
                    a2a_config = { onclick: 1, linkname: 'Check this out: ' + title + '. Do you agree?', linkurl: $scope.session.request.url };
                    var s = document.createElement("script");
                    s.type = "text/javascript";
                    s.src = "https://static.addtoany.com/menu/page.js";
                    $("head").append(s);
                    _this.getVideos(title);
                }
            });
        }
        return VideoConfigController;
    }());
    App.VideoConfigController = VideoConfigController;
    angular.module('videoConfigApp', ['MinuteFramework', 'MinuteFilters', 'angular.filter', 'gettext'])
        .controller('videoConfigController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', VideoConfigController]);
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id))
            return;
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.5";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
})(App || (App = {}));
