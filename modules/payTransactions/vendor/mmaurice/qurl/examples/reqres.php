<?php

/**
 * Пример использования библиотеки для работы с сервисом https://reqres.in/
 */

require_once(realpath(dirname(__FILE__) . '/../vendor/autoload.php'));

use \mmaurice\qurl\Client;
use \mmaurice\qurl\Request;
use \mmaurice\qurl\Response;

$client = new Client;

/**
 * List Users
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->get(['https://reqres.in/api/users', [
        'page' => 2,
    ]]);

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Single User
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->get('https://reqres.in/api/users/2');

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Single User not found
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->get('https://reqres.in/api/users/23');

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * List <Resource>
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->get('https://reqres.in/api/unknown');

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Single <Resource>
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->get('https://reqres.in/api/unknown/2');

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Single <Resource> not found
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->get('https://reqres.in/api/unknown/23');

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Create
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->post('https://reqres.in/api/users', [
        'name' => 'morpheus',
        'job' => 'leader',
    ]);

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Update
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->put('https://reqres.in/api/users/2', [
        'name' => 'morpheus',
        'job' => 'zion resident',
    ]);

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Update
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->path('https://reqres.in/api/users/2', [
        'name' => 'morpheus',
        'job' => 'zion resident',
    ]);

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Delete
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->delete('https://reqres.in/api/users/2');

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Register successful
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->post('https://reqres.in/api/register', [
        'email' => 'eve.holt@reqres.in',
        'password' => 'pistol',
    ]);

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Register unsuccessful
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->post('https://reqres.in/api/register', [
        'email' => 'sydney@fife',
    ]);

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Login successful
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->post('https://reqres.in/api/login', [
        'email' => 'eve.holt@reqres.in',
        'password' => 'cityslicka',
    ]);

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Login unsuccessful
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->post('https://reqres.in/api/login', [
        'email' => 'peter@klaven',
    ]);

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());

/**
 * Delayed response
 */
$request = $client->request();

$response = $request
    ->setBodyJson()
    ->get(['https://reqres.in/api/users', [
        'delay' => 3,
    ]]);

//var_dump($response->getResponseCode());
//var_dump($response->getResponseBody());
