<?php
/**
 * 按系统统计的月度新增执行数。
 * Count of monthly created execution.
 *
 * 范围：system
 * 对象：execution
 * 目的：scale
 * 度量名称：按系统统计的月度新增执行数
 * 单位：个
 * 描述：按系统统计的月度新增执行数是指在某月度内新添加的执行数。该度量项反映了团队或组织在某月内所面临的新任务或工作量。较高的月度新增执行数可能表明团队需要快速适应新任务和及时调整资源来满足需求。
 * 定义：所有的执行个数求和;创建时间为某年某月;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_monthly_created_execution extends baseCalc
{
    public $dataset = 'getExecutions';

    public $fieldList = array('t1.openedDate');

    public $result = array();

    public function calculate($row)
    {
        $openedDate = $row->openedDate;
        if(empty($openedDate)) return false;

        $year = substr($openedDate, 0, 4);
        if($year == '0000') return false;
        $month = substr($openedDate, 5, 2);

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
