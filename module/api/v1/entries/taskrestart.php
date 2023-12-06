<?php
/**
 * The task start entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Lu Fei <lufei@easycorp.ltd>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class taskRestartEntry extends entry
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
        $task = $this->loadModel('task')->getByID($taskID);

        $fields = 'assignedTo,realStarted';
        $this->batchSetPost($fields, $task);

        $fields = 'consumed,left,comment';
        $this->batchSetPost($fields);

        $control = $this->loadController('task', 'restart');
        $this->requireFields('consumed,left');
        $control->restart($taskID);

        $data = $this->getData();
        if(!$data) return $this->send400('error');
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $task = $this->loadModel('task')->getByID($taskID);

        return $this->send(200, $task);
    }
}
