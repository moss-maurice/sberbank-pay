<?php

namespace mmaurice\qurl\classes;

abstract class Storage
{
    protected $storage;

    public function __construct(array $array = [])
    {
        $this->storage = [];

        $this->import($array);
    }

    public function set($key, $value)
    {
        $this->storage[$key] = $value;
    }

    public function get($key, $defaultValue = null)
    {
        if (!$this->has($key)) {
            return $defaultValue;
        }

        return $this->storage[$key];
    }

    public function delete($key)
    {
        if ($this->has($key)) {
            unset($this->storage[$key]);

            return true;
        }

        return false;
    }

    public function has($key)
    {
        if (array_key_exists($key, $this->storage)) {
            return true;
        }

        return false;
    }

    public function import(array $list)
    {
        if (!empty($list)) {
            foreach ($list as $key => $value) {
                $this->storage[$key] = $value;
            }
        }
    }

    public function export()
    {
        return $this->storage;
    }
}
