<?php

namespace Json\RateLimiter;

class Cache {

	/**
     * The cache store implementation.
     *
     */
	private $store;

	/**
     * Create a new cache cache instance.
     *
     * @return void
     */
	public function __construct($store) {
		$this->store = $store;
	}

	/**
     * Determine if an item exists in the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return ! is_null($this->get($key));
    }

	/**
     * Store an item in the cache if the key does not exist.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int|null  $seconds
     * @return bool
     */
    public function add($key, $value, $seconds = null)
    {
        if ($seconds !== null) {
            if ($seconds <= 0) {
                return false;
            }

            // If the store has an "add" method we will call the method on the store so it
            // has a chance to override this logic. Some drivers better support the way
            // this operation should work with a total "atomic" implementation of it.
            if (method_exists($this->store, 'add')) {
                return $this->store->add($key, $value, $seconds);
            }
        }

        // If the value did not exist in the cache, we will put the value in the cache
        // so it exists for subsequent requests. Then, we will return true so it is
        // easy to know if the value gets added. Otherwise, we will return false.
        if (is_null($this->get($key))) {
            return $this->put($key, $value, $seconds);
        }

        return false;
    }

	/**
     * Increment the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return int|bool
     */
    public function increment($key, $value = 1)
    {
        return $this->store->increment($key, $value);
    }

	/**
     * Store an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @param  int|null  $seconds
     * @return bool
     */
    public function put($key, $value, $seconds = null)
    {

        if ($seconds === null) {
            return $this->forever($key, $value);
        }

        if ($seconds <= 0) {
            return $this->forget($key);
        }

        $result = $this->store->put($key, $value, $seconds);

        return $result;
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return bool
     */
    public function forever($key, $value)
    {
        $result = $this->store->forever($key, $value);

        return $result;
    }

	/**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
 
        $value = $this->store->get($key);

        if (is_null($value)) {
            $value = $default;
        }

        return $value;
    }

	/**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget($key)
    {
        return $this->store->forget($key);
    }
}