<?php
class count_of_delayed_execution_in_project extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $nowDate = helper::now();
        $end     = $row->end;
        $year    = $this->getYear($end);
        if(!$year) return false;

        $nextDate = date('Y-m-d', strtotime($end . ' +1 day'));
        if(strtotime($nowDate) <= strtotime($nextDate)) return false;
        if(!isset($this->result[$project])) $this->result[$project] = 0;
        $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
