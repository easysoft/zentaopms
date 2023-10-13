<?php
/**
 * 按项目统计的任务预计工时数。
 * Estimate of task in project.
 *
 * 范围：project
 * 对象：task
 * 目的：hour
 * 度量名称：按项目统计的任务预计工时数
 * 单位：小时
 * 描述：按项目统计的任务预计工时数是指在项目管理中，对所有任务的预计工时进行统计和汇总的度量。这个度量项用于评估项目的工作量和资源需求，并帮助规划和安排项目团队。任务预计工时数是通过对每个任务的工作量估算进行累加而得，可以作为项目计划和进度控制的依据。
 * 定义：项目中任务的预计工时数求和;过滤已删除的任务;过滤已取消的任务;过滤父任务;过滤已删除执行的任务;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class estimate_of_task_in_project extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.estimate', 't1.project', 't1.parent', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $parent = $row->parent;
        $status = $row->status;

        if($parent == '-1' || $status == 'cancel') return false;

        if(!isset($this->result[$row->project])) $this->result[$row->project] = 0;
        $this->result[$row->project] += $row->estimate;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
