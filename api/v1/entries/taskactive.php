<?php
/**
 * The task active entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 **/
class taskActiveEntry extends entry
{
    /**
     * POST method.
     *
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function post($taskID)
    {
        $control = $this->loadController('task', 'activate');

        $assignedTo = $this->request('assignedTo');
        $comment    = $this->request('comment');

        if(is_array($assignedTo))
        {
            $task         = $this->loadModel('task')->getByID($taskID);
            $teamEstimate = $this->request('teamEstimate', array());
            $oldTeam      = $this->dao->select('*')->from(TABLE_TASKTEAM)->where('task')->eq($taskID)->orderBy('order')->fetchAll('account');

            /* 构建旧团队 */
            $teamSource  = array();
            foreach($oldTeam as $member)
            {
                $teamSource[]  = $member->account;
            }

            /* 根据新传入的数据重新设置团队 */
            $team         = array();
            $teamConsumed = array();
            $teamLeft     = array();

            foreach($assignedTo as $index => $account)
            {
                $team[]         = $account;
                $teamConsumed[] = $oldTeam[$account]->consumed ?? 0;
                $teamLeft[]     = $oldTeam[$account]->left ?? $teamEstimate[$index] ?? 0;
            }

            /* 根据预计剩余工时计算团队剩余工时 */
            $left = 0;
            foreach($teamEstimate as $estimate)
            {
                $left += $estimate;
            }

            $this->setPost('team', $team);
            $this->setPost('teamConsumed', $teamConsumed);
            $this->setPost('teamSource', $teamSource);
            $this->setPost('teamEstimate', $teamEstimate);
            $this->setPost('teamLeft', $teamLeft);
            $this->setPost('left', $left);
            $this->setPost('mode', $task->mode);
            $this->setPost('multiple', 'on');
        }
        else
        {
            $left = $this->request('left');

            $this->setPost('assignedTo', $assignedTo);
            $this->setPost('left', $left);
        }
        $this->setPost('comment', $comment);

        $control->activate($taskID);

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $task = $this->loadModel('task')->getByID($taskID);

        return $this->send(200, $this->format($task, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList'));
    }
}

