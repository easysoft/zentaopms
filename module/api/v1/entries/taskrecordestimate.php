<?php
/**
 * The task recordWorkhour entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class taskRecordEstimateEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function get($taskID)
    {
        $issetEffort = $this->loadModel('effort') ? true : false;
        if($issetEffort)
        {
            $control = $this->loadController('effort', 'createForObject');
            $control->createForObject('task', $taskID);
        }
        else
        {
            $control = $this->loadController('task', 'recordWorkhour');
            $control->recordWorkhour($taskID);
        }

        $data = $this->getData();
        if(!$data) return $this->error('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $effort = $data->data->efforts ? $data->data->efforts : new stdclass();
        return $this->send(200, array('effort' => $effort));

    }

    /**
     * POST method.
     *
     * @param  int    $taskID
     * @access public
     * @return string
     */
    public function post($taskID)
    {
        if($this->loadModel('effort'))
        {
            $fields = 'id,dates,consumed,left,objectType,objectID,work';
            $this->batchSetPost($fields);
            $control = $this->loadController('effort', 'createForObject');
            $control->createForObject('task', $taskID);
        }
        else
        {
            $fields = 'id,dates,consumed,left,work';
            $this->batchSetPost($fields);
            $control = $this->loadController('task', 'recordWorkhour');
            $control->recordWorkhour($taskID);
        }

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $task = $this->loadModel('task')->getById($taskID);

        return $this->send(200, $this->format($task, 'deadline:date,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,realStarted:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool,mailto:userList'));
    }
}
