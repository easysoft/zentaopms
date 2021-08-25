<?php
/**
 * 禅道API的risks资源类
 * 版本V1
 *
 * The risks entry point of zentaopms
 * Version 1
 */
class risksEntry extends entry
{
    public function get()
    {
        $control = $this->loadController('my', 'risk');
        $control->risk($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $pager  = $data->data->pager;
        $result = array();
        foreach($data->data->risks as $risk)
        {
            $result[] = $this->format($risk, 'createdDate:time,editedDate:time');
        }

        return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'risks' => $result));
    }

    public function post($projectID = 0)
    {
        if((int)$projectID <= 0) return $this->sendError(400, 'The id of project is wrong.');

        $fields = 'source,name,category,strategy,status,impact,probability,rate,identifiedDate,plannedClosedDate,actualClosedDate,resolvedBy,assignedTo,prevention,remedy,resolution';
        $this->batchSetPost($fields);

        $this->setPost('impact', $this->request('impact', 3));
        $this->setPost('probability', $this->request('probability', 3));
        $this->setPost('rate', $this->request('rate', 9));
        $this->setPost('pri', 'middle');

        $control = $this->loadController('risk', 'create');
        $this->requireFields('name');

        $control->create($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $risk = $this->loadModel('risk')->getByID($data->id);

        $this->send(201, $this->format($risk, 'createdDate:time,editedDate:time'));
    }
}
