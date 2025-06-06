<?php
class count_of_activated_bug_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array('t1.status', 't1.execution');

    public $result = array();

    public function calculate($row)
    {
        $execution = $row->execution;
        $status    = $row->status;

        if(!isset($this->result[$execution])) $this->result[$execution] = 0;

        if($status == 'active') $this->result[$execution] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
