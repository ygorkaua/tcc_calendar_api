<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('swaggerapi', function () {
    return file_get_contents(__DIR__ . '/../public/swagger/index.html');
});
$router->get('v1/session/all', 'V1\ScheduleSession@getAllSessionData');
$router->get('v1/session/{sessionId}', 'V1\ScheduleSession@getSessionData');
$router->get('v1/session/user/{userId}', 'V1\ScheduleSession@getUserSessions');
$router->get('v1/session/professional/{professionalId}', 'V1\ScheduleSession@getProfessionalSessions');
$router->post('v1/create/user/{userId}/professional/{professionalId}/sessionDate/{sessionDate}', 'V1\ScheduleSession@createSession');
$router->post('v1/session/{sessionId}/sessionDate/{sessionDate}', 'V1\ScheduleSession@updateSessionDate');
$router->delete('v1/session/{sessionId}', 'V1\ScheduleSession@deleteSession');

