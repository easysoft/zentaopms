<?php
class count_of_bug_in_status_execution extends baseCalc
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
        foreach($this->result as $status => $tasks)
        {
            if(!is_array($tasks))
            {
                unset($this->result[$status]);
                continue;
            }

            $this->result[$status] = count($tasks);
        }

        $records = $this->getRecords(array('status', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
