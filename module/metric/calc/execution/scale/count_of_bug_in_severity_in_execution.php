<?php
class count_of_bug_in_severity_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->severity])) $this->result[$row->severity] = array();
        $this->result[$row->severity][$row->id] = $row->id;
    }
}
