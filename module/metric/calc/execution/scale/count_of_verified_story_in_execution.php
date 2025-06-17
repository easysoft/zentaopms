<?php
class count_of_verified_story_in_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        $execution    = $row->project;
        $stage        = $row->stage;
        $closedReason = $row->closedReason;

        if(!isset($this->result[$execution])) $this->result[$execution] = 0;

        if(in_array($stage, array('verified', 'delivering', 'delivered', 'released'))) $this->result[$execution] += 1;
        if($closedReason == 'done') $this->result[$execution] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
