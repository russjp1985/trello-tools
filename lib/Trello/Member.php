<?php

class Trello_Member extends Trello_Resource {

    const URL = '/boards/{{board_id}}/members/{{id}}';

    public function args() {
        return array('board_id' => $this->board_id, 'id' => $this->id);
    }
}
