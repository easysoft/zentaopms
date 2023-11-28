<?php
/**
 * 按项目统计的未开始任务数。
 * Count of wait task in project.
 *
 * 范围：project
 * 对象：task
 * 目的：scale
 * 度量名称：按项目统计的未开始任务数
 * 单位：个
 * 描述：按项目统计的未开始任务数指的是在项目执行过程中未开始进行的任务数量。这个度量项帮助团队了解项目进展的一部分，即有多少任务未启动。通过统计未开始任务数，团队可以评估项目的准备状况、资源分配以及可能存在的延迟因素。
 * 定义：项目中任务个数求和;状态为未开始;过滤已删除的任务;过滤已删除执行的任务;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_wait_task_in_project extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.status', 't1.project');

    public $result = array();

    public function calculate($row)
    {
        if($row->status != 'wait') return false;
        if(!isset($this->result[$row->project])) $this->result[$row->project] = 0;
        $this->result[$row->project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
