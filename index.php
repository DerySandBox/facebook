<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>ZenClause</title>
    </head>
    <body>
        <h1>Welcome to ZenClause</h1>
        <?php
        session_start();
        require_once __DIR__ . '/lib/Facebook/autoload.php';

        $app_id = '1671214739826298';
        $app_secret = '66c90f2ccd34a07b83a8703756fe84fd';
        $redirect_url = 'http://zcfb.herokuapp.com/';

        $fb = new Facebook\Facebook([
            'app_id' => $app_id,
            'app_secret' => $app_secret,
            'default_graph_version' => 'v2.5',
        ]);

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
            if (!isset($accessToken)) {
                // Need to login first
                $permissions = ['email', 'public_profile','user_friends']; // optional
                $loginUrl = $helper->getLoginUrl($redirect_url, $permissions);
                //$loginUrl = $helper->getLoginUrl($redirect_url);

                echo '<a href="' . $loginUrl . '"><img src="img/facebook.png" /></a>';
            } else {
                // Logged in already!
                $_SESSION['facebook_access_token'] = (string) $accessToken;

                // Now you can redirect to another page and use the
                // access token from $_SESSION['facebook_access_token']
            }
        }

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

            echo 'Thank you so much for your visit: ' . $userNode->getName();
            echo '<br/>Your email is: ' . $userNode->getEmail();
        }
        ?>        
    </body>
</html>
