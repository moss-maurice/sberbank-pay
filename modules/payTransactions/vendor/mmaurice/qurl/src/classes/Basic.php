<?php

namespace mmaurice\qurl\classes;

class Basic
{
    protected $curl;

    public function curl()
    {
        return $this->curl;
    }

    protected function isJsonType($string)
    {
        if (preg_match('/^(?:application|text)\/json/i', $string)) {
            return true;
        }

        return false;
    }

    protected function isMultipartFromDataType($string)
    {
        if (preg_match('/^multipart\/form\-data/i', $string)) {
            return true;
        }

        return false;
    }

    protected function isUrlEncode($string)
    {
        if (preg_match('/^application\/x\-www\-form\-urlencoded/i', $string)) {
            return true;
        }

        return false;
    }
}
