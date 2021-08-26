<?php
/**
 * 禅道API的issue资源类
 * 版本V1
 *
 * The issue entry point of zentaopms
 * Version 1
 */
class issueEntry extends Entry
{
    public function get($issueID)
    {
        /* If $issueID has '-', go to productIssue entry point for Gitlab. */
        if(strpos($issueID, '-') !== FALSE) return $this->fetch('productIssue', 'get', array('issueID' => $issueID));

        $control = $this->loadController('issue', 'view');
        $control->view($issueID);

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->status) and $data->status == 'success') return $this->send(200, $this->format($data->data->issue, 'createdDate:time,editedDate:time,assignedDate:time'));
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $this->sendError(400, 'error');
    }

    public function put($issueID)
    {
        $oldIssue = $this->loadModel('issue')->getByID($issueID);

        /* Set $_POST variables. */
        $fields = 'type,title,severity,pri,assignedTo,deadline,desc';
        $this->batchSetPost($fields, $oldIssue);

        $control = $this->loadController('issue', 'edit');
        $control->edit($issueID);

        $data = $this->getData();
        if(!$data or (isset($data->message) and $data->message == '404 Not found')) return $this->send404();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $issue = $this->issue->getByID($issueID);
        $this->send(200, $this->format($issue, 'createdDate:time,editedDate:time,assignedDate:time'));
    }

    public function delete($issueID)
    {
        $control = $this->loadController('issue', 'delete');
        $control->delete($issueID, 'true');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
