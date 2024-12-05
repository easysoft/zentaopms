<?php
/**
 * 按执行统计的开发任务消耗工时数
 * Consume of devel task in execution.
 *
 * 范围：execution
 * 对象：task
 * 目的：hour
 * 度量名称：按执行统计的开发任务消耗工时数
 * 单位：小时
 * 描述：按执行统计的开发任务消耗工时数是指任务类型为开发时已经消耗的工时总和。该度量项反映了开发任务的资源使用情况，可以帮助团队掌握执行的开发成本。
 * 定义：执行中满足以下条件的任务消耗工时数求和，任务类型为开发，过滤已删除的任务，过滤父任务，过滤已删除的执行，过滤已删除的项目。
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@chandao.com>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class consume_of_devel_task_in_execution extends baseCalc
{
    public $result = array();

    public $dataset = 'getTasks';

    public $fieldList = array('t1.execution', 't1.consumed', 't1.parent', 't1.type', 't1.isParent');

    public function calculate($row)
    {
        if($row->isParent == '1' || $row->type != 'devel') return;
        if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
        $this->result[$row->execution] += $row->consumed;
    }

    public function getResult($options = array())
    {
        return $this->filterByOptions($this->getRecords(array('execution', 'value')), $options);
    }
}
