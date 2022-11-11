<?php

namespace mmaurice\qurl\classes;

class Headers extends \mmaurice\qurl\classes\Storage
{
    public function set($key, $value)
    {
        parent::set(strtolower($key), $value);
    }

    public function get($key, $defaultValue = null)
    {
        return parent::get(strtolower($key), $defaultValue);
    }

    public function delete($key)
    {
        return parent::delete(strtolower($key));
    }

    public function has($key)
    {
        return parent::has(strtolower($key));
    }

    public function import(array $list)
    {
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                parent::set(strtolower($key), $value);
            }
        }
    }
}
