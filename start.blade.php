<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Wolfys Start Page</title>
    <link rel="stylesheet" href="https://backend.ge-world.ru/storage/css/fontawesome/all.min.css">
    <link rel="stylesheet" href="https://cdn.metroui.org.ua/v4/css/metro-all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Comfortaa:wght@500&display=swap" rel="stylesheet">
</head>
<style>
    body { background-color: rgba(0, 0, 0, 0); margin: 0px auto; overflow: hidden; }
    .bg-filter {
        position: absolute;
        background: linear-gradient(180deg, rgba(116, 116, 116, 0.3) 0%, rgba(0, 0, 0, 0.3) 100%);
        width: 100% !important;
        height: 1080px;
        z-index: 1000;
    }
    .player {
        z-index: 0;
    }
    .avatar {
        position: absolute;
        z-index: 1005;
        left: 93%;
        top: 5%;
    }

    .carousel-social {
        position: absolute;
        background-color: transparent !important;
        top: 85%;
        width: 25% !important;
        z-index: 1000;
    }
    .text-social {
        text-shadow: 1px 1px #0b0b0b, 1px -1px #0b0b0b, -1px 1px #0b0b0b, -1px -1px #0b0b0b, 3px 3px 6px rgba(0,0,0,.5);
    }
    .text-game {
        font-family: 'Comfortaa', cursive;
        font-size: 32px;
        text-shadow: 1px 1px #0b0b0b, 1px -1px #0b0b0b, -1px 1px #0b0b0b, -1px -1px #0b0b0b, 3px 3px 6px rgba(0,0,0,.5);
        color: white;
        position: absolute;
        top: 5%;
        left: 5%;
        z-index: 1000;
    }
    .text-start {
        font-family: 'Comfortaa', cursive;
        font-size: 64px;
        text-shadow: 1px 1px #0b0b0b, 1px -1px #0b0b0b, -1px 1px #0b0b0b, -1px -1px #0b0b0b, 3px 3px 6px rgba(0,0,0,.5);
        color: white;
        position: absolute;
        top: 45%;
        left: 50%;
        margin-right: -50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
    }
    .countdown-start {
        font-family: 'Comfortaa', cursive;
        position: absolute;
        top: 60%;
        left: 50%;
        font-size: 64px;
        text-shadow: 1px 1px #0b0b0b, 1px -1px #0b0b0b, -1px 1px #0b0b0b, -1px -1px #0b0b0b, 3px 3px 6px rgba(0,0,0,.5);
        color: white;
        margin-right: -50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
    }
    .text-countdown-end {
        font-family: 'Comfortaa', cursive;
        position: absolute;
        top: 50%;
        left: 50%;
        font-size: 64px;
        text-shadow: 1px 1px #0b0b0b, 1px -1px #0b0b0b, -1px 1px #0b0b0b, -1px -1px #0b0b0b, 3px 3px 6px rgba(0,0,0,.5);
        color: white;
        margin-right: -50%;
        transform: translate(-50%, -50%);
        z-index: 1000;
    }
    .display-none {
        display: none !important;
    }
    .countdown .part+.part::before {
        background: inherit !important;
    }

</style>
<body>
<input type="hidden" id="video" value="{{ $video }}">
<img class="avatar ani-heartbeat" src="https://backend.ge-world.ru/storage/img/avatar/Wolfys.png" alt="Wolfys">
<span class="text-game">Игра: {{ $name }}</span>
<div class="bg-filter"></div>
<div id="player" class="player"></div>
<span class="text-center text-start">
    Старт Стрима<br />
    через:
</span>
<div class="countdown-start"
     id="countdown-start"
     data-role="countdown"
     data-cls-days="display-none"
     data-cls-hour="display-none"
     data-cls-zero="display-none"
     data-locale="ru-RU"
     data-on-tick="checkTimer(arguments)"
     data-minutes="{{ $time }}"></div>
<span class="text-countdown-end" style="display: none;">Скоро начнём</span>
<!-- data-on-tick="checkTimer(arguments)" -->
<div data-role="carousel"
     data-cls-bullet="bullet-big"
     data-auto-start="true"
     data-cls-controls="fg-black"
     data-height="160"
     data-controls="false"
     data-bullets="false"
     class="carousel-social"
>
    <div class="slide d-flex flex-justify-center flex-align-center">
        <span class="h2 fg-white text-social"> <i class="fa-brands fa-vk"></i> !vk</span>
    </div>
    <div class="slide d-flex flex-justify-center flex-align-center">
        <span class="h2 fg-white text-social"><i class="fa-brands fa-discord"></i> !discord</span>
    </div>
    <div class="slide d-flex flex-justify-center flex-align-center">
        <span class="h2 fg-white text-social"><i class="fa-brands fa-telegram"></i> !tg</span>
    </div>
</div>
<script src="https://cdn.metroui.org.ua/v4/js/metro.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://backend.ge-world.ru/storage/css/fontawesome/all.js"></script>
<script>
    // 2. This code loads the IFrame Player API code asynchronously.
    var tag = document.createElement('script');

    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    // 3. This function creates an <iframe> (and YouTube player)
    //    after the API code downloads.
    var player;

    function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
            height: '100%',
            width: '100%',
            fitToBackground: true,
            videoId: $('#video').val(),
            playerVars: {
                'autoplay': 1,
                'controls': 1,
                'autohide': 1,
                'enablejsapi': 1,
                'loop': 1,
                'disablekb': 1,
                'fs': 0,
                'modestbranding': 0,
                'showinfo': 0,
                'color': 'white',
                'theme': 'light',
                'rel': 0,
                'playlist': $('#video').val(),

            },
            events: {
                'onReady': onPlayerReady,
            }
        });
    }

    // 4. The API will call this function when the video player is ready.
    function onPlayerReady(event) {
        event.target.playVideo();
        player.setSize(1920, 1080);
        player.setLoop(true);
        player.setPlaybackQuality('hd1080');
    }

    function checkTimer(arguments) {
        console.log('Минута: '+ arguments[2] +' | Секунда:' + arguments[3])
        if(arguments[2] === 0 && arguments[3] === 5) {
            /*
            $('#player').css('display','none');
            $('.text-start').css('display','none');
            $('#countdown-start').css('display','none');
             */
            $('#countdown-start').css('display','none');
            $('.text-start').css('display','none');
            $('.text-countdown-end').css('display','block');
        }
    }

    function timerOff() {

    }

</script>
<script>

</script>
</body>
</html>
