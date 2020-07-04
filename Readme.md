## Requirement

phpredis

## Install

```shell
composer require fanqingxuan/ratelimiter
```

## Usage 

```php
<?php

require './vendor/autoload.php';

use Json\RateLimiter\Cache;
use Json\RateLimiter\RedisStore;
use Json\RateLimiter\MemcacheStore;
use Json\RateLimiter\RateLimiter;

/**
$redis = new Redis;
$redis->connect('127.0.0.1', 6379);

$redisCache = new RedisStore($redis,'lock');
$cache = new Cache($redisCache);

*/

$memcache = new Memcache;
$memcache->connect('127.0.0.1', 11211);

$memcacheStore = new MemcacheStore($memcache,'lock');
$cache = new Cache($memcacheStore);

$rateLimter = new RateLimiter($cache);

$key = 'hello';
$maxAttempts = 10;
$seconds = 60;

if($rateLimter->tooManyAttempts("hello",$maxAttempts)) {
	var_dump("can use after ".$rateLimter->availableIn($key).' seconds');
	throw new Exception("over the max attempts");
}

$rateLimter->hit($key, $seconds);

var_dump("left attempt amount:".$rateLimter->retriesLeft($key,$maxAttempts));

```

