<?php
/**
 * 按人员统计的待处理任务数。
 * Count of assigned task in user.
 *
 * 范围：user
 * 对象：task
 * 目的：scale
 * 度量名称：按人员统计的待处理任务数
 * 单位：个
 * 描述：按人员统计的待处理任务数表示每个人待处理的任务数量之和。反映了每个人在需要处理的任务数量上的规模。该数值越大，说明需要投入越多的时间处理任务。
 * 定义：所有任务个数求和;指派给为某人;过滤已删除的任务;过滤已删除项目的任务;过滤已删除执行的任务;
 *
 * @copyright Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @author    qixinzhi <qixinzhi@easycorp.ltd>
 * @package
 * @uses      func
 * @license   ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @Link      https://www.zentao.net
 */
class count_of_assigned_task_in_user extends baseCalc
{
    public $dataset = 'getTasks';

    public $fieldList = array('t1.assignedTo', 't1.status');

    public $result = array();

    public function calculate($row)
    {
        $assignedTo = $row->assignedTo;
        $status     = $row->status;

        if(empty($assignedTo) || $assignedTo == 'closed') return false;
        if($status == 'closed' || $status == 'cancel') return false;

        if(!isset($this->result[$assignedTo])) $this->result[$assignedTo] = 0;
        $this->result[$assignedTo] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
