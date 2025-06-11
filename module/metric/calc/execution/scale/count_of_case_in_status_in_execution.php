<?php
class count_of_case_in_status_in_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->status])) $this->result[$row->status] = array();
        $this->result[$row->status][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $status => $cases)
        {
            if(!is_array($cases)) continue;

            $this->result[$status] = count($cases);
        }

        $records = $this->getRecords(array('status', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
