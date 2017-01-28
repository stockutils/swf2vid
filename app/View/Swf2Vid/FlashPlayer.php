<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!-- saved from url=(0014)about:internet -->
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<!--
Smart developers always View Source.

This application was built using Adobe Flex, an open source framework
for building rich Internet applications that get delivered via the
Flash Player or to desktops via Adobe AIR.

Learn more about Flex at http://flex.org
// -->
<head>
    <title></title>
    <meta name="google" value="notranslate" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <!-- Include CSS to eliminate any default margins/padding and set the height of the html element and
         the body element to 100%, because Firefox, or any Gecko based browser, interprets percentage as
         the percentage of the height of its parent container, which has to be set explicitly.  Fix for
         Firefox 3.6 focus border issues.  Initially, don't display flashContent div so it won't show
         if JavaScript disabled.
    -->
    <style type="text/css" media="screen">
        html, body {
            height: 100%;
        }

        body {
            margin: 0;
            padding: 0;
            overflow: auto;
            text-align: center;
            background-color: #000000;
        }

        object:focus {
            outline: none;
        }

        #flashContent {
            display: none;
        }
    </style>

    <script type="text/javascript" src="/static/bower_components/swfobject/swfobject/src/swfobject.js"></script>
</head>

<body>
    <div id="flashContent">
        <p>
            To view this page ensure that Adobe Flash Player version
            11.1.0 or greater is installed.
        </p>
    </div>

    <script type="text/javascript">
        function movieFail() {
            closePreview();
            alert("The preview cannot be generated at this time.\n\nPlease try again after some time or contact our support team.\n\n");
        }

        function movieDone() {
            closePreview();
        }

        function closePreview() {
            if (top && top.closePreview) {
                top.closePreview();
            }
        }
    </script>

    <script type="text/javascript">
        // For version detection, set to min. required Flash Player version, or 0 (or 0.0.0), for no version detection.
        var swfVersionStr = "11.1.0";
        // To use express install, set to playerProductInstall.swf, otherwise the empty string.
        var xiSwfUrlStr = "/static/local/flash/converter/playerProductInstall.swf";
        var flashvars = {player_url: "<?= $player_url ?>", project_url: "<?= $project_url ?>"};
        var params = {};
        params.quality = "high";
        params.bgcolor = "#000000";
        params.allowscriptaccess = "always";
        params.allowfullscreen = "true";
        var attributes = {};
        attributes.id = "converter";
        attributes.name = "converter";
        attributes.align = "middle";
        swfobject.embedSWF(
            "<?= $converter_url ?>", "flashContent",
            "100%", "100%",
            swfVersionStr, xiSwfUrlStr,
            flashvars, params, attributes);
        // JavaScript enabled so display the flashContent div in case it is not replaced with a swf object.
        swfobject.createCSS("#flashContent", "display:block;text-align:left;");
    </script>
</body>
</html>
