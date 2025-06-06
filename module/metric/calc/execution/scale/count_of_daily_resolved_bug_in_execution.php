<?php
/**
 * 按执行统计的每日解决Bug数。
 * Count of daily resolved bug in execution.
 *
 * 范围：execution
 * 对象：bug
 * 目的：scale
 * 度量名称：按执行统计的每日解决Bug数
 * 单位：个
 * 描述：按执行统计的每日解决Bug数是指执行每日解决的Bug的数量。该度量项可以帮助我们了解开发团队解决Bug的速度和效率。
 * 定义：执行中Bug数求和，解决日期为某日，过滤已删除的Bug，过滤已删除的执行。
 *
 * @copyright Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    songchenxuan <songchenxuan@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
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
