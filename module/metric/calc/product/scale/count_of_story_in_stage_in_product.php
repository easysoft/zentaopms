<?php
class count_of_story_in_stage_in_product extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->stage])) $this->result[$row->stage] = array();
        $this->result[$row->stage][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $stage => $stories)
        {
            if(!is_array($stories))
            {
                unset($this->result[$stage]);
                continue;
            }
            $this->result[$stage] = count($stories);
        }

        $records = $this->getRecords(array('stage', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
