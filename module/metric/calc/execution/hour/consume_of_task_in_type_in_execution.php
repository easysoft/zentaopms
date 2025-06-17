<?php
/**
 * 按类型统计的关联需求的任务消耗工时数。
 * Consume of task in type in execution.
 *
 * 范围：execution
 * 对象：task
 * 目的：hour
 * 度量名称：按类型统计的关联需求的任务消耗工时数
 * 单位：小时
 * 描述：按类型统计的关联需求的任务消耗工时数是指已经花费的工时总和，用于完成所有任务。该度量项反映了任务的实际完成情况和资源使用情况，可以帮助团队掌握任务的进展情况和资源利用效率。
 * 定义：执行中任务的消耗工时数求和;过滤已删除的任务;过滤父任务;过滤未关联需求的任务;过滤已删除的执行;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    Zemei Wang <wangzemei@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class consume_of_task_in_type_in_execution extends baseCalc
{
    public $dataset = '';

    public $fieldList = array();

    public $result = array();

    public function calculate($row)
    {
        if($row->isParent == '1') return;
        if(empty($row->story))    return;

        if(!isset($this->result[$row->type])) $this->result[$row->type] = array();
        $this->result[$row->type][$row->id] = $row->consumed;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $type => $tasks)
        {
            if(!is_array($tasks))
            {
                unset($this->result[$type]);
                continue;
            }
            $this->result[$type] = array_sum($tasks);
        }

        $records = $this->getRecords(array('type', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
