<?php
/**
 * Created by PhpStorm.
 * User: stuart
 * Date: 15/04/2016
 * Time: 16:25
 */

namespace Bryter\Helpers\Config;


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
    public $path;

    /**
     * __construct
     *
     * @param $path
     * @param array $data
     */
    function __construct($path, $data = array())
    {
        $this->path = $path;

        if (!count($data)) $this->loadConfigData($path);
        else $this->data = $data;
    }


    /**
     * get
     *
     * @param $key
     * @param $default
     * @return mixed
     */
    public function get($key, $default) {

        if (array_key_exists($key, $this->data))
            return $this->data[$key];
        else $this->set($key, $default);

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
    public function save($path = null) {
        if (is_null($path)) $path = $this->path;

        try {
            file_put_contents($path, json_encode($this->data));
            return true;
        } catch(\ErrorException $error) {
            Log::error('Config file write failed: ' . $path);
            return false;
        }
    }

    /**
     * refresh
     *
     * @param null $path
     * @return array
     */
    public function refresh($path = null) {
        if (is_null($path)) $path = $this->path;

        return $this->loadConfigData($path);
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
    public function loadConfigData($path = null) {
        if (is_null($path)) $path = $this->path;

        $dataArray = array();

        if (is_file($path)) {
            $dataArray = json_decode(
                file_get_contents($path)
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
    public static function has($path, $key) {

        if (self::hasConfig($path)) {
            $dataArray = (array) json_decode(
                file_get_contents($path)
            );

            return array_key_exists($key, $dataArray);
        } else return false;
    }

    /**
     * @param $path
     * @return bool
     */
    public static function hasConfig($path) {
        if (is_file($path)) return true;
        else return false;
    }

}
