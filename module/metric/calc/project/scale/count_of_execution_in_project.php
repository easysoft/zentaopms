<?php
/**
 * 按项目统计的执行总数。
 * Count of execution in project.
 *
 * 范围：project
 * 对象：execution
 * 目的：scale
 * 度量名称：按项目统计的执行总数
 * 单位：个
 * 描述：按项目统计的执行总数表示在项目中所有执行的数量，可以用来评估项目的规模、项目执行进度、工作负荷、绩效评估、风险控制和项目管理的有用信息。
 * 定义：项目的执行个数求和;过滤已删除的执行;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_execution_in_project extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.project');

    public $result = array();

    public function calculate($row)
    {
        $project = $row->project;
        if(!isset($this->result[$project])) $this->result[$project] = 0;
        $this->result[$project] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
