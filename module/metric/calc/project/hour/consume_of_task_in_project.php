<?php
/**
 * 按项目统计的任务消耗工时数。
 * Consume of task in project.
 *
 * 范围：project
 * 对象：task
 * 目的：hour
 * 度量名称：按项目统计的任务消耗工时数
 * 单位：小时
 * 描述：按项目统计的任务消耗工时数是指已经花费的工时总和，用于完成所有任务。该度量项可以用来评估项目在任务执行过程中的工时投入情况，以及在完成任务方面的效率和资源利用情况。较高的任务消耗工时总数可能表明需要审查工作流程和资源分配，以提高工作效率。
 * 定义：项目中任务的消耗工时数求和;过滤已删除的任务;过滤父任务;过滤已删除执行的任务;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouixn <zhouixn@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class consume_of_task_in_project extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.consumed', 't1.project', 't1.parent');

    public $result = array();

    public function calculate($row)
    {
        $parent = $row->parent;
        if($parent == '-1') return;

        if(!isset($this->result[$row->project])) $this->result[$row->project] = 0;
        $this->result[$row->project] += $row->consumed;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
