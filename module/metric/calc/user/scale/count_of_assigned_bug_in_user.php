<?php
/**
 * 按人员统计的待处理Bug数。
 * Count of assigned bug in user.
 *
 * 范围：user
 * 对象：bug
 * 目的：scale
 * 度量名称：按人员统计的待处理Bug数
 * 单位：个
 * 描述：按人员统计的待处理Bug数表示每个人待处理的Bug数量之和。反映了每个人需要处理的Bug数量上的规模。该数值越大，说明需要投入越多的时间解决Bug。
 * 定义：所有Bug个数求和;指派给为某人;过滤已删除的Bug;过滤已删除产品的Bug;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    zhouxin <zhouxin@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_assigned_bug_in_user extends baseCalc
{
    public $dataset = 'getProjectBugs';

    public $fieldList = array('t1.assignedTo');

    public $result = array();

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
