<?php
/**
 * 禅道API的projects资源类
 * 版本V1
 *
 * The project entry point of zentaopms
 * Version 1
 */
class projectEntry extends entry
{
    public function get($projectID)
    {
        $control = $this->loadController('project', 'view');
        $control->view($projectID);

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'success') return $this->send(200, $data->data->project);
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $this->sendError(400, 'error');
    }

    public function put($projectID)
    {
        $oldProject     = $this->loadModel('project')->getByID($projectID);
        $linkedProducts = $this->project->getProducts($projectID);

        /* Set $_POST variables. */
        $fields = 'name,begin,end,acl,parent,desc,PM,whitelist';
        $this->batchSetPost($fields, $oldProject);

        $products = array();
        $plans    = array();
        foreach($linkedProducts as $product)
        {
            $products[] =  $product->id;
            $plans[]    =  $product->plan;
        }
        $this->setPost('products', $products);
        $this->setPost('plans', $plans);

        $control = $this->loadController('project', 'edit');
        $control->edit($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $project = $this->project->getByID($projectID);
        $this->send(200, $project);
    }

    public function delete($projectID)
    {
        $control = $this->loadController('project', 'delete');
        $control->delete($projectID, 'yes');

        $this->getData();
        $this->sendSuccess(200, 'success');
    }
}
