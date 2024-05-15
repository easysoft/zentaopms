<?php
/**
 * 按人员统计的待处理用户需求数
 * Count of assignedTo requirement in user.
 *
 * 范围：user
 * 对象：requirement
 * 目的：scale
 * 度量名称：按人员统计的待处理用户需求数
 * 单位：个
 * 描述：按人员统计的待处理用户需求数表示每个人待处理的用户需求数量之和。反映了每个人员需要处理的用户需求数量的规模。该数值越大，说明需要投入越多的时间处理用户需求。
 * 定义：所有用户需求个数求和 指派给为某人 过滤已删除的用户需求 过滤已删除产品的用户需求
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_assigned_requirement_in_user extends baseCalc
{
    public $dataset = 'getAllRequirements';

    public $fieldList = array('assignedTo');

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
