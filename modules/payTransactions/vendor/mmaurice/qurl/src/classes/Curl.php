<?php

namespace mmaurice\qurl\classes;

use \CurlFile;
use \Exception;

class Curl
{
    protected $curlHandle;
    protected $content;

    public function __construct($headers = [])
    {
        if (!extension_loaded('curl')) {
            throw new Exception("CURL library is not available!");
        }

        $this->curlHandle = curl_init();

        return $this;
    }

    public function setOption($key, $value)
    {
        curl_setopt($this->curlHandle, $key, $value);

        return $this;
    }

    public function setOptions(array $options)
    {
        if (!empty($options)) {
            foreach ($options as $key => $value) {
                $this->setOption($key, $value);
            }
        }

        return $this;
    }

    public function getInfo($key)
    {
        return curl_getinfo($this->curlHandle, $key);
    }

    public function getRawContent()
    {
        return $this->content;
    }

    public function execute()
    {
        $this->content = curl_exec($this->curlHandle);

        if (curl_errno($this->curlHandle)) {
            $error = curl_error($this->curlHandle);

            throw new Exception("CURL query error: '{$error}'");
        }

        return $this;
    }

    public function pause(int $option)
    {
        curl_pause($this->curlHandle, $option);

        return $this;
    }

    public function reset()
    {
        curl_reset($this->curlHandle);

        return $this;
    }

    public function close()
    {
        if (!is_null($this->curlHandle)) {
            curl_close($this->curlHandle);

            $this->curlHandle = null;
        }

        return null;
    }

    static public function attachCurlFile($file)
    {
        $file = realpath($file);

        if (is_file($file)) {
            if (!class_exists('\\CURLFile')) {
                throw new Exception("class 'CURLFile' is not available!");
            }

            return new CurlFile($file);
        }

        return null;
    }
}
