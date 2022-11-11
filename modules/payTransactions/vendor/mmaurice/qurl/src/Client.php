<?php

namespace mmaurice\qurl;

use \mmaurice\qurl\classes\Curl;
use \mmaurice\qurl\classes\Storage;
use \mmaurice\qurl\Request;

class Client extends \mmaurice\qurl\classes\Basic
{
    public function __construct(array $options = [])
    {
        $this->curl = new Curl;

        $this->setOptions($options);

        return $this;
    }

    public function setOption($key, $value)
    {
        $this->curl->setOption($key, $value);

        return $this;
    }

    public function setOptions(array $options)
    {
        $this->curl->setOptions($options);

        return $this;
    }

    public function request()
    {
        return new Request($this);
    }
}
