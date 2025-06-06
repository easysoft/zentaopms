<?php
class count_of_daily_resolved_bug_in_execution extends baseCalc
{
    public $dataset = 'getExecutionBugs';

    public $fieldList = array('t1.execution', 't1.status', 't1.resolvedDate');

    public $result = array();

    public function calculate($row)
    {
        if($row->status == 'active') return false;

        $year = $this->getYear($row->resolvedDate);
        if(!$year) return false;

        $date = substr($row->resolvedDate, 0, 10);
        list($year, $month, $day) = explode('-', $date);

        if(!isset($this->result[$row->execution]))                      $this->result[$row->execution] = array();
        if(!isset($this->result[$row->execution][$year]))               $this->result[$row->execution][$year] = array();
        if(!isset($this->result[$row->execution][$year][$month]))       $this->result[$row->execution][$year][$month] = array();
        if(!isset($this->result[$row->execution][$year][$month][$day])) $this->result[$row->execution][$year][$month][$day] = 0;

        $this->result[$row->execution][$year][$month][$day] ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'year', 'month', 'day', 'value'));

        return $this->filterByOptions($records, $options);
    }
}
