<?php
class count_of_story_in_stage_in_product extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->stage])) $this->result[$row->stage] = array();
        $this->result[$row->stage][$row->id] = $row->id;
    }
}
