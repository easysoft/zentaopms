<?php
/**
 * The task component entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class taskComponentEntry extends entry
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
        $fields = 'name,color,type,assignedTo,parent,estimate,story,module,pri,desc,estStarted,deadline';
        $this->batchSetPost($fields);

        $fields = explode(',', $fields);
        foreach($fields as $field) $this->setArrayPost($field);
        $task = $this->loadModel('task')->getById($taskID);

        $control = $this->loadController('task', 'batchCreate');
        $control->batchCreate($task->execution, 0, 0, $taskID);

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $task = $this->task->getById($data->idList[0]);
        return $this->send(200, $this->format($task, 'deadline:date,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,realStarted:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,canceledBy:user,canceledDate:time,lastEditedBy:user,lastEditedDate:time,deleted:bool,mailto:userList'));
    }

    public function setArrayPost($field)
    {
        $_POST[$field] = array('0' => $_POST[$field]);
    }
}
