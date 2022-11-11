<?php

namespace mmaurice\qurl;

use \mmaurice\qurl\classes\Curl;
use \mmaurice\qurl\classes\Headers;
use \mmaurice\qurl\classes\Body;
use \mmaurice\qurl\Client;
use \mmaurice\qurl\Response;

class Request extends \mmaurice\qurl\classes\Basic
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const HEAD = 'HEAD';
    const DELETE = 'DELETE';
    const CONNECT = 'CONNECT';
    const OPTIONS = 'OPTIONS';
    const PATH = 'PATH';
    const TRACE = 'TRACE';
    const SEARCH = 'SEARCH';

    protected $headers;
    protected $body;

    public $requestUrl;
    public $requestBody;

    public function __construct(Client $client)
    {
        $this->curl = $client->curl();
        $this->headers = new Headers;
        $this->body = new Body;

        $this->setUserAgent('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');

        return $this;
    }

    public function setHeader($header, $value)
    {
        $this->headers->set(trim($header), trim($value));

        return $this;
    }

    public function setHeaders(array $headers)
    {
        $this->headers->import($headers);

        return $this;
    }

    public function setBodyField($field, $value)
    {
        $this->body->set(trim($field), $value);

        return $this;
    }

    public function setBodyFields(array $fields)
    {
        $this->body->import($fields);

        return $this;
    }

    /*
    public function setFile($field, $fileName)
    {
        $this->body->set(trim($field), self::attachCurlFile($fileName));

        return $this;
    }
    */

    public function getHeaders()
    {
        $headers = $this->headers->export();

        if (!empty($headers)) {
            $headers = array_map(function($key, $value) {
                return $key . ': ' . $value;
            }, array_keys($headers), array_values($headers));
        }

        return $headers;
    }

    public function getBodyFields()
    {
        return $this->body->export();
    }

    public function get($url, $body = [], $headers = [])
    {
        return $this->query(self::GET, $url, $body, $headers);
    }

    public function post($url, $body = [], $headers = [])
    {
        $this->curl->setOption(CURLOPT_POST, true);

        return $this->query(self::POST, $url, $body, $headers);
    }

    public function put($url, $body = [], $headers = [])
    {
        return $this->query(self::PUT, $url, $body, $headers);
    }

    public function head($url, $body = [], $headers = [])
    {
        $this->curl->setOption(CURLOPT_NOBODY, true);

        return $this->query(self::HEAD, $url, $body, $headers);
    }

    public function delete($url, $body = [], $headers = [])
    {
        return $this->query(self::DELETE, $url, $body, $headers);
    }

    public function connect($url, $body = [], $headers = [])
    {
        return $this->query(self::CONNECT, $url, $body, $headers);
    }

    public function options($url, $body = [], $headers = [])
    {
        return $this->query(self::OPTIONS, $url, $body, $headers);
    }

    public function path($url, $body = [], $headers = [])
    {
        $this->headers->delete('Content-Length');

        return $this->query(self::PATH, $url, $body, $headers);
    }

    public function trace($url, $body = [], $headers = [])
    {
        return $this->query(self::TRACE, $url, $body, $headers);
    }

    public function search($url, $body = [], $headers = [])
    {
        return $this->query(self::SEARCH, $url, $body, $headers);
    }

    public function query($method, $url, $body = [], $headers = [])
    {
        $this->setRequestMethod($method);
        $this->setUrl($url);
        $this->setBodyFields($body);
        $this->setHeaders($headers);

        $this->curl->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->curl->setOption(CURLOPT_SSLVERSION, 6);
        $this->curl->setOption(CURLOPT_VERBOSE, true);
        $this->curl->setOption(CURLOPT_HEADER, true);
        $this->curl->setOption(CURLINFO_HEADER_OUT, true);
        $this->curl->setOption(CURLOPT_SSL_VERIFYPEER, false);

        ($this->getHeaders() ? $this->curl->setOption(CURLOPT_HTTPHEADER, $this->getHeaders()) : false);
        (!empty($this->getBodyFields()) ? $this->curl->setOption(CURLOPT_POSTFIELDS, $this->buildPostData($this->getBodyFields())) : false);

        $this->requestBody = $this->getBodyFields();

        return new Response($this);
    }

    public function setBodyJson()
    {
        $this->setHeader('Content-Type', 'application/json');

        return $this;
    }

    public function setBodyUrlEncode()
    {
        $this->setHeader('Content-Type', 'application/x-www-form-urlencoded');

        return $this;
    }

    public function setBodyMultipartFormData()
    {
        $this->setHeader('Content-Type', 'multipart/form-data');

        return $this;
    }

    protected function setUrl($url)
    {
        $uri = $url;

        if (is_array($url) and !empty($url)) {
            $uri = array_shift($url);

            if (!empty($url)) {
                $url = array_shift($url);

                if (!empty($url)) {
                    $uri .= '?' . $this->buildGetData($url);
                }
            }
        }

        $this->requestUrl = $uri;
        $this->curl->setOption(CURLOPT_URL, $uri);

        return $this;
    }

    public function setPort($port)
    {
        $this->curl->setOption(CURLOPT_PORT, intval($port));

        return $this;
    }

    public function setTimeout($seconds)
    {
        $this->curl->setOption(CURLOPT_TIMEOUT, $seconds);

        return $this;
    }

    public function setConnectTimeout($seconds)
    {
        $this->curl->setOption(CURLOPT_CONNECTTIMEOUT, $seconds);

        return $this;
    }

    public function setUserAgent($userAgent)
    {
        $this->curl->setOption(CURLOPT_USERAGENT, $userAgent);

        return $this;
    }

    public function setFollowLocation($status = true, $maxRedirects = 0)
    {
        $this->curl->setOption(CURLOPT_FOLLOWLOCATION, $status);

        if (intval($maxRedirects) > 0) {
            $this->curl->setOption(CURLOPT_MAXREDIRS, intval($maxRedirects));
        }

        return $this;
    }

    protected function setRequestMethod($method)
    {
        $this->curl->setOption(CURLOPT_URL, $method);
        $this->curl->setOption(CURLOPT_CUSTOMREQUEST, $method);

        return $this;
    }

    public function setBasicAuth($login, $password = '')
    {
        return $this->setAuth(CURLAUTH_BASIC, $login, $password);
    }

    public function setDigestAuth($login, $password = '')
    {
        return $this->setAuth(CURLAUTH_DIGEST, $login, $password);
    }

    public function buildGetData(array $params)
    {
        if (!empty($params)) {
            return http_build_query($params, '', '&');
        }

        return '';
    }

    public function buildPostData($params)
    {
        if (!empty($params)) {
            if (is_array($params) and $this->headers->has('Content-Type')) {
                if ($this->isJsonType($this->headers->get('Content-Type', ''))) {
                    return json_encode($params);
                } else if ($this->isMultipartFromDataType($this->headers->get('Content-Type', ''))) {
                    return $params;
                } else if ($this->isUrlEncode($this->headers->get('Content-Type', ''))) {
                    return $this->buildGetData($params);
                }
            }

            return $params;
        }

        return '';
    }

    protected function setAuth($authType, $login, $password = '')
    {
        $this->curl->setOption(CURLOPT_HTTPAUTH, $authType);
        $this->curl->setOption(CURLOPT_USERPWD, $login . ':' . $password);

        return $this;
    }
}
