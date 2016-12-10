<?php

namespace Eilander\Cache;

use Illuminate\Filesystem\Filesystem;

class CacheKeys
{
    /**
     * @var array
     */
    private $keys = [];

    /**
     * @var Illuminate\Filesystem\Filesystem
     */
    private $files = null;

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var string
     */
    protected $file = 'cached-keys.json';

    /**
     * @var string
     */
    protected $tag = '';

    public function __construct()
    {
        $this->files = new Filesystem();
    }

    /**
     * @param $path
     *
     * @return void
     */
    public function path($path)
    {
        if (is_string($path)) {
            $this->path = $path;
        }

        return $this;
    }

    /**
     * @param $tag
     *
     * @return void
     */
    public function tag($tag)
    {
        if (is_string($tag)) {
            $this->tag = $tag;
        }

        return $this;
    }

    /**
     * @param $key
     *
     * @return void
     */
    public function add($key)
    {
        if (is_string($key)) {
            $this->addKey($key, $this->tag);
        }

        return $this;
    }

    /**
     * Store keys to file.
     */
    public function store()
    {
        $this->storeKeys();
    }

    /**
     * Remove keys file.
     */
    public function cleanUp()
    {
        $this->removeFiles();
    }

    /**
     * Remove keys.
     */
    public function forget()
    {
        $this->forgetKeys();
    }

    /**
     * @param $tag
     *
     * @return array|mixed
     */
    public function getKeysByTag($tag)
    {
        $this->loadKeys();
        $this->keys[$tag] = isset($this->keys[$tag]) ? $this->keys[$tag] : [];

        return $this->keys[$tag];
    }

    /**
     * @param $tag
     *
     * @return array|mixed
     */
    public function getKeys()
    {
        $this->loadKeys();

        return $this->keys;
    }

    /**
     * Add key.
     */
    private function addKey($key, $tag)
    {
        $this->keys[$tag] = $this->getKeysByTag($tag);

        if (!in_array($key, $this->keys[$tag])) {
            $this->keys[$tag][] = $key;
        }
    }

    /**
     * Get store file from config.
     */
    private function storeFile()
    {
        if (trim($this->path) == '') {
            throw new CacheException('no valid path is set!!');
        }
        if (!is_dir($this->path)) {
            $this->files->makeDirectory($this->path, $mode = 0777, true, true);
        }

        return $this->path.$this->file;
    }

    /**
     * @return array|mixed
     */
    private function loadKeys()
    {
        if (is_array($this->keys) && count($this->keys)) {
            return $this->keys;
        }

        if (!file_exists($this->storeFile())) {
            $this->storeKeys();
        }

        $contents = $this->files->get($this->storeFile());
        $this->keys = json_decode($contents, true);

        return $this->keys;
    }

    /**
     * @return int
     */
    private function forgetKeys()
    {
        $this->loadKeys();

        if (isset($this->keys[$this->tag])) {
            unset($this->keys[$this->tag]);
        }

        $this->storeKeys();
    }

    /**
     * @return int
     */
    private function storeKeys()
    {
        $contents = json_encode($this->keys);

        return $this->files->put($this->storeFile(), $contents);
    }

    private function removeFiles()
    {
        return $this->files->deleteDirectory($this->path);
    }
}
