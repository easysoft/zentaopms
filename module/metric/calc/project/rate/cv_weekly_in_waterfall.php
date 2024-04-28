<?php
/**
 * 按瀑布项目统计的截止本周的成本偏差率。
 * Cv weekly in waterfall.
 *
 * 范围：project
 * 对象：task
 * 目的：rate
 * 度量名称：按瀑布项目统计的截止本周的成本偏差率
 * 单位：%
 * 描述：按瀑布项目统计的截止本周的成本偏差率用于衡量项目的实际成本与计划成本之间的差异。它通过计算已花费的成本与预计花费的成本之间的差异来评估项目的成本绩效。
 * 定义：复用： 按瀑布项目统计的截止本周已完成任务工作的预计工时、按瀑布项目统计的截止本周的实际花费工时(AC) 公式： 按瀑布项目统计的截止本周的成本偏差率=(EV-AC)/AC*100%
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class cv_weekly_in_waterfall extends baseCalc
{
    public $result = array();

    public $reuse = true;

    public $reuseMetrics = array('ac' => 'ac_of_weekly_all_in_waterfall', 'ev' => 'ev_of_weekly_finished_task_in_waterfall');

    public $reuseRule = '({ev} - {ac}) / {ac}';

    public function calculate($metrics)
    {
        $acs = $metrics['ac'];
        $evs = $metrics['ev'];
        if(empty($acs) || empty($evs)) return false;

        $all = array_merge($acs, $evs);

        $projects = array_column($all, 'project', 'project');
        $years    = array_column($all, 'year', 'year');
        $weeks    = array_column($all, 'week', 'week');

        $acs = $this->generateUniqueKey($acs);
        $evs = $this->generateUniqueKey($evs);

        foreach($projects as $project)
        {
            foreach($years as $year)
            {
                foreach($weeks as $week)
                {
                    $key = "{$project}_{$year}_{$week}";
                    $ac  = isset($acs[$key]) ? $acs[$key] : 0;
                    $ev  = isset($evs[$key]) ? $evs[$key] : 0;

                    if($ac == 0) continue;
                    $this->result[$project] = array($year => array($week => round(($ev - $ac) / $ac, 4)));
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
