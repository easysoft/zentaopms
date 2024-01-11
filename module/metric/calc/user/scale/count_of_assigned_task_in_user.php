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
 * 定义：任务个数求和;指派给为某人，并且过滤任务状态为已关闭和已取消的任务;如果任务为多人任务，并且过滤状态为已完成的任务;过滤已删除的任务;过滤已删除项目的任务;过滤已删除执行的任务;过滤挂起的执行和项目;
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

    public $fieldList = array('t1.assignedTo', 't1.status', 't1.mode', 't4.account', 't3.status as projectStatus', 't2.status as executionStatus');

    public $result = array();

    public function calculate($row)
    {
        $assignedTo = $row->assignedTo;
        $status     = $row->status;
        $mode       = $row->mode;

        $projectStatus   = $row->projectStatus;
        $executionStatus = $row->executionStatus;

        if(empty($assignedTo) || $assignedTo == 'closed') return false;
        if($status == 'closed' || $status == 'cancel') return false;
        if($projectStatus == 'suspended' || $projectStatus == 'closed' || $executionStatus == 'suspended' || $executionStatus == 'closed') return false;

        if($mode == 'multi')
        {
            if($status == 'done') return false;
            $assignedTo = $row->account;
        }


        if(!isset($this->result[$assignedTo])) $this->result[$assignedTo] = 0;
        $this->result[$assignedTo] += 1;
    }

    public function getResult($options = array())
    {
        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
