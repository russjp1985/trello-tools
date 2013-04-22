<?php

class Trello_Action extends Trello_Resource {

    const URL = '/boards/{{board_id}}/actions/{{id}}';

    public function args() {
        return array('board_id' => $this->board_id, 'id' => $this->id);
    }

}
