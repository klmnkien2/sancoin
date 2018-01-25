<!DOCTYPE html>
<html lang="en">

<head>
    <% include ../partials/common-head %>
    <link href='https://fonts.googleapis.com/css?family=Yellowtail' rel='stylesheet' type='text/css'>
    <script src="https://releases.flowplayer.org/6.0.5/flowplayer.min.js" defer></script>
    <script src="https://releases.flowplayer.org/hlsjs/flowplayer.hlsjs.min.js" defer></script>
    <script type="text/javascript" src="assets/broadcast.js"></script>
</head>

<body>
    <div id="broadcast" style="display:none;" data="<%= broadcast %>"></div>
    <div id="main" class="main-container player">
        <div id="banner" class="banner">
            <span id="bannerText" class="text">Waiting for Broadcast to Begin</span>
        </div>
        <div id="videoContainer" class="video-container player"></div>
    </div>
</body>

</html>