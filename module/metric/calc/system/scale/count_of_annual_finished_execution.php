<?php
/**
 * 按系统统计的年度完成执行数。
 * Count of annual finished execution.
 *
 * 范围：system
 * 对象：execution
 * 目的：scale
 * 度量名称：按系统统计的年度完成执行数
 * 单位：个
 * 描述：按系统统计的年度完成执行数是指在某年度已经完成的执行数。该度量项反映了团队或组织在某年的工作效率和完成能力。较高的年度完成执行数表示团队或组织在完成任务方面表现出较高的效率，反之则可能需要审查工作流程和资源分配情况，以提高执行效率。
 * 定义：所有的执行个数求和;实际完成日期为某年;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_finished_execution extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.realEnd');

    public $result = array();

    public function calculate($row)
    {
        $year = $this->getYear($row->realEnd);
        if(!$year) return false;

        if(!isset($this->result[$year])) $this->result[$year] = 0;
        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
