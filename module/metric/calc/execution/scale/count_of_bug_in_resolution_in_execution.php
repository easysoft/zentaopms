<?php
class count_of_bug_in_resolution_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->resolution])) $this->result[$row->resolution] = array();
        $this->result[$row->resolution][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $resolution => $bugs)
        {
            if(!is_array($bugs)) continue;

            $this->result[$resolution] = count($bugs);
        }

        $records = $this->getRecords(array('resolution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
