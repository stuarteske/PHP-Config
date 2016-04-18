<?php

namespace Bryter\Helpers\Config;

use Illuminate\Support\Facades\Log;


/**
 * Class Config
 * @package Bryter\Helpers\Config
 */
class Config
{

    /**
     * @var array
     */
    public $data;

    /**
     * @var string
     */
    public $storageDir;

    /**
     * @var string
     */
    public $filename = 'config.json';

    /**
     * __construct
     *
     * @param $path
     * @param array $data
     */
    function __construct($storageDir = null, $data = array())
    {
        if (!is_null($storageDir)) $this->storageDir = $storageDir;

        if (!count($data)) $this->loadConfigData($storageDir);
        else $this->data = $data;
    }


    /**
     * get
     *
     * @param $key
     * @param $default
     * @return mixed
     */
    public function get($key, $default = false) {

        if (array_key_exists($key, $this->data))
            return $this->data[$key];

        return $default;
    }

    /**
     * set
     *
     * @param $key
     * @param $value
     * @return bool
     */
    public function set($key, $value) {

        $this->data[$key] = $value;

        return array_key_exists($key, $this->data);
    }

    /**
     * save
     *
     * @param null $path
     */
    public function save($storageDir = null) {
        if (is_null($storageDir)) $storageDir = $this->storageDir;

        $fullPath = $this->getFullpath($storageDir);

        try {
            file_put_contents($fullPath, json_encode($this->data));
            return true;
        } catch(\ErrorException $error) {
            Log::error('Config file write failed: ' . $fullPath);
            return false;
        }
    }

    /**
     * refresh
     *
     * @param null $path
     * @return array
     */
    public function refresh($storageDir = null) {
        if (is_null($storageDir)) $storageDir = $this->storageDir;

        return $this->loadConfigData($storageDir);
    }

    /**
     * _toString
     *
     * @return string
     */
    public function _toString() {
        return json_encode($this->data);
    }

    /**
     * _toArray
     *
     * @return array
     */
    public function _toArray() {
        return $this->data;
    }

    /**
     * loadConfigData
     *
     * @param null $path
     * @return array
     */
    public function loadConfigData($storageDir = null) {
        if (is_null($storageDir)) $storageDir = $this->storageDir;

        $fullPath = $this->getFullpath($storageDir);

        $dataArray = array();

        if (is_file($fullPath)) {
            $dataArray = json_decode(
                file_get_contents($fullPath)
            );
        }

        $this->data = (array) $dataArray;

        return $this->data;
    }

    /**
     * @param $path
     * @param $key
     * @return bool
     */
    public function has($key, $storageDir = null) {
        if (is_null($storageDir)) $storageDir = $this->storageDir;

        $fullPath = $this->getFullpath($storageDir);

        if (self::hasConfig($storageDir)) {
            $dataArray = (array) json_decode(
                file_get_contents($fullPath)
            );

            return array_key_exists($key, $dataArray);
        } else return false;
    }

    /**
     * @param $path
     * @return bool
     */
    public function hasConfig($storageDir = null) {
        if (is_null($storageDir)) $storageDir = $this->storageDir;

        $fullPath = $this->getFullpath($storageDir);

        if (is_file($fullPath)) return true;
        else return false;
    }

    public function getStorageDir() {
        return $this->storageDir;
    }

    public function setStorageDir($storageDir) {
        $this->storageDir = $storageDir;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function getFullpath($storageDir = null) {
        if (is_null($storageDir)) $storageDir = $this->storageDir;

        $fullPath = $storageDir . DIRECTORY_SEPARATOR . $this->getFilename();

        return $fullPath;
    }

    /**
     * @param $storageDir
     * @return mixed
     */
    public function hasStorageDir($storageDir = null) {

        if (is_null($storageDir)) $storageDir = $this->storageDir;

        return is_dir(
            $storageDir
        );
    }

    /**
     * @param $username
     * @return mixed
     */
    public function createStorageDir($storageDir = null) {

        if (is_null($storageDir)) $storageDir = $this->storageDir;

        if (!$this->hasStorageDir($storageDir) ) mkdir(
            $storageDir,
            '0755',
            true
        );

        return $this->hasStorageDir($storageDir);
    }



}
