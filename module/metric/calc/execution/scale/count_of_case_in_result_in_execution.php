<?php
class count_of_case_in_result_in_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->lastRunResult])) $this->result[$row->lastRunResult] = array();
        $this->result[$row->lastRunResult][$row->id] = $row->id;
    }
}
