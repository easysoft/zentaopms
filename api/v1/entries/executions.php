<?php
/**
 * 禅道API的executions资源类
 * 版本V1
 *
 * The executions entry point of zentaopms
 * Version 1
 */
class executionsEntry extends entry
{
    public function get($projectID = 0)
    {
        $control = $this->loadController('execution', 'all');
        $control->all($this->param('status', 'all'), $this->param('project', $projectID));
        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success') return $this->send(200, $data->data->executionStats);
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        return $this->sendError(400, 'error');
    }

    public function post($projectID = 0)
    {
        $fields = 'project,code,name,begin,end,lifetime,desc,days';
        $this->batchSetPost($fields);

        $projectID = $this->param('project', $projectID);
        $this->setPost('project', $projectID);
        $this->setPost('acl', $this->request('acl', 'private'));
        $this->setPost('whitelist', $this->request('whitelist', array()));
        $this->setPost('products', $this->request('products', array()));
        $this->setPost('plans', $this->request('plans', array()));

        $control = $this->loadController('execution', 'create');
        $this->requireFields('name,code,begin,end,days');

        $control->create($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);

        $execution = $this->loadModel('execution')->getByID($data->id);

        $this->send(200, $execution);
    }
}
