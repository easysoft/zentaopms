<?php
/**
 * 按执行统计的任务总数。
 * Count of task in execution.
 *
 * 范围：execution
 * 对象：task
 * 目的：scale
 * 度量名称：按执行统计的任务总数
 * 单位：个
 * 描述：按执行统计的任务总数是指整个执行当前存在的任务总量。该度量项可以用来跟踪任务的规模和复杂性，为资源分配和工作计划提供基础，可以帮助团队评估工作负荷和任务分配的合理性。
 * 定义：执行中所有的任务个数求和;过滤已删除的任务;过滤已删除的执行;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_task_in_execution extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.execution');

    public $result = array();

    public function calculate($row)
    {
        if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
        $this->result[$row->execution] ++;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
