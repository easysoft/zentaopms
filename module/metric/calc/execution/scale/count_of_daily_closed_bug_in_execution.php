<?php
/**
 * 按执行统计的每日关闭Bug数。
 * Count of daily closed bug in execution.
 *
 * 范围：execution
 * 对象：bug
 * 目的：scale
 * 度量名称：按执行统计的每日关闭Bug数
 * 单位：个
 * 描述：按执行统计的每日关闭Bug数是指每天在执行中每日关闭的Bug的数量。该度量项可以帮助我们了解开发团队对已解决的Bug进行确认与关闭的速度和效率，通过对比不同时间段的关闭Bug数，可以评估开发团队的协作和问题处理能力。
 * 定义：执行中Bug数求和，关闭时间为某日，过滤已删除的Bug，过滤已删除的执行。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
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
