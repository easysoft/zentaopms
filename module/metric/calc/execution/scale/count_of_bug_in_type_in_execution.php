<?php
class count_of_bug_in_type_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->type])) $this->result[$row->type] = array();
        $this->result[$row->type][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $type => $bugs)
        {
            if(!is_array($bugs)) continue;

            $this->result[$type] = count($bugs);
        }

        $records = $this->getRecords(array('type', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
