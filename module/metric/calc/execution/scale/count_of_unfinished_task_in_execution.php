<?php
/**
 * 按执行统计的未完成任务数。
 * Count of unfinished task in execution.
 *
 * 范围：execution
 * 对象：task
 * 目的：scale
 * 度量名称：按执行统计的未完成任务数
 * 单位：个
 * 描述：按执行统计的未完成任务数是指执行未完成的任务总量。该度量项反映了团队的待办工作量和未来的工作压力。较低的未完成任务总数可能表明项目在交付工作方面表现出较好的能力。
 * 定义：复用：;按执行统计的未完成任务数;按执行统计的任务总数;公式：;按执行统计的未完成任务数=按执行统计的任务总数-按执行统计的已完成任务数;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_unfinished_task_in_execution extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.status', 't1.execution');

    public $result = array();

    public function calculate($row)
    {
        if($row->status != 'done')
        {
            if(!isset($this->result[$row->execution])) $this->result[$row->execution] = 0;
            $this->result[$row->execution] ++;
        }
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('execution', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
