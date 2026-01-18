<?php

namespace App\Cache;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

abstract class CacheBase
{
    protected $key;
    protected $ttl;
    protected $data;

    protected abstract function cacheMiss();
    protected abstract function errorModelName(): string;
    protected abstract function errorModelId();

    function __construct($key, $ttl)
    {
        $this->key = $key;
        $this->ttl = $ttl;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function invalidate(): bool
    {
        return Cache::forget($this->key);
    }

    public function fetch()
    {
        $this->data = Cache::remember($this->getKey(), $this->ttl, function () {
            return $this->cacheMiss();
        });

        // only store "non-null" data from cacheMiss()
        if(is_null($this->data)) $this->invalidate();

        return $this->data;
    }

    public function fetchOrFail()
    {
        $result = $this->fetch();
        if($result == null){
            throw new ModelNotFoundException("Unable to retrieve " . $this->errorModelName() . ($this->errorModelId() ? (" " . $this->errorModelId()) : ""));
        }else{
            return $result;
        }
    }

    public function store($data)
    {
        return Cache::put($this->getKey(), $data, $this->ttl);
    }

    public function exists()
    {
        return Cache::has($this->getKey());
    }
}
