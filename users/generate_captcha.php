<?php
session_start();

$captcha_code = substr(md5(rand()), 0, 6);
$_SESSION['captcha'] = $captcha_code;

$im = imagecreatetruecolor(150, 50);
$bg = imagecolorallocate($im, 22, 86, 165);
$fg = imagecolorallocate($im, 255, 255, 255);
imagefill($im, 0, 0, $bg);
imagettftext($im, 20, 0, 30, 30, $fg, 'path/to/font.ttf', $captcha_code);

header('Content-Type: image/png');
imagepng($im);
imagedestroy($im);
?>