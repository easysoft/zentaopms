<?php
/**
 * 按项目统计的已关闭执行数。
 * Count of closed execution in project.
 *
 * 范围：project
 * 对象：execution
 * 目的：scale
 * 度量名称：按项目统计的已关闭执行数
 * 单位：个
 * 描述：按项目统计的已关闭执行数表示在项目中已关闭的执行项的数量，可以用来了解已关闭的执行数量。
 * 定义：项目的执行个数求和;过滤已删除的执行;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_closed_execution_in_project extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.project', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        $status  = $row->status;

        if(!isset($this->result[$project])) $this->result[$project] = 0;
        if($status == 'closed') $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
