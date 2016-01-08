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
        session_start();
        require_once( 'Facebook/FacebookSession.php' );
        require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
        require_once( 'Facebook/FacebookRequest.php' );
        require_once( 'Facebook/FacebookResponse.php' );
        require_once( 'Facebook/FacebookSDKException.php' );
        require_once( 'Facebook/FacebookRequestException.php' );
        require_once( 'Facebook/FacebookAuthorizationException.php' );
        require_once( 'Facebook/GraphObject.php' );

        use Facebook\FacebookSession;
        use Facebook\FacebookRedirectLoginHelper;
        use Facebook\FacebookRequest;
        use Facebook\FacebookResponse;
        use Facebook\FacebookSDKException;
        use Facebook\FacebookRequestException;
        use Facebook\FacebookAuthorizationException;
        use Facebook\GraphObject;

$app_id = '1741551159400431';
        $app_secret = '257bf39896d0a4dd33f6c53fd6dafdec';
        $redirect_url = 'http://zcfb.herokuapp.com/index.php';

        // init app with app id (APPID) and secret (SECRET)
        FacebookSession::setDefaultApplication($app_id, $app_secret);

// login helper with redirect_uri
        echo 1;
        $helper = new FacebookRedirectLoginHelper($redirect_url);
        echo 2;

        try {
            $session = $helper->getSessionFromRedirect();
            echo 3;
        } catch (FacebookRequestException $ex) {
            // When Facebook returns an error
        } catch (Exception $ex) {
            // When validation fails or other local issues
        }

// see if we have a session
        echo 4;
        if (isset($session)) {
            // graph api request for user data
            $request = new FacebookRequest($session, 'GET', '/me');
            $response = $request->execute();
            // get response
            $graphObject = $response->getGraphObject();

            // print data
            echo print_r($graphObject, 1);
        } else {
            echo 5;

            // show login url
            echo '<a href="' . $helper->getLoginUrl() . '">Login</a>';
        }
        ?>        

        <div
            class="fb-like"
            data-share="true"
            data-width="450"
            data-show-faces="true">
        </div>
    </body>
</html>
