<?php
/**
 * 按人员统计的待处理用例数。
 * Count of assigned case in user.
 *
 * 范围：user
 * 对象：case
 * 目的：scale
 * 度量名称：按人员统计的待处理用例数
 * 单位：个
 * 描述：按人员统计的待处理用例数表示每个人待处理的用例数量之和。反映了每个人需要处理的用例数量上的规模。该数值越大，说明需要投入越多的时间处理用例。
 * 定义：所有用例个数求和;指派给为某人;过滤已删除的用例;过滤已删除产品的用例;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_assigned_case_in_user extends baseCalc
{
    public $result = array();

    public function getStatement()
    {
        return $this->dao->select('t1.assignedTo')->from(TABLE_TESTRUN)->alias('t1')
            ->leftJoin(TABLE_CASE)->alias('t2')->on('t1.case = t2.id')
            ->leftJoin(TABLE_TESTTASK)->alias('t3')->on('t1.task = t3.id')
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t2.deleted')->eq(0)
            ->andWhere('t3.status')->ne('done')
            ->query();
    }

    public function calculate($row)
    {
        $assignedTo = $row->assignedTo;

        if(empty($assignedTo) || $assignedTo == 'closed') return false;

        if(!isset($this->result[$assignedTo])) $this->result[$assignedTo] = 0;
        $this->result[$assignedTo] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
