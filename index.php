<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>ZenClause</title>
        <script src="js/pixi.js"></script>
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
        if ($_GET['logout']) {
            unset($_SESSION['facebook_access_token']);
        }
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
            if (!isset($accessToken) || isset($_GET['logout'])) {
                // Need to login first
                echo '<h2>Initialize facebook accesstoken</h2>';
                $permissions = ['email', 'user_friends'];
                $loginUrl = $helper->getLoginUrl($redirect_url, $permissions);
                //$loginUrl = $helper->getLoginUrl($redirect_url);

                echo '<a href="' . $loginUrl . '"><img src="img/facebook.png" /></a>';
            } else {
                // Logged in already!
                echo '<h2>Getting facebook accesstoken</h2>';
                $_SESSION['facebook_access_token'] = (string) $accessToken;

                // Now you can redirect to another page and use the
                // access token from $_SESSION['facebook_access_token']
            }
        }

        // use the access token to retrieve the facebook data
        if (isset($_SESSION['facebook_access_token'])) {
            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);

            try {
                $response = $fb->get('/me?locale=en_US&fields=id,email,name,friends');
                $userNode = $response->getGraphUser();

                $response = $fb->get('/me/friends?limit=5&offset=0');
                $friendList = $response->getGraphEdge();
            } catch (Facebook\Exceptions\FacebookResponseException $e) {
                // When Graph returns an error
                echo 'Graph returned an error: ' . $e->getMessage();
                exit;
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                // When validation fails or other local issues
                echo 'Facebook SDK returned an error: ' . $e->getMessage();
                exit;
            }

            echo '<img src="//graph.facebook.com/' . $userNode->getId() . '/picture">';
            echo '<br/>Thank you so much for your visit: ' . $userNode->getName();
            echo '<br/>Your email is: ' . $userNode->getEmail();
            var_dump($friendList);
            $friendsOn = 0;
            foreach ($friendList as $page) {
                $friend = $page->asArray();
                echo '<br/>id=' . $friend['id'] . '  name=' . $friend['name'];
                //$response = $fb->get('/me?locale=en_US&fields=id,email,name,friends');
                $friendsOn ++;
            }
            echo '<br/>You have ' . $friendList->getTotalCount() . ' facebook friends but only ' . $friendsOn . ' of them are in the community. '
            . 'Invite them to join you now. <br/>';
            ?>

            <script>
                var renderer = PIXI.autoDetectRenderer(1331, 548, {backgroundColor: 0x1099bb});
                document.body.appendChild(renderer.view);
                var stage = new PIXI.Container();

    // put the background image	
                putBackground();

                putHouse_grey(-600, -170);
                putHouse_grey(-400, -200);

                putHouse_occupied(-490, -30);

    // put the houses
                putHouse_fb(-260, 30);
                putHouse_fb(-40, -110);
                putHouse_fb(300, -20);
            <?php
            $friendsOn = 0;
            foreach ($friendList as $page) {
                $friend = $page->asArray();
                echo '<br/>id=' . $friend['id'] . '  name=' . $friend['name'];
                //$response = $fb->get('/me?locale=en_US&fields=id,email,name,friends');
                $friendsOn ++;
            }
            ?>

    // start animating
                animate();


                function putBackground() {
                    var background = new PIXI.Sprite.fromImage("img/Layer.1.Base.png");
                    background.position.x = 0;
                    background.position.y = 0;
                    stage.addChild(background);
                }

                function putHouse_fb(x, y, id, name) {
                    // create a house container
                    var house = new PIXI.Sprite.fromImage('img/Layer.4.FB.png');

                    // put on facebook pic
                    var fb_feed = new PIXI.Sprite.fromImage('//graph.facebook.com/' + id + '/picture');
                    fb_feed.position.x = 50;
                    fb_feed.position.y = 50;
                    house.addChild(fb_feed);

                    // put the feed to the house
                    var fb_feed = new PIXI.Sprite.fromImage('img/Layer.7.CloudB.png');
                    fb_feed.position.x = 50;
                    fb_feed.position.y = 50;
                    house.addChild(fb_feed);

                    // put the text to the feed
                    var countingText = new PIXI.Text(name, {font: 'bold italic 12px Arvo', fill: 'red'});
                    countingText.position.x = 660;
                    countingText.position.y = 195;
                    fb_feed.addChild(countingText);

                    // put house to stage
                    house.position.x = x;
                    house.position.y = y;
                    stage.addChild(house);
                }

                function putHouse_occupied(x, y) {
                    // create a house container
                    var house = new PIXI.Sprite.fromImage('img/Layer.3.Occupied.png');

                    // put house to stage
                    house.position.x = x;
                    house.position.y = y;
                    stage.addChild(house);
                }

                function putHouse_grey(x, y) {
                    // create a house container
                    var house = new PIXI.Sprite.fromImage('img/Layer.2.Grey.png');

                    // put house to stage
                    house.position.x = x;
                    house.position.y = y;
                    stage.addChild(house);
                }

                function animate() {
                    requestAnimationFrame(animate);
                    renderer.render(stage);
                }

            </script>        

            <?php
        }
        ?>        
    </body>
</html>
