<?php
/**
 * 按项目统计的任务剩余工时数。
 * Left of task in project.
 *
 * 范围：project
 * 对象：task
 * 目的：hour
 * 度量名称：按项目统计的任务剩余工时数
 * 单位：小时
 * 描述：按项目统计的任务剩余工时数是指当前未消耗的工时总和，用于完成所有任务。该度量项可以用来评估项目在任务执行过程中剩余的工作量和时间，以及为完成任务所需的资源和计划。较小的任务剩余工时总数可能表示项目将及时完成任务，而较大的任务剩余工时总数可能需要重新评估进度和资源分配。
 * 定义：项目中任务的剩余工时数求和;过滤已删除的任务;过滤已取消的任务;过滤父任务;过滤已删除执行的任务;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class left_of_task_in_project extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.left', 't1.project', 't1.parent', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $parent = $row->parent;
        $status = $row->status;

        if($parent == '-1' || $status == 'cancel') return false;

        if(!isset($this->result[$row->project])) $this->result[$row->project] = 0;
        $this->result[$row->project] += $row->left;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
