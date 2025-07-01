<?php
class count_of_delayed_execution_in_project extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
