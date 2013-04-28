<?php

abstract class Trello_Resource {

    protected $_data = array();

    protected $_associations = array();

    protected abstract function args();

    protected function fetch() {
        $url = $this->format(static::URI, $this->args()); 
        $trello = Trello::getInstance();
        $this->_data = $trello->get($url);
    }

    protected function set($data) {
        $this->_data = $data;
    }

    protected function format($string, $args) {
        foreach ($args as $arg => $value) {
            $string = str_replace('{{'.$arg.'}}', $value, $string);
        }
        return $string;
    }

    protected function loadAssociation($param, $args=array()) {
        list($class, $uri, $default_args) = $this->associations[$param];
        if (empty($default_args)) {
            $default_args = array();
        }
        $trello = Trello::getInstance();

        $data = $trello->get($this->format($uri, $this->args()), array_merge($default_args, $args));
        $this->_associations[$param] = array();
        foreach ($data as $item) {
            $model = new $class();
            $model->set($item);
            $this->_associations[$param][$item['id']] = $model;
        }
    }

    public function __get($param) {
        if (empty($this->_data)) {
            $this->fetch();
        }
        if (isset($this->_data[$param])) {
            return $this->_data[$param];
        }
        if (isset($this->associations[$param]) && empty($this->_associations[$param])) {
            $this->loadAssociation($param);
        }
        if (isset($this->_associations[$param])) {
            return $this->_associations[$param];
        }
        return null;
    }

}
