<?php
class count_of_assigned_task extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        $assignedTo = $row->assignedTo;
        $mode       = $row->mode;

        if($mode == 'multi') $assignedTo = $row->account;
        if(!isset($this->result[$assignedTo])) $this->result[$assignedTo] = array();
        $this->result[$assignedTo][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $assignedTo => $tasks)
        {
            if(!is_array($tasks))
            {
                unset($this->result[$type]);
                continue;
            }
            $this->result[$assignedTo] = count($tasks);
        }

        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
