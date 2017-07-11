<?php

namespace Rsc\Components\Cache;

use Rsc\Components\Cache\Cache;

class Memory implements Cache
{
    protected $data = [];

    public function has($key)
    {
        return isset($this->data[$key]);
    }

    public function get($key)
    {
        if(isset($this->data[$key]))
        {
            return $this->data[$key];
        }
        return null;
    }

    public function set($key, $value, $expiration = 0)
    {
        $this->data[$key] = $value;
        return $this;
    }
}
