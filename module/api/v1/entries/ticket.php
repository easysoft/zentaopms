<?php
/**
 * The ticket entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class ticketEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $ticketID
     * @access public
     * @return string
     */
    public function get($ticketID)
    {
        $control = $this->loadController('ticket', 'view');
        $control->view($ticketID);

        $data = $this->getData();

        $ticket = $data->data->ticket;

        $ticket->productName  = $data->data->product;
        $ticket->moduleName   = isset($data->data->modulePath[0]->name) ? $data->data->modulePath[0]->name : '/';

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $ticket->actions = $this->loadModel('action')->processActionForAPI($data->data->actions, $data->data->users, $this->lang->ticket);
        return $this->send(200, $this->format($ticket, 'activatedDate:time,openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,mailto:userList,resolvedBy:user,resolvedDate:time,closedBy:user,closedDate:time,lastEditedBy:user,lastEditedDate:time,deadline:date,deleted:bool'));
    }

    /**
     * PUT method.
     *
     * @param  int    $ticketID
     * @access public
     * @return string
     */
    public function put($ticketID)
    {
        $oldTicket = $this->loadModel('ticket')->getById($ticketID);

        $fields = 'module,product,type,openedBuild,assignedTo,deadline,title,desc,status,notify,uid';
        $this->batchSetPost($fields, $oldTicket);

        $control = $this->loadController('ticket', 'edit');
        $control->edit($ticketID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $ticket = $this->ticket->getByID($ticketID);

        return $this->send(200, $this->format($ticket, 'openedBy:user,openedDate:time,activatedBy:user,activatedDate:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,editedBy:user,editedDate:time,deadline:time,assignedTo:user,mailto:userList,deleted:bool'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $ticketID
     * @access public
     * @return string
     */
    public function delete($ticketID)
    {
        $control = $this->loadController('ticket', 'delete');
        $control->delete($ticketID, 'yes');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}

