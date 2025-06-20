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
 * 定义：所有任务个数求和;指派给为某人;过滤已关闭的任务;过滤已取消的任务;过滤已删除的任务;过滤已删除项目的任务;过滤已删除执行的任务;过滤多人任务中某人任务状态为已完成的任务;过滤任务关联的执行和项目都为挂起状态时的任务
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
    public $dataset = 'getTasksWithTeam';

    public $fieldList = array('t1.id', "case when t1.mode != '' and t4.status != 'done' then t4.account when t1.mode != '' and t4.status = 'done' then '' else t1.assignedTo end as assignedTo", 't1.status', 't1.mode', 't4.account', 't3.status as projectStatus', 't2.status as executionStatus', 't4.status as teamStatus');

    public $result = array();

    public $supportSingleQuery = true;

    public function singleQuery()
    {
        $select = "`assignedTo` as `user`, count(`assignedTo`) as `value`";
        return $this->dao->select($select)->from($this->getSingleSql())
            ->where('`status`')->notin('closed,cancel')
            ->andWhere('`projectStatus`', true)->ne('suspended')
            ->orWhere('`executionStatus`')->ne('suspended')->markRight(1)
            ->andWhere("(`mode` = 'multi' and `teamStatus` != 'done')", true)
            ->orWhere('`mode`')->ne('multi')
            ->markRight(1)
            ->groupBy('`assignedTo`')
            ->fetchAll();
    }

    public function calculate($row)
    {
        $assignedTo = $row->assignedTo;
        $status     = $row->status;
        $mode       = $row->mode;

        $projectStatus   = $row->projectStatus;
        $executionStatus = $row->executionStatus;
        $teamStatus      = $row->teamStatus;

        if($status == 'closed' || $status == 'cancel') return false;

        if($mode == 'multi')
        {
            if($teamStatus == 'done') return false;
            $assignedTo = $row->account;
        }

        /* 如果执行和项目都是挂起的，不计算。*/
        if($projectStatus == 'suspended' && $executionStatus == 'suspended') return false;

        if(!isset($this->result[$assignedTo])) $this->result[$assignedTo] = array();
        $this->result[$assignedTo][$row->id] = $row->id;
    }

    public function getResult($options = array())
    {
        foreach($this->result as $assignedTo => $tasks)
        {
            if(!is_array($tasks)) continue;

            $this->result[$assignedTo] = count($tasks);
        }

        $records = $this->getRecords(array('user', 'value'));
        return $this->filterByOptions($records, $options);
    }
}
