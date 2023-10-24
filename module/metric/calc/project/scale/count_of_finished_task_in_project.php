<?php
/**
 * 按项目统计的已完成任务数。
 * Count of finished task in project.
 *
 * 范围：project
 * 对象：task
 * 目的：scale
 * 度量名称：按项目统计的已完成任务数
 * 单位：个
 * 描述：按项目统计的已完成任务数是指项目已经完成的任务总量。该度量项可以衡量任务完成的进度和效率，以及项目的工作质量和产出。较高的已完成任务总数可能表明项目在交付工作方面表现出较好的能力。
 * 定义：项目中任务个数求和;状态为已完成;过滤已删除的任务;过滤已删除执行的任务;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_finished_task_in_project extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.status', 't1.project');

    public $result = array();

    public function calculate($row)
    {
        if($row->status != 'done') return false;
        if(!isset($this->result[$row->project])) $this->result[$row->project] = 0;
        $this->result[$row->project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
