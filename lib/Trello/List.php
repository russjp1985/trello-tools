<?php

class Trello_List extends Trello_Resource {

    const URL = '/boards/{{board_id}}/lists/{{id}}';

    public function args() {
        return array('board_id' => $this->board_id, 'id' => $this->id);
    }
}
