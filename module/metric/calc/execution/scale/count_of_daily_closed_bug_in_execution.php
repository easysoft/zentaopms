<?php
class count_of_daily_closed_bug_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array('t1.execution', 't1.status', 't1.closedDate');

    public $result = array();

    public function calculate($row)
    {
        if($row->status != 'closed') return false;

        $year = $this->getYear($row->closedDate);
        if(!$year) return false;

        $date = substr($row->closedDate, 0, 10);
        list($year, $month, $day) = explode('-', $date);

        if(!isset($this->result[$row->execution]))                      $this->result[$row->execution] = array();
        if(!isset($this->result[$row->execution][$year]))               $this->result[$row->execution][$year] = array();
        if(!isset($this->result[$row->execution][$year][$month]))       $this->result[$row->execution][$year][$month] = array();
        if(!isset($this->result[$row->execution][$year][$month][$day])) $this->result[$row->execution][$year][$month][$day] = 0;

        $this->result[$row->execution][$year][$month][$day] ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'year', 'month', 'day'));
        return $this->filterByOptions($records, $options);
    }
}
