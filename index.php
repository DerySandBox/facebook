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
        <?php
        require_once __DIR__ . '/lib/Facebook/autoload.php';
        session_start();

        $app_id = '1741551159400431';
        $app_secret = '257bf39896d0a4dd33f6c53fd6dafdec';
        $redirect_url = 'https://zcfb.herokuapp.com/';

        echo "1";
        $fb = new Facebook\Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.5',
        ]);

        echo "1";
        // Get the access token first
        if (!isset($_SESSION['facebook_access_token'])) {
            $helper = $fb->getRedirectLoginHelper();
            try {
                $accessToken = $helper->getAccessToken();
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }
        echo "1";
            if (!isset($accessToken)) {
                // Need to login first
                $permissions = ['email', 'user_likes']; // optional
        echo "a";
                $loginUrl = $helper->getLoginUrl($redirect_url, $permissions);
        echo "b";

                echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
        echo "c";
            } else {
                // Logged in already!
                $_SESSION['facebook_access_token'] = (string) $accessToken;

                // Now you can redirect to another page and use the
                // access token from $_SESSION['facebook_access_token']
            }
        }

        echo "1";
        // use the access token to retrieve the facebook data
        if (isset($_SESSION['facebook_access_token'])) {
            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);

            try {
                $response = $fb->get('/me');
                $userNode = $response->getGraphUser();
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            echo 'Logged in as ' . $userNode->getName();
        }
        ?>        
    </body>
</html>
