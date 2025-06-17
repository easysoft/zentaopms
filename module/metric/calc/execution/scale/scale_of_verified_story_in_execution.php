<?php
class scale_of_verified_story_in_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

     public $result = 0;

    public function calculate($row)
    {
        if($row->isParent == '1') return false;
        if(empty($row->estimate)) return null;

        if(in_array($row->stage, array('verified', 'delivering', 'delivered', 'released'))) $this->result += $row->estimate;
        if($row->closedReason == 'done') $this->result += $row->estimate;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
