<?php

namespace mmaurice\qurl;

use \mmaurice\qurl\classes\Curl;
use \mmaurice\qurl\classes\Storage;
use \mmaurice\qurl\Request;

class Response extends \mmaurice\qurl\classes\Basic
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->curl = $request->curl();
        $this->request = $request;

        $this->curl->execute();

        return $this;
    }

    public function getRequestUrl()
    {
        return $this->request->requestUrl;
    }

    public function getRawRequestHeader()
    {
        return trim($this->curl->getInfo(CURLINFO_HEADER_OUT), PHP_EOL);
    }

    public function getRequestHeader()
    {
        return $this->mapHeadersArray($this->getRawRequestHeader());
    }

    public function getRawRequestBody()
    {
        return $this->request->requestBody;
    }

    public function getRequestBody()
    {
        return $this->request->buildPostData($this->request->getBodyFields());
    }

    public function getRawResponseHeader()
    {
        return trim(substr($this->curl->getRawContent(), 0, intval($this->curl->getInfo(CURLINFO_HEADER_SIZE))), PHP_EOL);
    }

    public function getResponseHeader()
    {
        return $this->mapHeadersArray($this->getRawResponseHeader());
    }

    public function getRawResponseBody()
    {
        return substr($this->curl->getRawContent(), intval($this->curl->getInfo(CURLINFO_HEADER_SIZE)));
    }

    public function getResponseBody()
    {
        if ($this->isJsonType($this->getResponseContentType())) {
            return json_decode($this->getRawResponseBody());
        }

        return $this->getRawResponseBody();
    }

    public function getResponseRedirect()
    {
        return $this->curl->getInfo(CURLINFO_REDIRECT_URL);
    }

    public function getResponseIp()
    {
        return $this->curl->getInfo(CURLINFO_PRIMARY_IP);
    }

    public function getResponsePort()
    {
        return $this->curl->getInfo(CURLINFO_PRIMARY_PORT);
    }

    public function getResponseContentType()
    {
        return $this->curl->getInfo(CURLINFO_CONTENT_TYPE);
    }

    public function getResponseCode()
    {
        return $this->curl->getInfo(CURLINFO_RESPONSE_CODE);
    }

    public function getResponseMessage()
    {
        $message = $this->getResponseRawMessage();
        $code = $this->getResponseCode();

        $regExp = '/^(http[^\s]+\s+' . $code . '\s+)([^$]+)$/i';

        if (preg_match($regExp, $message, $matches)) {
            return trim(preg_replace($regExp, '$2', $message));
        }

        return '';
    }

    public function getResponseRawMessage()
    {
        $headers = $this->getResponseHeader();

        if (array_key_exists(0, $headers)) {
            return $headers[0];
        }

        return '';
    }

    protected function mapHeadersArray($rawHeaders)
    {
        $results = [];

        $headers = explode(PHP_EOL, $rawHeaders);

        if (!empty($headers)) {
            foreach ($headers as $header) {
                $line = explode(':', $header, 2);

                if (array_key_exists(1, $line) and !empty($line[1])) {
                    $results[trim($line[0])] = trim($line[1]);
                } else {
                    $results[] = trim($line[0]);
                }
            }
        }

        return $results;
    }
}
