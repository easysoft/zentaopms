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
 * 定义：按人员统计的待处理的用例个数;所有测试单中的用例个数求和（不去重）;指派给某人;过滤已删除的用例;过滤已删除的测试单中的用例;过滤已关闭的测试单中的用例;
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
    public $dataset = 'getTestRuns';

    public $fieldList = array('t1.assignedTo', 't3.status');

    public $result = array();

    public function calculate($row)
    {
        $assignedTo = $row->assignedTo;

        if($row->status == 'done') return false;
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
