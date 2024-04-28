<?php
/**
 * 按瀑布项目统计截止本周的实际花费工时(AC)。
 * Ac of weekly all in waterfall.
 *
 * 范围：project
 * 对象：effort
 * 目的：hour
 * 度量名称：按瀑布项目统计截止本周的实际花费工时(AC)
 * 单位：小时
 * 描述： 按瀑布项目统计的截止本周实际花费工时指的是在瀑布项目管理方法中，截止本周实际花费的工时总数。这个度量项用于评估实际工作量和预计工作量之间的差异，有助于估计项目的真实进展情况。AC的值越接近EV，代表项目团队在任务执行方面表现得越好。
 * 定义：瀑布项目中本周结束之前所有日志记录的工时之和 过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class ac_of_all_in_waterfall extends baseCalc
{
    public $result = array();

    public $rows = array();

    public function getStatement()
    {
        $efforts = $this->dao->select('t1.project,t1.`date`,t1.consumed')->from(TABLE_EFFORT)->alias('t1')
            ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
            ->where('t1.deleted')->eq('0')
            ->andWhere('t2.deleted')->eq('0')
            ->andWhere('t2.model')->eq('waterfall')
            ->andWhere('t2.type')->eq('project')
            ->query();

        return $efforts;
    }

    public function calculate($row)
    {
        $this->rows[] = $row;
    }

    public function getResult($options = array())
    {
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
                        $AC      = $this->getAC($row, $date);
                        $project = $row->project;

                        if(!isset($result[$project])) $result[$project] = array();
                        if(!isset($result[$project][$year])) $result[$project][$year] = array();
                        if(!isset($result[$project][$year][$week])) $result[$project][$year][$week] = 0;
                        $result[$project][$year][$week] += $AC;
                    }
                }
            }
        }
        else
        {
            $date = date('Y-m-d');

            foreach($this->rows as $row)
            {
                $AC      = $this->getAC($row, $date);
                $week    = $this->getWeek($date);
                $year    = $this->getYear($date);
                $project = $row->project;

                if(!isset($result[$project])) $result[$project] = array();
                if(!isset($result[$project][$year])) $result[$project][$year] = array();
                if(!isset($result[$project][$year][$week])) $result[$project][$year][$week] = 0;
                $result[$project][$year][$week] += $AC;
            }
        }

        $records = $this->getRecords(array('project', 'year', 'week', 'value'), $result);
        return $this->filterByOptions($records, $options);
    }

    public function getAC($row, $currentDate)
    {
        $project  = $row->project;
        $date     = $row->date;
        $consumed = $row->consumed;

        $lastDay = $this->getLastDay($currentDate);
        if(empty($lastDay)) $lastDay = $this->getThisMonday($currentDate);

        return $date > $lastDay ? 0 : (is_null($consumed) ? 0 : $consumed);
    }
}
