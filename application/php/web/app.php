<?php

require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Response;

$request = \Symfony\Component\HttpFoundation\Request::createFromGlobals();

$app = new \Silex\Application();

function sendFilxRequest( $period, $cycles, $color )
{
    $ch = curl_init();
    $headers = array('Authorization: Bearer cc4485baa5d8703cbcfe853a747a424a9e063afd0445c55c644505e3d50a2465');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL,"https://api.lifx.com/v1/lights/all/effects/breathe");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,
        "period=".$period."&cycles=".$cycles."&color=".$color);
    curl_exec($ch);
    curl_close($ch);
}

$app->post('/pingdom', function(\Symfony\Component\HttpFoundation\Request $request) {

    $parameters = json_decode($request->getContent(), true);
    switch ($request->get('type')) {
        case 'shoploapps':

            if( $parameters['previous_state'] == "UP" && $parameters['current_state'] == "DOWN" ){

                sendFilxRequest(1, 10, "yellow");
            }
            if( $parameters['previous_state'] == "DOWN" && $parameters['current_state'] == "UP" ){

                sendFilxRequest(1, 10, "blue");
            }

            break;
    }
	return new Response('Hi');
});


$app->run($request);