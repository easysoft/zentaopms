<?php
/**
 * 禅道API的issues资源类
 * 版本V1
 *
 * The issues entry point of zentaopms
 * Version 1
 */
class issuesEntry extends entry
{
    public function get()
    {
        $control = $this->loadController('my', 'issue');
        $control->issue($this->param('type', 'assignedTo'), $this->param('order', 'id_desc'), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $pager  = $data->data->pager;
        $result = array();
        foreach($data->data->issues as $issue)
        {
            $result[] = $this->format($issue, 'createdDate:time,editedDate:time,assignedDate:time');
        }

        return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'issues' => $result));
    }

    public function post($projectID = 0)
    {
        if((int) $projectID <= 0) $this->sendError(400, 'The id of project is wrong.');

        $fields = 'type,title,severity,pri,assignedTo,deadline,desc';
        $this->batchSetPost($fields);

        $control = $this->loadController('issue', 'create');
        $this->requireFields('type,title,severity');

        $control->create($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and!isset($data->id)) return $this->sendError(400, $data->message);

        $issue = $this->loadModel('issue')->getByID($data->id);

        $this->send(201, $this->format($issue, 'createdDate:time,editedDate:time,assignedDate:time'));
    }
}
