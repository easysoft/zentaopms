<?php
class scale_of_verified_story_in_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

     public $result = 0;

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('value'));
        return $this->filterByOptions($records, $options);
    }
}
