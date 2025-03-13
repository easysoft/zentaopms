<?php
class avg_of_dev_efficiency extends baseCalc
{
    public $dataset = 'getAllDevStories';

    public $fieldList = array('t1.closedDate', 't1.closedReason', 't1.releasedDate', 't1.estimate');

    public $result = array();

    public function calculate($row)
    {
        $releasedDate = $row->releasedDate;
        $closedDate   = $row->closedDate;
        $closedReason = $row->closedReason;
        $estimate     = $row->estimate;

        if((!helper::isZeroDate($closedDate) && $closedReason == 'done') || !helper::isZeroDate($releasedDate))
        {
            // 如果关闭且关闭原因为已完成，没有发布时间，该需求为直接关闭跳过了中间流程，此时用关闭时间作为发布时间
            if(!helper::isZeroDate($closedDate) && $closedReason == 'done' && helper::isZeroDate($releasedDate)) $releasedDate = $closedDate;

            $year  = $this->getYear($releasedDate);
            $month = substr($releasedDate, 5, 2);

            if(!isset($this->result[$year])) $this->result[$year] = array();
            if(!isset($this->result[$year][$month])) $this->result[$year][$month] = 0;
            $this->result[$year][$month] += $estimate;
        }
    }

    public function getResult($options = array())
    {
        global $app;
        $users = $app->control->loadModel('user')->getPairs('noempty|nodeleted');

        $records = $this->getRecords(array('year', 'month', 'value'));
        foreach($records as $index => $record)
        {
            if($record['value']) $records[$index]['value'] = round($record['value'] / count($users), 4);
        }
        return $this->filterByOptions($records, $options);
    }
}
