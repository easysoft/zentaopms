<?php
/**
 * 按执行统计的任务剩余工时数。
 * Left of task in execution.
 *
 * 范围：execution
 * 对象：task
 * 目的：hour
 * 度量名称：按执行统计的任务剩余工时数
 * 单位：小时
 * 描述：按执行统计的任务剩余工时数是指当前未消耗的工时总和，用于完成所有任务。该度量项反映了任务完成的剩余工作量，可以帮助团队预测任务的完成时间和资源需求。
 * 定义：执行中任务的剩余工时数求和;过滤已删除的任务;过滤已取消的任务;过滤父任务;过滤已删除的执行;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class left_of_task_in_execution extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.execution', 't1.left', 't1.parent', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $parent = $row->parent;
        $status = $row->status;

        if($parent == '-1' || $status == 'cancel') return false;

        if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
        $this->result[$row->execution] += $row->left;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
