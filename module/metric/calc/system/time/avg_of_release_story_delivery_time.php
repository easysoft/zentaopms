<?php
class avg_of_release_story_delivery_time extends baseCalc
{
    public $dataset = 'getAllDevStories';

    public $fieldList = array('t1.closedDate', 't1.closedReason', 't1.releasedDate', 't1.openedDate', 't1.stage');

    public $result = array();

    public function calculate($row)
    {
        $releasedDate = $row->releasedDate;
        $openedDate   = $row->openedDate;
        $closedDate   = $row->closedDate;
        $closedReason = $row->closedReason;
        $stage        = $row->stage;

        if(!in_array($stage, array('released', 'closed'))) return;
        if(helper::isZeroDate($closedDate) && helper::isZeroDate($releasedDate)) return;
        // 如果关闭且关闭原因为已完成，没有发布时间，该需求为直接关闭跳过了中间流程，此时用关闭时间作为发布时间
        if(!helper::isZeroDate($closedDate) && $closedReason == 'done' && helper::isZeroDate($releasedDate)) $releasedDate = $closedDate;

        $year  = $this->getYear($releasedDate);
        $month = substr($releasedDate, 5, 2);

        $date1 = new DateTime($releasedDate);
        $date2 = new DateTime($openedDate);

        $days = $date1->diff($date2)->days;

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = array('count' => 0, 'days' => 0);

        $this->result[$year][$month]['count'] ++;
        $this->result[$year][$month]['days']  += $days;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $monthInfo)
        {
            foreach($monthInfo as $month => $info)
            {
                $record = array('year' => $year, 'month' => $month);
                $record['value'] = $info['count'] ? round($info['days'] / $info['count'], 4) : 0;

                $records[] = $record;
            }
        }
        return $this->filterByOptions($records, $options);
    }
}
