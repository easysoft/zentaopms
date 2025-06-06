<?php
class count_of_daily_created_bug_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array('t1.execution', 't1.openedDate');

    public $result = array();

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
