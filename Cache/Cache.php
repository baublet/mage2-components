<?php

namespace Rsc\Components\Cache;

interface Cache
{
    public function has($key);
    public function set($key, $value, $expiration = 0);
    public function get($key);
}
