<?php
/**
 * 按瀑布项目统计的截止本周的任务的计划完成工时(PV)。
 * Pv of weekly task in waterfall.
 *
 * 范围：project
 * 对象：task
 * 目的：hour
 * 度量名称：按瀑布项目统计的截止本周的任务的计划完成工时(PV)
 * 单位：小时
 * 描述：按瀑布项目统计的每周的任务的计划完成工时指的是在瀑布项目管理方法中，按计划需要完成的任务的总预计工时。这个度量项用于评估每周的任务的预期工作量，可用作与实际花费工时和已完成任务的预计工时进行比较。
 * 定义：任务截至日期小于等于本周结束日期，累加预计工时;任务预计开始日期小于或等于本周结束日期，预计截至日期大于本周结束日期，累加预计工时=(任务的预计工时÷任务工期天数)x 任务预计开始到本周结束日期的天数;条件：过滤父任务，过滤已删除的任务，过滤已取消的任务，过滤已删除的执行的任务，过滤已删除的项目；任务未填写预计开始日期时默认取任务所属阶段的计划开始日期；任务未填写预计截至日期，预计截至日期默认取任务所属阶段的计划完成日期，时间只计算后台维护的工作日。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class pv_of_weekly_task_in_waterfall extends baseCalc
{
    public $result = array();

    public $rows = array();

    public function getStatement()
    {
        $tasks = $this->dao->select('t1.id,t1.estStarted,t1.deadline,t1.estimate,t1.status,t1.closedReason,t2.begin as executionBegin,t2.end as executionEnd,t3.id as project')
            ->from(TABLE_TASK)->alias('t1')
            ->leftJoin(TABLE_EXECUTION)->alias('t2')->on('t1.execution = t2.id')
            ->leftJoin(TABLE_PROJECT)->alias('t3')->on('t2.project = t3.id')
            ->where('t1.parent')->ge(0)
            ->andWhere('t1.deleted')->eq('0')
            ->andWhere('t1.status')->ne('cancel')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t3.type')->eq('project')
            ->andWhere('t3.model')->eq('waterfall')
            ->query();

        return $tasks;
    }

    public function calculate($row)
    {
        $this->rows[] = $row;
    }

    public function getResult($options = array())
    {
        $result = array();
        if(isset($options['year']) && isset($options['week']))
        {
            $years = explode(',', $options['year']);
            $weeks = explode(',', $options['week']);

            foreach($years as $year)
            {
                foreach($weeks as $week)
                {
                    $date = $this->getFridayByWeek($year, $week);

                    foreach($this->rows as $row)
                    {
                        $PV      = $this->getPV($row, $date);
                        $project = $row->project;

                        if(!isset($result[$project])) $result[$project] = array();
                        if(!isset($result[$project][$year])) $result[$project][$year] = array();
                        if(!isset($result[$project][$year][$week])) $result[$project][$year][$week] = 0;
                        $result[$project][$year][$week] += $PV;
                    }
                }
            }
        }
        else
        {
            $date = date('Y-m-d');

            foreach($this->rows as $row)
            {
                $PV      = $this->getPV($row, $date);
                $week    = $this->getWeek($date);
                $year    = $this->getYear($date);
                $project = $row->project;

                if(!isset($result[$project])) $result[$project] = array();
                if(!isset($result[$project][$year])) $result[$project][$year] = array();
                if(!isset($result[$project][$year][$week])) $result[$project][$year][$week] = 0;
                $result[$project][$year][$week] += $PV;
            }
        }

        $records = $this->getRecords(array('project', 'year', 'week', 'value'), $result);
        return $this->filterByOptions($records, $options);
    }

    private function getPV($row, $date)
    {
        $estStarted   = $row->estStarted;
        $deadline     = $row->deadline;
        $estimate     = $row->estimate;
        $status       = $row->status;
        $closedReason = $row->closedReason;
        $project      = $row->project;

        $executionBegin = $row->executionBegin;
        $executionEnd   = $row->executionEnd;

        if(helper::isZeroDate($estStarted)) $estStarted = $executionBegin;
        if(helper::isZeroDate($deadline))   $deadline   = $executionEnd;

        $lastDay = $this->getLastDay($date);
        $monday  = $this->getThisMonday($date);
        if(empty($lastDay)) $lastDay = $monday;

        $PV = 0;
        if($deadline <= $lastDay)
        {
            $PV = $estimate;
        }
        elseif($estStarted <= $lastDay)
        {
            $fullDays       = $this->getActualWorkingDays($estStarted, $deadline);
            $weekActualDays = $this->getActualWorkingDays($estStarted, $lastDay);
            if(!empty($fullDays) and !empty($weekActualDays)) $PV = round(count($weekActualDays) / count($fullDays) * $estimate, 2);
        }

        return $PV;
    }
}
