<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru">
<head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8"/>
    <title>Глитч-вечеринка</title>
    <link rel="stylesheet" href="css/glitch.css">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="js/glitch.js"></script>
    <script src="js/html2canvas.js"></script>
    <script src="js/jquery.mosaicflow.min.js"></script>
</head>
<body <?php if (!isset($_GET['code'])): ?>class="nothing-to-show"<?php endif; ?>>



<div id="targets" class="targets"></div>
<?php
$client_id = '3889434'; // ID приложения
$client_secret = 'ZlTeZO8GXlzsoPHYraVU'; // Защищённый ключ
$redirect_uri = 'http://glitch.loc/index.php'; // Адрес сайта

$url = 'http://oauth.vk.com/authorize';
$params = array(
    'client_id' => $client_id,
    'redirect_uri' => $redirect_uri,
    'response_type' => 'code',
    'scope' => '4'
);

echo $link = '<a class="button" href="' . $url . '?' . urldecode(http_build_query($params)) . '">Глитч-вечеринка</a>';

if (isset($_GET['code'])) {
    $result = false;
    $params = array(
        'client_id' => $client_id,
        'client_secret' => $client_secret,
        'code' => $_GET['code'],
        'redirect_uri' => $redirect_uri
    );

    $token = json_decode(file_get_contents('https://oauth.vk.com/access_token' . '?' . urldecode(http_build_query($params))), true);

    if (isset($token['access_token'])) {
        $params = array(
            'uids' => $token['user_id'],
            'fields' => 'photo_big',
            'order' => 'random',
            'count' => '25',
            'access_token' => $token['access_token']
        );

        $userInfo = json_decode(file_get_contents('https://api.vk.com/method/friends.get' . '?' . urldecode(http_build_query($params))), true);

        echo '<div id="images">';
        if (isset($userInfo['response'][0]['uid'])) {
            foreach ($userInfo['response'] as $user) {
                $url = $user['photo_big'];

                $urlinfo = parse_url($url);
                $filename = basename($urlinfo['path']);

                if (!file_exists('vkpics/' . $filename)) {
                    copy($url, 'vkpics/' . $filename);
                }

                echo '<div class="image"><img src="vkpics/' . $filename . '"></div>';
            }
        }
        echo '</div>';
    }
}
?>

<audio id="muha" src="muha.mp3"></audio>
<audio id="tanki" src="tanki.mp3"></audio>

<script>
    <?php if (isset($_GET['code'])): ?>
    function glitchAllImages() {
        $('#images').find('.image').each(function (i, el) {
            var $el = $(el),
                $img = $el.find('img');
            $img.glitch(function (canvas) {
                $el.append(canvas);
                $el.css('z-index', '1');
                $el.hide();
            });
        });

        $('#images').find('.image:nth-child(5n+5)').show('slow');
        document.getElementById('tanki').play();

        setTimeout(function(){
            $('#images').find('.image:nth-child(5n+4)').show('slow');
            setTimeout(function(){
                $('#images').find('.image:nth-child(5n+3)').show('slow');
                setTimeout(function(){
                    $('#images').find('.image:nth-child(5n+2)').show('slow');
                    setTimeout(function(){
                        $('#images').find('.image:nth-child(5n+1)').show('slow');
                        setTimeout(function(){
                            $('#images').find('.image:nth-child(5n)').show('slow');
                        }, 500);
                    }, 1000);
                }, 1000);
            }, 1000);
        }, 1000);

    }
    <?php endif; ?>

    $(window).load(function () {
        <?php if (isset($_GET['code'])): ?>
            glitchAllImages();
        <?php endif; ?>

        $('.button').on('click', function (ev) {
            $(this).hide();
            $('#images').css('position', 'relative');
        });
        $('.button').on('mousemove', function (ev) {
            document.getElementById('muha').play();
        });

    });
</script>
</body>
</html>

