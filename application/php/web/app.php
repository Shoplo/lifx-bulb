<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$app = new \Silex\Application();

function sendFilxRequest( $period, $cycles, $colorFrom, $colorTo )
{
    $ch = curl_init();
    $headers = array('Authorization: Bearer cc4485baa5d8703cbcfe853a747a424a9e063afd0445c55c644505e3d50a2465');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL,"https://api.lifx.com/v1/lights/all/effects/pulse");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
        "period=".$period."&cycles=".$cycles."&color=".$colorTo."&from_color=".$colorFrom);
    curl_exec($ch);
    curl_close($ch);

    $ch = curl_init();
    $headers = array('Authorization: Bearer cc4485baa5d8703cbcfe853a747a424a9e063afd0445c55c644505e3d50a2465');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL,"https://api.lifx.com/v1/lights/all/state?power=off");
    curl_setopt($ch, CURLOPT_PUT, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
        "power=off");
    curl_exec($ch);
    curl_close($ch);
}

function ifDown( $colorFrom, $colorTo )
{
    sendFilxRequest(1, 10, $colorFrom, $colorTo);
}

function ifUp( $colorFrom, $colorTo )
{
    sendFilxRequest(1, 10, $colorFrom, $colorTo);
}

$app->post('/pingdom', function(\Symfony\Component\HttpFoundation\Request $request) {

    $parameters = json_decode($request->getContent(), true);
    mail('adrian.adamiec@shoplo.pl', 'test', print_r($parameters, true));
    switch ($parameters['check_name']) {
        case 'Shoploapp Status Pingdom':

            if( $parameters['previous_state'] == "UP" && $parameters['current_state'] == "DOWN" ){

                ifDown("yellow", "red");
            }
            if( $parameters['previous_state'] == "DOWN" && $parameters['current_state'] == "UP" ){

                ifUp("yellow", "green");
            }

            break;

        case 'Omni Api Status':
        case 'Omni Home':

            if( $parameters['previous_state'] == "UP" && $parameters['current_state'] == "DOWN" ){

                ifDown("#6A5AFF", "red");
            }
            if( $parameters['previous_state'] == "DOWN" && $parameters['current_state'] == "UP" ){

                ifUp("#6A5AFF", "green");
            }

            break;

        case 'Shoplo Healthcheck':

            if( $parameters['previous_state'] == "UP" && $parameters['current_state'] == "DOWN" ){

                ifDown("#42e8f4", "red");
            }
            if( $parameters['previous_state'] == "DOWN" && $parameters['current_state'] == "UP" ){

                ifUp("#42e8f4", "green");
            }

            break;
    }
	return new Response('Hi');
});


$app->run($request);