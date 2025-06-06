<?php
class count_of_resolved_bug_in_user extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->resolvedBy])) $this->result[$row->resolvedBy] = array();
        $this->result[$row->resolvedBy][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $resolvedBy => $bugs)
        {
            if(!is_array($bugs)) continue;

            $this->result[$resolvedBy] = count($bugs);
        }

        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
