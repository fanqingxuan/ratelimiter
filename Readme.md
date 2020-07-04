This project is used for rate limit and most of the code from laravel framework.

## Requirement

phpredis

## Install

```shell
composer require fanqingxuan/ratelimiter
```

## Usage 

```php
require './vendor/autoload.php';

use Json\RateLimiter\Cache;
use Json\RateLimiter\RedisCache;
use Json\RateLimiter\RateLimiter;

$redis = new Redis;
$redis->connect('127.0.0.1', 6379);

$redisCache = new RedisCache($redis,'lock');

$cache = new Cache($redisCache);

$rateLimter = new RateLimiter($cache);

$key = 'hello';
$maxAttempts = 10;
$seconds = 60;

if($rateLimter->tooManyAttempts("hello",$maxAttempts)) {
	var_dump("after ".$rateLimter->availableIn($key).' seconds can use');
	throw new Exception("over the max attempt");
}

$rateLimter->hit($key, $seconds);

var_dump("left attempt count:".$rateLimter->retriesLeft($key,$maxAttempts));
```

