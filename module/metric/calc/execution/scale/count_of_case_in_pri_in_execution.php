<?php
class count_of_case_in_pri_in_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->pri])) $this->result[$row->pri] = array();
        $this->result[$row->pri][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $pri => $cases)
        {
            if(!is_array($cases))
            {
                unset($this->result[$pri]);
                continue;
            }
            $this->result[$pri] = count($cases);
        }

        $records = $this->getRecords(array('pri', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
