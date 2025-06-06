<?php
class count_of_bug_in_pri_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->pri])) $this->result[$row->pri] = array();
        $this->result[$row->pri][$row->id] = $row->id;
    }
}
