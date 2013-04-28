<?php

class Trello_Board extends Trello_Resource {

    const URI = "/boards/{{board_id}}";

    public $board_id = null;

    public $associations = array(
        'lists'   => array('Trello_List', '/boards/{{board_id}}/lists'),
        'actions' => array('Trello_Action', '/boards/{{board_id}}/actions'),
        'members' => array('Trello_Member', '/boards/{{board_id}}/members',
            array('fields' => 'fullName,avatarHash'),
        ),
    );

    public $member_actions = array();
    public $member_action_summary = array();

    public function __construct($board_id) {
        $this->board_id = $board_id;
    }

    protected function args() {
        return array('board_id' => $this->board_id);
    }

    public function sortActionsByMember($start, $end) {
        $this->opts = array(
            'since' => $start,
            'before' => $end,
        );
        $this->loadAssociation('actions', array('before' => $end, 'since' => $start, 'limit' => 250));
        foreach ($this->actions as $action) {
            $member = $action->memberCreator;
            $member_id = $member['id'];
            if (empty($this->member_actions[$member_id])) {
                $this->member_actions[$member_id] = array();
                $this->member_action_summary[$member_id] = array();
            }
            $this->member_actions[$member_id][$action->id] = $action;
        }
        return $this;
    }

    public function summarize() {
        $pretty = 'l, F jS Y';
        $this->summary = "Summary from ". date($pretty, $this->opts['since']). " to ". date($pretty, $this->opts['before']);
        foreach ($this->member_actions as $member_id => $actions) {
            foreach ($actions as $action) {
                $summary = false;
                $data = $action->data;
                $card = $data['card']['name'];
                $old = $data['old'];

                switch ($action->type) {
                case 'updateCard':
                    // The card was moved from one list to another
                    if (isset($old['idList'])) {
                        $old_list = $data['listBefore']['name'];
                        $new_list = $data['listAfter']['name'];
                        $summary = "<em>&quot;$card&quot;</em> went from <b>$old_list</b> to <b>$new_list</b>";
                    }
                    break;
                }

                if (!empty($summary)) {
                    $this->member_action_summary[$member_id][] = $summary;
                }
            }
        }
    }

}
