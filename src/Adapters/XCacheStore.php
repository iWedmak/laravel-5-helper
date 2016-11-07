<?php namespace iWedmak\Helper\Adapters;

use Cache;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Cache\TaggableStore;

class XCacheStore extends TaggableStore implements Store {

    protected $prefix;
    
    public function __construct($prefix = '')
    {
        $this->setPrefix(($prefix)?$prefix:\Config::get('cache.prefix'));
    }
    
    public function get($key) 
    {
        $value = xcache_get($this->prefix.$key);
		if (isset($value))
		{
			return $value;
		}
    }
    
    public function put($key, $value, $minutes) 
    {
        xcache_set($this->prefix.$key, $value, $minutes * 60);
    }
    
    public function putMany(array $values, $minutes)
    {
        $prefixedValues = [];
        foreach ($values as $key => $value) {
            xcache_set($this->prefix.$key, $value, $minutes * 60);
        }
    }
    
    public function many(array $keys)
    {
        $prefixedKeys = array_map(function ($key) {
            return $this->prefix.$key;
        }, $keys);
        $values=[];
        foreach($prefixedKeys as $key)
        {
            $values[$key]=xcache_get($key);
        }
        return $values;
    }
    
    public function getPrefix()
    {
        return $this->prefix;
    }
    
    public function increment($key, $value = 1) 
    {
        return xcache_inc($this->prefix.$key, $value);
    }
    
    public function decrement($key, $value = 1) 
    {
        return xcache_dec($this->prefix.$key, $value);
    }
    
    public function forever($key, $value) 
    {
        return $this->put($key, $value, 0);
    }
    
    public function forget($key) 
    {
        xcache_unset($this->prefix.$key);
    }
    
    public function flush() 
    {
        xcache_clear_cache(XC_TYPE_VAR);
    }
    
    public function setPrefix($prefix)
    {
        $this->prefix = ! empty($prefix) ? $prefix.':' : '';
    }
}