<?php
/**
 * 按项目统计的年度关闭执行数。
 * Count annual closed execution in project.
 *
 * 范围：project
 * 对象：execution
 * 目的：scale
 * 度量名称：按项目统计的年度关闭执行数
 * 单位：个
 * 描述：按项目统计的年度关闭执行数是指在项目中某年度已经关闭的执行数。该度量项反映了项目团队在某年度的工作效率和完成能力。较高的年度关闭执行数表示项目在完成任务方面表现出较高的效率，反之则可能需要审查工作流程和资源分配情况，以提高执行效率。
 * 定义：项目的执行个数求和;关闭时间为某年;过滤已删除的执行;过滤已删除的项目;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_annual_closed_execution_in_project extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.project', 't1.closedDate');

    public $result = array();

    public function calculate($row)
    {
        $project    = $row->project;
        $closedDate = $row->closedDate;

        if(empty($closedDate)) return false;
        $year = substr($closedDate, 0, 4);
        if($year == '0000') return false;

        if(!isset($this->result[$project])) $this->result[$project] = array();
        if(!isset($this->result[$project][$year])) $this->result[$project][$year] = 0;

        $this->result[$project][$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('project', 'year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
