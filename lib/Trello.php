<?php

class Trello {

    const API_VERSION = '1';
    const API_URL = 'https://api.trello.com/';

    protected $key;
    protected $token;

    protected static $instance;

    public static function getInstance() {
        if (empty(static::$instance)) {
            throw new Exception("You must call Trello::connect first!");
        }
        return static::$instance;
    }

    public static function connect($config=array()) {
        static::$instance = static::create($config);
        return static::getInstance();
    }

    protected static function create($config) {
        $class = get_called_class();
        return new $class($config['key'], $config['token']);
    }

    private function __construct($key, $token) {
        $this->key = $key;
        $this->token = $token;
    }

    public function get($resource, $args=array()) {
        $url = $this->buildUrl($resource, $args);
        return $this->makeRequest('GET', $url);
    }

    public function makeRequest($method, $url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        $output = curl_exec($ch);
        return json_decode($output, true);
    }

    public function buildUrl($resource, $args=array()) {
        $url = self::API_URL.self::API_VERSION.$resource;
        $args['token'] = $this->token;
        $args['key'] = $this->key;
        $url .= '?' . http_build_query($args);
        return $url;
    }

}
