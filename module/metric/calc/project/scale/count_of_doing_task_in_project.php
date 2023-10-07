<?php
/**
 * 按项目统计的进行中任务数。
 * Count of doing task in project.
 *
 * 范围：project
 * 对象：task
 * 目的：scale
 * 度量名称：按项目统计的进行中任务数
 * 单位：个
 * 描述：按项目统计的进行中任务数表示项目执行过程中正在进行的任务数量。这个度量项帮助团队了解项目当前的工作负载和进展情况。统计进行中任务数可以帮助团队判断项目的工作量是否合理分配，并进行进一步的资源规划和调整。
 * 定义：项目中任务个数求和;状态为进行中;过滤已删除的任务;过滤已删除执行的任务;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouixn <zhouixn@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_doing_task_in_project extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.status', 't1.project');

    public $result = array();

    public function calculate($row)
    {
        if($row->status != 'doing') return false;
        if(!isset($this->result[$row->project])) $this->result[$row->project] = 0;
        $this->result[$row->project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
