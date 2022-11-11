# Simple QueryURL library (based on cURL)

[![Language](https://img.shields.io/badge/php-%5E5.6-blue)](https://img.shields.io/badge/php-%5E5.6-blue) [![Language](https://img.shields.io/badge/cURL-%5E7.59.0-blue)](https://img.shields.io/badge/cURL-%5E7.59.0blue)

Класс библиотеки QueryURL представляет собой простой интерфейс для работы с cURL-библиотекой.

#### Установка
Установка производится через Composer:
```sh
composer require mmaurice/qurl
```

#### Примеры кода
###### Создание объекта
Для создания объекта нет нужды передавать какие-либо параметры в конструктор. Тогда будет создан базовый клиент.
```php
use \mmaurice\qurl\Client;

$client = new Client;
```

###### Задание параметров cURL
При создании клиента можно передавать в конструктор параметры cURL:

```php
use \mmaurice\qurl\Client;

$client = new Client([
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
]);
```

Так же параметры можно задавать методом `setOption`. В таком случае, первым аргументом будет выступать константа параметра cURL, а вторым аргументом - его значение:

```php
$client
    ->setOption(CURLOPT_FOLLOWLOCATION, true)
    ->setOption(CURLOPT_SSL_VERIFYPEER, false)
    ->setOption(CURLOPT_RETURNTRANSFER, true);
```

В случае необходимости задания массива параметров, можно воспользоваться методом `setOptions`, который принимает массив значений, состоящий из констант параметров cURL в качестве ключей, и значений параметров в качестве значений массива:

```php
$client->setOptions([
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_RETURNTRANSFER => true,
]);
```

Более подробно о параметрах cURL можно ознакомиться в соответствующем разделе [документации](https://www.php.net/manual/ru/function.curl-setopt.php).

###### Создание объекта запроса
Для начала конфигурирования запроса, необходимо воспользоваться методом `request`, который имеет набор методов для настройки запроса:

```php
$request = $client->request(); // Instance of \mmaurice\qurl\Request
```

###### Задание заголовков
Заголовки можно задать при помощи метода `setHeader`. В таком случае, первым аргументом будет выступать имя заголовка, а вторым аргументом - его значение:

```php
$request->setHeader('Accept-Encoding', 'gzip, deflate, br');
```

В случае необходимости задания массива заголовков, можно воспользоваться методом `setHeaders`, который принимает массив значений, состоящий из имён заголовков в качестве ключей, и значений заголовков в качестве значений массива:

```php
$request->setHeaders([
    'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
    'Cache-Control' => 'max-age=0',
    'Connection' => 'keep-alive',
]);
```

###### Задание тела запроса
Тело запроса можно задать при помощи метода `setBodyFields`. В качестве аргументов, метод принимает ассоциативный массив значений, которые далее будут сформированы в тело запроса. Например:

```php
$request->setBodyFields([
    'foo' => 'bar',
    'baz' => 'boo',
]);
```

В случае необходимости дополнить существующий массив параметров, можно воспользоваться методом `setBodyField`. В таком случае, первым аргументом будет выступать ключ параметра, а вторым аргументом - его значение:

```php
$request->setBodyField('foo', 'bar');
```

Хочется дополнительно отметить, что эта операция дополнит существующий набор параметров переданными.

Кроме этого, можно указать, как именно необходимо закодировать тело запроса, а именно - **json**, **url-encoded** строка и **массив**:

```php
// Закодировать тело запроса в формате JSON
// Будет автоматически установлен заголовок "Content-Type: application/json"
$request->setBodyJson();

// Закодировать тело запроса в формате url-encoded строки
// Будет автоматически установлен заголовок "Content-Type: application/x-www-form-urlencoded"
$request->setBodyUrlEncode();

// Закодировать тело запроса в формате массива
// Будет автоматически установлен заголовок "Content-Type: multipart/form-data"
$request->setBodyMultipartFormData();
```

###### Прочие параметры запроса
Объект Request поддерживает настройку некоторых ключевых параметров запроса, а именно:

```php
// Задать номер порта (CURLOPT_PORT), на который будет отправлен запрос
$request->setPort(80);

// Задать таймаут (CURLOPT_TIMEOUT)
$request->setTimeout(5);

// Задать таймаут соединения (CURLOPT_CONNECTTIMEOUT)
$request->setTimeout(2);

// Задать таймаут соединения (CURLOPT_USERAGENT)
$request->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

// Разрешить следовать редиректам (CURLOPT_FOLLOWLOCATION), а так же ограничить их количество (CURLOPT_MAXREDIRS)
$request->setFollowLocation(true, 5);
```

Прочие параметры cURL можно задачть в настройках клиента.

###### Отправка запроса
Для отправки обыкновенного **GET**-запроса, необходимо воспользоваться методом `get`, первым аргументом которого будет URL, на который отправляется запрос, вторым аргументом можно так же передавать тело запроса в виде массива параметров, а третьим аргументом можно передать массив заголовков. Тело запроса и заголовки можно не передавать, если они уже были переданы отдельными методами, или если запрос не подразумевает их передачу. В качестве результата запроса будет создан объект Response:

```php
$response = $request->get($url, $body = [], $headers = []); // Instance of \mmaurice\qurl\Response
```

Запрос будет иметь следующий вид:

```php
$response = $request->get('https://api.ipify.org/?format=json');
```

Важно отметить, что `$url` можно задать и в виде массива, первым аргументом которого будет путь, а вторым аргументом - массив **GET-параметров**:

```php
$response = $request->get([
    'https://api.ipify.org/',
    [
        'format' => 'json',
    ],
]);
```

Помимо этого, существует возможность отправить запросы методом `post`, `put`, `head`, `delete`, `connect`, `options`, `path`, `trace`, `search`. Пример соответствующих методов выглядят следующим образом:

```php
$response = $request->post($url, $body = [], $headers = []);
$response = $request->put($url, $body = [], $headers = []);
$response = $request->head($url, $body = [], $headers = []);
$response = $request->delete($url, $body = [], $headers = []);
$response = $request->connect($url, $body = [], $headers = []);
$response = $request->options($url, $body = [], $headers = []);
$response = $request->path($url, $body = [], $headers = []);
$response = $request->trace($url, $body = [], $headers = []);
$response = $request->search($url, $body = [], $headers = []);
```

Набор аргументов для этих методов идентичен набору аргументов для метода `get` - в качестве первого аргумента передаётся URL запроса, а в качестве второго аргумента - массив полей тела запроса, а третьего аргумента - массив заголовков.

Все выше описанные методы являюся обёртками для основного метода запроса `query`. Можно воспользоваться им напрямую, для создания любого типа запроса. Например:

```php
use \mmaurice\qurl\Request;

$response = $request->query(Request::GET, [
    'https://api.ipify.org/',
    [
        'format' => 'json',
    ],
]);
```

В качестве первого аргумента передаётся значение типа запроса. Все типы запросов перечислены в виде констант класса `\mmaurice\qurl\Request`, а именно:

- `\mmaurice\qurl\Request::GET`
- `\mmaurice\qurl\Request::POST`
- `\mmaurice\qurl\Request::PUT`
- `\mmaurice\qurl\Request::HEAD`
- `\mmaurice\qurl\Request::DELETE`
- `\mmaurice\qurl\Request::CONNECT`
- `\mmaurice\qurl\Request::OPTIONS`
- `\mmaurice\qurl\Request::PATH`
- `\mmaurice\qurl\Request::TRACE`
- `\mmaurice\qurl\Request::SEARCH`

###### Получение результата

Одним из преимуществ данной библиотеки является возможность простым образом получить данные о сформированном запросе и полученном ответе. Можно получить следующие данные:

```php
//Запрашиваемый URL
$response->getRequestUrl();

//Заголовки запроса
$response->getRequestHeader();

//Заголовки запроса без предварительной обработки
$response->getRawRequestHeader();

//Тело запроса
$response->getRequestBody();

//Тело запроса без предварительной обработки
$response->getRawRequestBody();

//Заголовки ответа сервера
$response->getResponseHeader();

//Заголовки ответа сервера без предварительной обработки
$response->getRawResponseHeader();

//Тело ответа сервера
$response->getResponseBody();

//Тело ответа сервера без предварительной обработки
$response->getRawResponseBody();

//Ссылка перенаправления сервера (если передал)
$response->getResponseRedirect();

//IP-адрес ответившего сервера
$response->getResponseIp();

//Порт ответившего сервера
$response->getResponsePort();

//Тип контента ответа
$response->getResponseContentType();

//Код ответа сервера
$response->getResponseCode();

//Сообщение сервера
$response->getResponseMessage();

//Полное сообщение сервера
$response->getResponseRawMessage();
```

Более наглядные примеры работы с библиотекой находятся в каталоге `/examples` проекта.
