<?php
class consume_of_task_in_type_in_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if($row->isParent == '1') return;
        if(empty($row->story))    return;

        if(!isset($this->result[$row->type])) $this->result[$row->type] = array();
        $this->result[$row->type][$row->id] = $row->consumed;
    }
}
