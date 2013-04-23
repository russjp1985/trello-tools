<?php

class TrelloTest extends TPTest {

    public function beforeEach() {
        $this->config = array(
            'key' => uniqid(),
            'token' => uniqid(),
        );
        $trello = TPTMock::getReflection('Trello',
            array('makeRequest' => array())
        );
        $name = $trello->name;
        $this->trello = $name::connect($this->config);
    }

    public function itIsATrello() {
        $this->expect($this->trello)->toBeInstanceOf('Trello');
    }

    public function itGetsAResource() {
        $this->trello->get('/boards', array('since' => 123456, 'before' => '56778'));
        $this->expect($this->trello)->toHaveCalled('makeRequest', 1);

        // Resource URL
        $key = $this->config['key'];
        $token = $this->config['token'];
        $url = "https://api.trello.com/1/boards?since=123456&before=56778&token=$token&key=$key";
        $this->expect($this->trello)->toHaveCalledWith('makeRequest', array('GET', $url));
    }
}
