/// <reference path="E:/var/Dropbox/projects/buzzvid/public/static/bower_components/minute/_all.d.ts" />
var App;
(function (App) {
    var YouTubeUploadController = (function () {
        function YouTubeUploadController($scope, $minute, $ui, $timeout, gettext, gettextCatalog) {
            this.$scope = $scope;
            this.$minute = $minute;
            this.$ui = $ui;
            this.$timeout = $timeout;
            this.gettext = gettext;
            this.gettextCatalog = gettextCatalog;
            gettextCatalog.setCurrentLanguage($scope.session.lang || 'en');
        }
        return YouTubeUploadController;
    }());
    App.YouTubeUploadController = YouTubeUploadController;
    angular.module('YouTubeUploadApp', ['MinuteFramework', 'gettext'])
        .controller('YouTubeUploadController', ['$scope', '$minute', '$ui', '$timeout', 'gettext', 'gettextCatalog', YouTubeUploadController]);
})(App || (App = {}));
