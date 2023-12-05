<?php
/**
 * The ticketsEntry entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class ticketsEntry extends entry
{
    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function get()
    {
        if(strpos(strtolower($this->param('fields')), 'moduleandproduct') !== false) return $this->getModuleAndProduct();

        $control = $this->loadController('ticket', 'browse');
        $control->browse($this->param('status', 'wait'), 0, $this->param('orderBy', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $tickets = $data->data->tickets;
        $pager   = $data->data->pager;

        $result = array();
        foreach($tickets as $ticket)
        {
            $result[] = $this->format($ticket, 'openedBy:user,openedDate:time,activatedBy:user,activatedDate:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,editedBy:user,editedDate:time,deadline:time,assignedTo:user,mailto:userList,deleted:bool');
        }

        $data = array();
        $data['page']    = $pager->pageID;
        $data['total']   = $pager->recTotal;
        $data['limit']   = $pager->recPerPage;
        $data['tickets'] = $result;

        return $this->send(200, $data);
    }

    /**
     * POST method.
     *
     * @access public
     * @return string
     */
    public function post()
    {
        $fields = 'module,product,type,openedBuild,assignedTo,deadline,title,desc,status,notify,uid,pri';
        $this->batchSetPost($fields);

        $control = $this->loadController('ticket', 'create');
        $this->requireFields('title,product,module');
        $control->create();

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $ticket = $this->loadModel('ticket')->getByID($data->id);

        return $this->send(201, $this->format($ticket, 'openedBy:user,openedDate:time,activatedBy:user,activatedDate:time,finishedBy:user,finishedDate:time,closedBy:user,closedDate:time,editedBy:user,editedDate:time,deadline:time,assignedTo:user,mailto:userList,deleted:bool'));
    }

    /**
     * GET method.
     *
     * @access public
     * @return string
     */
    public function getModuleAndProduct()
    {
        $control = $this->loadController('ticket', 'create');
        $control->create();

        $data = $this->getData();

        $modules  = $data->data->modules;
        $products = $data->data->products;

        $data = array();
        $data['modules']  = $modules;
        $data['products'] = $products;

        return $this->send(200, $data);
    }

}
