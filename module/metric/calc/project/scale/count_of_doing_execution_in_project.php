<?php
/**
 * 按项目统计的进行中执行数。
 * Count of doing execution in project.
 *
 * 范围：project
 * 对象：execution
 * 目的：scale
 * 度量名称：按项目统计的进行中执行数
 * 单位：个
 * 描述：按项目统计的进行中执行数表示在项目中正在进行中的执行项的数量，可以用来了解当前正在进行的任务数量，反映项目团队的工作进展。
 * 定义：所有的执行个数求和;状态为进行中;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_doing_execution_in_project extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.project', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $status  = $row->status;

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        if($status == 'doing') $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
