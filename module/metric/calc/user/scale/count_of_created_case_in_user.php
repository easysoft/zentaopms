<?php
class count_of_created_case_in_user extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->openedBy])) $this->result[$row->openedBy] = array();
        $this->result[$row->openedBy][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $openedBy => $cases)
        {
            if(!is_array($cases))
            {
                unset($this->result[$openedBy]);
                continue;
            }
            $this->result[$openedBy] = count($cases);
        }

        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
