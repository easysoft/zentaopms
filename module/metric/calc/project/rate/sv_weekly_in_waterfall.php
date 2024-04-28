<?php
/**
 * 按瀑布项目统计的截止本周的进度偏差率。
 * Sv weekly in waterfall.
 *
 * 范围：project
 * 对象：task
 * 目的：rate
 * 度量名称：按瀑布项目统计的截止本周的进度偏差率
 * 单位：%
 * 描述：按瀑布项目统计的截止本周的进度偏差率是用来衡量项目截止本周的进度与计划进度之间的差异。它通过计算已完成的工作量与计划工作量之间的差异来评估项目的进展情况。
 * 定义：复用： 按瀑布项目统计的截止本周已完成任务工作的预计工时(EV) 、按瀑布项目统计的截止本周的任务的计划完成工时(PV)，公式： 按瀑布项目统计的截止本周的进度偏差率=(EV-PV)/PV*100%
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class sv_weekly_in_waterfall extends baseCalc
{
    public $result = array();

    public $reuse = true;

    public $reuseMetrics = array('pv' => 'pv_of_weekly_task_in_waterfall', 'ev' => 'ev_of_weekly_finished_task_in_waterfall');

    public $reuseRule = '({ev} - {pv}) / {pv}';

    public function calculate($metrics)
    {
        $pvs = $metrics['pv'];
        $evs = $metrics['ev'];
        if(empty($pvs) || empty($evs)) return false;

        $all = array_merge($pvs, $evs);

        $projects = array_column($all, 'project', 'project');
        $years    = array_column($all, 'year', 'year');
        $weeks    = array_column($all, 'week', 'week');

        $pvs = $this->generateUniqueKey($pvs);
        $evs = $this->generateUniqueKey($evs);

        foreach($projects as $project)
        {
            foreach($years as $year)
            {
                foreach($weeks as $week)
                {
                    $key = "{$project}_{$year}_{$week}";
                    $pv  = isset($pvs[$key]) ? $pvs[$key] : 0;
                    $ev  = isset($evs[$key]) ? $evs[$key] : 0;

                    if($pv == 0) continue;
                    $this->result[$project] = array($year => array($week => round(($ev - $pv) / $pv, 4)));
                }
            }
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'year', 'week', 'value'));
        return $this->filterByOptions($records, $options);
    }

    public function generateUniqueKey($records)
    {
        $uniqueKeyRecords = array();
        foreach($records as $record)
        {
            $key = "{$record['project']}_{$record['year']}_{$record['week']}";
            $uniqueKeyRecords[$key] = $record['value'];
        }

        return $uniqueKeyRecords;
    }
}
