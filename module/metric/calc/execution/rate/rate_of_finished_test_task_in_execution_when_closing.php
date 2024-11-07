<?php
/**
 * 按执行统计的执行关闭时测试任务完成率
 * Rate of finished test task in execution when closing
 *
 * 范围：execution
 * 对象：execution
 * 目的：scale
 * 度量名称：按执行统计的执行关闭时测试任务完成率
 * 单位：%
 * 描述：按执行统计的测试任务按计划完成率是指执行关闭时已完成的测试任务数与执行开始时计划的测试任务数的比率。该度量项反映了团队能否按期完成规划的测试任务，可以帮助团队识别执行中存在的潜在问题，例如测试介入时间晚等。
 * 定义：复用： 按执行统计的执行关闭时已完成的测试任务数、按执行统计的测试任务数；公式：按执行统计的执行关闭时已完成的测试任务数/按执行统计的测试任务数
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class rate_of_finished_test_task_in_execution_when_closing extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.type', 't1.status', 't1.closedReason', 't1.closedDate', 't1.execution', 't2.multiple', 't2.closedDate as executionClosed', 't3.closedDate as projectClosed');

    public $result = array();

    public $initRecord = false;

    public function calculate($row)
    {
        $executionClosed = $row->multiple == '1' ? $row->executionClosed : $row->projectClosed;
        if(!helper::isZeroDate($executionClosed))
        {
            if($row->type !== 'test') return;

            $execution = $row->execution;
            $status    = $row->status;
            $closedReason = $row->closedReason;
            $closedDate = $row->closedDate;
            if($closedDate) $closedDate = date('Y-m-d', strtotime($closedDate));

            if(!isset($this->result[$execution])) $this->result[$execution] = array('finished' => 0, 'total' => 0);
            if(($status == 'done' || ($status == 'closed' && $closedReason == 'done')) && $closedDate && $closedDate <= $executionClosed) $this->result[$execution]['finished'] ++;
            $this->result[$execution]['total'] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = array();
        $executions = $this->getExecutions();
        $closedExecutions = array_filter($executions, function($execution) { return $execution->status === 'closed'; });
        foreach($closedExecutions as $executionID => $executionInfo)
        {
            if(!isset($this->result[$executionID])) $this->result[$executionID] = array('finished' => 0, 'total' => 0);
        }

        foreach($this->result as $execution => $value)
        {
            $rate = $value['total'] == 0 ? 0 : round($value['finished'] / $value['total'], 4);
            $records[] = array('execution' => $execution, 'value' => $rate);
        }
        return $this->filterByOptions($records, $options);
    }
}
