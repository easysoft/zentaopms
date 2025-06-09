<?php
class count_of_bug_in_status_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->status])) $this->result[$row->status] = array();
        $this->result[$row->status][$row->id] = $row->id;
    }
}
