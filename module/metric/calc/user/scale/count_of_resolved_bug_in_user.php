<?php
class count_of_resolved_bug_in_user extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->resolvedBy])) $this->result[$row->resolvedBy] = array();
        $this->result[$row->resolvedBy][$row->id] = $row->id;
    }
}
