<?php
class avg_of_release_story_defect_density extends baseCalc
{
    public $dataset = 'getAllDevStoriesWithLinkBug';

    public $fieldList = array('t1.closedDate', 't1.closedReason', 't1.releasedDate', 't1.estimate', 't1.stage', "count(CASE WHEN t4.deleted = '0' THEN t3.id END) as linkbugcount");

    public $result = array();

    public function calculate($row)
    {
        $releasedDate = $row->releasedDate;
        $closedDate   = $row->closedDate;
        $closedReason = $row->closedReason;
        $estimate     = $row->estimate;
        $stage        = $row->stage;
        $linkbugcount = $row->linkbugcount;

        if(!in_array($stage, array('released', 'closed'))) return;
        if(helper::isZeroDate($closedDate) && helper::isZeroDate($releasedDate)) return;
        // 如果关闭且关闭原因为已完成，没有发布时间，该需求为直接关闭跳过了中间流程，此时用关闭时间作为发布时间
        if(!helper::isZeroDate($closedDate) && $closedReason == 'done' && helper::isZeroDate($releasedDate)) $releasedDate = $closedDate;

        $year  = $this->getYear($releasedDate);
        $month = substr($releasedDate, 5, 2);

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = array('estimates' => 0, 'bugs' => 0);

        $this->result[$year][$month]['estimates'] += $estimate;
        $this->result[$year][$month]['bugs']      += $linkbugcount;

    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $year => $monthInfo)
        {
            foreach($monthInfo as $month => $info)
            {
                $record = array('year' => $year, 'month' => $month);
                $record['value'] = $info['estimates'] ? round($info['bugs'] / $info['estimates'], 4) : 0;

                $records[] = $record;
            }
        }
        return $this->filterByOptions($records, $options);
    }
}
