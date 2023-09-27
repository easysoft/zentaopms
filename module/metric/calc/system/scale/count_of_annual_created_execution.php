<?php
/**
 * 按系统统计的年度新增执行数。
 * Count of annual created execution.
 *
 * 范围：system
 * 对象：execution
 * 目的：scale
 * 度量名称：按系统统计的年度新增执行数
 * 单位：个
 * 描述：按系统统计的年度新增执行数是指在某年度新添加的执行数。该度量项反映了一个团队或组织在某年的工作量大小。较高的年度新增执行数可能表明团队面临更多的任务和挑战，需要更多的资源和努力来完成执行。同时，对于项目管理方面，该度量项也可以提供管理决策的依据。
 * 定义：所有的执行个数求和;创建时间为某年;过滤已删除的执行;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_annual_created_execution extends baseCalc
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

        if(!isset($this->result[$year])) $this->result[$year] = 0;
        $this->result[$year] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('year', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
