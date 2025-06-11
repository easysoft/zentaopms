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

    public function getResult($options = array())
    {
        foreach($this->result as $lastRunResult => $cases)
        {
            if(!is_array($cases))
            {
                unset($this->result[$lastRunResult]);
                continue;
            }
            $this->result[$lastRunResult] = count($cases);
        }

        $records = $this->getRecords(array('result', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
