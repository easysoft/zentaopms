<?php
/**
 * 按系统统计的月度关闭执行数。
 * Count of monthly closed execution.
 *
 * 范围：system
 * 对象：execution
 * 目的：scale
 * 度量名称：按系统统计的月度关闭执行数
 * 单位：个
 * 描述：按系统统计的月度完成执行数是指在某月度已经关闭的执行数。该度量项反映了团队或组织在某月内的工作效率和完成能力。较高的月度完成执行数表示团队或组织在快速完成任务方面表现出较高的效率，反之则可能需要审查工作流程和资源分配情况，以提高执行效率。
 * 定义：所有的执行个数求和;关闭时间为某年某月;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_closed_execution extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.closedDate');

    public $result = array();

    public function calculate($row)
    {
        $closedDate = $row->closedDate;
        if(empty($closedDate)) return false;

        $year = substr($closedDate, 0, 4);
        if($year == '0000') return false;
        $month = substr($closedDate, 5, 2);

        if(!isset($this->result[$year])) $this->result[$year] = array();
        if(!isset($this->result[$year][$month])) $this->result[$year][$month] = 0;
        $this->result[$year][$month] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'month', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
