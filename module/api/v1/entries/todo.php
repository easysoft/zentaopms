<?php
/**
 * The todo entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class todoEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $todoID
     * @access public
     * @return string
     */
    public function get($todoID)
    {
        $control = $this->loadController('todo', 'view');
        $control->view($todoID, $this->param('from', 'my'));

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $todo = $data->data->todo;
        return $this->send(200, $this->format($todo, 'assignedDate:time,finishedDate:time,closedDate:time'));
    }

    /**
     * PUT method.
     *
     * @param  int    $todoID
     * @access public
     * @return string
     */
    public function put($todoID)
    {
        $oldTodo = $this->loadModel('todo')->getByID($todoID);

        /* Set $_POST variables. */
        $fields = 'date,type,name,pri,desc,status,begin,end,private';
        $this->batchSetPost($fields, $oldTodo);

        $this->setPost('objectID', 0);
        $this->setPost('date', $this->request('date', date("Y-m-d", strtotime($oldTodo->date))));
        $this->setPost('begin', $this->request('begin') ? str_replace(':', '', $this->request('begin')) : $oldTodo->begin);
        $this->setPost('end', $this->request('end') ? str_replace(':', '', $this->request('end')) : $oldTodo->end);

        $control = $this->loadController('todo', 'edit');
        $control->edit($todoID);

        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $todo = $this->todo->getByID($todoID);
        return $this->send(200, $this->format($todo, 'assignedDate:time,finishedDate:time,closedDate:time'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $todoID
     * @access public
     * @return string
     */
    public function delete($todoID)
    {
        $control = $this->loadController('todo', 'delete');
        $control->delete($todoID, 'yes');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
