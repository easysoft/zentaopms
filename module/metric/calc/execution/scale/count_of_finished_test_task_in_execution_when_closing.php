<?php
/**
 * 按执行统计的执行关闭时已完成的测试任务数。
 * Count of finished test task in execution when closing.
 *
 * 范围：execution
 * 对象：task
 * 目的：scale
 * 度量名称：按执行统计的执行关闭时完成的测试任务数
 * 单位：个
 * 描述：按执行统计的执行关闭时已完成测试任务数表示执行关闭时任务状态为已完成的测试任务个数求和。该度量项反映了执行关闭时测试人员完成的测试任务个数，可以评估执行中测试人员的实际工作量和测试效率。
 * 定义：执行关闭时执行中满足以下条件的测试任务个数求和，条件是：任务类型为测试，状态为已完成或已关闭且关闭原因为已完成，过滤已删除的任务，过滤已删除的执行，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Tingting Dai <daitingting@xirangit.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_finished_test_task_in_execution_when_closing extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.type', 't1.status', 't1.closedReason', 't1.finishedDate', 't1.closedDate', 't1.execution', 't2.closedDate AS executionClosedDate');

    public $result = array();

    public $initRecord = false;

    public function calculate($row)
    {
        $finishedDate = $this->isDate($row->finishedDate) ? $row->finishedDate : $row->closedDate;
        if(!helper::isZeroDate($row->executionClosedDate))
        {
            if($row->type == 'test' && ($row->status == 'done' || $row->status == 'closed' && $row->closedReason == 'done') && $finishedDate <= $row->executionClosedDate)
            {
                if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
                $this->result[$row->execution] ++;
            }
        }
    }

    public function getResult($options = array())
    {
        $executions = $this->getExecutions();
        $closedExecutions = array_filter($executions, function($execution) { return $execution->status === 'closed'; });
        foreach($closedExecutions as $executionID => $executionInfo)
        {
            if(!isset($this->result[$executionID])) $this->result[$executionID] = 0;
        }

        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
