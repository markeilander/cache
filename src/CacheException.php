<?php

namespace Eilander\Cache;

class CacheException extends \Exception
{
    /**
     * The exception description.
     *
     * @var string
     */
    protected $message = 'No valid path defined';
}
