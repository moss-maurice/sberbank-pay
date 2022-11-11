<?php

require_once(realpath(dirname(__FILE__) . '/../vendor/autoload.php'));

use \mmaurice\qurl\Client;
use \mmaurice\qurl\Request;
use \mmaurice\qurl\Response;

// Можно указать опции CURL при создании клиента
$client = new Client([
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
]);

// А так же можно добавить опции в последующем коде
$client->setOption(CURLOPT_RETURNTRANSFER, true);

// Так же доступна возможность задать опции в виде массива через метод setOptions(array)
$client->setOptions([
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
]);

$request = $client->request();

// Можно определять заголовки для запроса
$request->setHeader('Accept-Encoding', 'gzip, deflate, br');

// Так же доступна возможность задать опции в виде массива через метод setHeaders(array)
$request->setHeaders([
    'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
    'Cache-Control' => 'max-age=0',
    'Connection' => 'keep-alive',
]);

// Инициируем GET-запрос
$response = $request->get([
    'https://api.ipify.org/',
    [
        'format' => 'json',
    ],
]);

// Помимо этого, можно инициировать запросы других типов:
// POST
//$request->post($url, $body, $headers);
// PUT
//$request->put($url, $body, $headers);
// HEAD
//$request->head($url, $body, $headers);
// DELETE
//$request->delete($url, $body, $headers);
// CONNECT
//$request->connect($url, $body, $headers);
// OPTIONS
//$request->options($url, $body, $headers);
// PATH
//$request->path($url, $body, $headers);
// TRACE
//$request->trace($url, $body, $headers);
// SEARCH
//$request->search($url, $body, $headers);

// Эти запросы являются методами-обёртками для основного метода запроса query.
// Первым параметром принимается тип запроса:
// $request->query(Request::GET, $url, $body, $headers);
// Все возможные типы запросов перечислены в классе Request в виде констант:
// Request::GET
// Request::POST
// Request::PUT
// Request::HEAD
// Request::DELETE
// Request::CONNECT
// Request::OPTIONS
// Request::PATH
// Request::TRACE
// Request::SEARCH

// Запрашиваемый URL
var_dump($response->getRequestUrl());

// Чистые заголовки запроса
var_dump($response->getRawRequestHeader());

// Заголовки запроса в виде массива
var_dump($response->getRequestHeader());

// Чистое тело запроса
var_dump($response->getRawRequestBody());

// Форматированное тело запроса
var_dump($response->getRequestBody());

// Чистые заголовки ответа
var_dump($response->getRawResponseHeader());

// Заголовки ответа в виде массива
var_dump($response->getResponseHeader());

// Чистое тело ответа
var_dump($response->getRawResponseBody());

// Тело ответа в виде массива
var_dump($response->getResponseBody());

// Форматированное тело ответа
var_dump($response->getResponseRedirect());

// IP-адрес ответившего сервера
var_dump($response->getResponseIp());

// Порт ответившего сервера
var_dump($response->getResponsePort());

// Тип контента в ответе
var_dump($response->getResponseContentType());

// Код ответа
var_dump($response->getResponseCode());

// Сообщение ответа
var_dump($response->getResponseMessage());

// Полное сообщение ответа
var_dump($response->getResponseRawMessage());
