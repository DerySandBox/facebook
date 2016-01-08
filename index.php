<?php
require_once __DIR__ . '/lib/Facebook/autoload.php';

$app_id = '1741551159400431';
$app_secret = '257bf39896d0a4dd33f6c53fd6dafdec';
$redirect_url = 'http://packetcode.com/apps/fblogin-basic/';


$fb = new Facebook\Facebook([
    'app_id' => $app_id,
    'app_secret' => $app_secret,
    'default_graph_version' => 'v2.5',
        ]);
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>ZC with FaceBook</title>
    </head>
    <body>
        <h1>Hello</h1>
    </body>
</html>
