<?php
/**
 * 按执行统计的当期Bug转任务的任务消耗工时
 * Consumed of task from current execution bug in execution
 *
 * 范围：execution
 * 对象：task
 * 目的：hour
 * 度量名称：按执行统计的当期Bug转任务的任务消耗工时
 * 单位：小时
 * 描述：按执行统计的往期Bug转任务的任务消耗工时数是指执行中当期产生的Bug转任务消耗的工时总和。该度量项反映了任务来源为当期Bug的资源使用情况，可以帮助团队识别当期产生的缺陷占用的团队资源以及缺陷管理中存在的问题。
 * 定义：执行中满足以下条件的任务消耗工时数求和，条件是：任务来源为当期Bug，过滤已删除的任务，过滤父任务，过滤已删除的执行，过滤已删除的项目。当期Bug的定义：Bug的影响版本是执行中创建的版本。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class consumed_of_task_from_current_execution_bug_in_execution extends baseCalc
{
    public $result = array();

    public $dataset = 'getTasksWithBuildInfo';

    public $fieldList = array('t1.id as taskID', 't1.execution', 't1.consumed', 't2.openedBuild', 't5.id as currentBuild');

    public function calculate($row)
    {
        if(!isset($this->result[$row->execution]))
        {
            $this->result[$row->execution] = array();
            $this->result[$row->execution]['consumed'] = 0;
            $this->result[$row->execution]['taskList'] = array();
        }

        $openedBuildList = explode(',', $row->openedBuild);
        if(!in_array($row->taskID, $this->result[$row->execution]['taskList']) && in_array($row->currentBuild, $openedBuildList))
        {
            $this->result[$row->execution]['taskList'][] = $row->taskID;
            $this->result[$row->execution]['consumed']  += $row->consumed;
        }
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $execution => $executionData) $records[] = array('execution' => $execution, 'value' => $executionData['consumed']);

        return $this->filterByOptions($records, $options);
    }
}
