<?php
/**
 * 按执行统计的截止执行开始当天的测试任务数。
 * Count of test task in execution when starting.
 *
 * 范围：execution
 * 对象：task
 * 目的：scale
 * 度量名称：按执行统计的截止执行开始当天的测试任务数
 * 单位：个
 * 描述：按执行统计的截止执行开始当天的测试任务数表示执行开始时已创建的测试任务的数量。该度量项反映了本期执行计划完成的测试任务数量，可以用于评估执行团队测试人员的工作负载。
 * 定义：截止执行开始当天23:59分的任务个数求和，任务类型为测试，过滤已删除的任务，过滤已取消的任务数，过滤已删除的执行，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Tingting Dai <daitingting@xirangit.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_test_task_in_execution_when_starting extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.type', 't1.status', 't1.openedDate', 't1.execution', 't2.realBegan');

    public $result = array();

    public $initRecord = false;

    public function calculate($row)
    {
        if($row->type == 'test' && $row->status != 'cancel' && !empty($row->realBegan) && date('Y-m-d', strtotime($row->openedDate)) <= $row->realBegan)
        {
            if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
            $this->result[$row->execution] ++;
        }
    }

    public function getResult($options = array())
    {
        $executions = $this->getExecutions();
        $beginExecutions = array_filter($executions, function($execution) { return !empty($execution->realBegan); });
        foreach($beginExecutions as $executionID => $executionInfo)
        {
            if(!isset($this->result[$executionID])) $this->result[$executionID] = 0;
        }

        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
