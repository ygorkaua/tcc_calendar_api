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

$router->get('v1/session/{sessionId}', 'Index\ScheduleSession@getSessionData');
$router->get('v1/session/user/{userId}', 'Index\ScheduleSession@getUserSessions');
$router->get('v1/session/professional/{professionalId}', 'Index\ScheduleSession@getProfessionalSessions');
$router->post('v1/session/create', 'Index\ScheduleSession@createSession');
$router->post('v1/session/{sessionId}', 'Index\ScheduleSession@updateSessionDate');
$router->delete('v1/session/{sessionId}', 'Index\ScheduleSession@deleteSession');
