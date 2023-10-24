<?php
/**
 * 按执行统计的任务进度。
 * Progress of task in execution.
 *
 * 范围：execution
 * 对象：task
 * 目的：rate
 * 度量名称：按执行统计的任务进度
 * 单位：%
 * 描述：按执行统计的任务进度是指执行团队按已消耗的工时数与已消耗和剩余的工时数的比率。该度量项反映了任务的执行进展情况，可以帮助团队评估任务是否按计划进行并做出相应调整。
 * 定义：复用：;按执行统计的任务消耗工时数;按执行统计的任务剩余工时数;公式：;按执行统计的任务进度=按执行统计的任务消耗工时数/（按执行统计的任务消耗工时数+按执行统计的任务剩余工时数）;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class progress_of_task_in_execution extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.consumed', 't1.left', 't1.execution');

    public $result = array();

    public function calculate($row)
    {
        $consumed = !empty($row->consumed) ? $row->consumed : 0;
        $left     = !empty($row->left)     ? $row->left : 0;

        if(!isset($this->result[$row->execution])) $this->result[$row->execution] = array('consumed' => 0, 'left' => 0);
        $this->result[$row->execution]['consumed'] += $consumed;
        $this->result[$row->execution]['left']     += $left;
    }

    public function getResult($options = array())
    {
        $records = array();
        foreach($this->result as $execution => $taskInfo)
        {
            $total = $taskInfo['consumed'] + $taskInfo['left'];
            $progress = $total ? round($taskInfo['consumed'] / $total, 4) : 0;
            $records[] = array('execution' => $execution, 'value' => $progress);
        }
        return $this->filterByOptions($records, $options);
    }
}
