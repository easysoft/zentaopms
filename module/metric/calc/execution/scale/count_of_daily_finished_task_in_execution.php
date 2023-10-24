<?php
/**
 * 按执行统计的日完成任务数。
 * Count of daily finished task in execution.
 *
 * 范围：execution
 * 对象：task
 * 目的：scale
 * 度量名称：按执行统计的日完成任务数
 * 单位：个
 * 描述：按执行统计的日完成任务数是指每天完成的任务数量。该度量项反映了团队的日常工作效率和任务完成速度。
 * 定义：执行中任务个数求和;状态为已完成;实际完成日期为某日;过滤已删除的任务;过滤已删除的执行;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_daily_finished_task_in_execution extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.status', 't1.finishedDate', 't1.execution');

    public $result = array();

    public function calculate($row)
    {
        $status = $row->status;
        $finishedDate = $row->finishedDate;
        $execution = $row->execution;

        if($status != 'done' || empty($finishedDate)) return false;

        if(empty($finishedDate)) return false;
        $date = substr($finishedDate, 0, 10);
        list($year, $month, $day) = explode('-', $date);
        if($year == '0000') return false;

        if(!isset($this->result[$execution]))                      $this->result[$execution] = array();
        if(!isset($this->result[$execution][$year]))               $this->result[$execution][$year] = array();
        if(!isset($this->result[$execution][$year][$month]))       $this->result[$execution][$year][$month] = array();
        if(!isset($this->result[$execution][$year][$month][$day])) $this->result[$execution][$year][$month][$day] = 0;

        $this->result[$row->execution][$year][$month][$day] ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'year', 'month', 'day', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
