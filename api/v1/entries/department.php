<?php
/**
 * 禅道API的department资源类
 * 版本V1
 *
 * The department entry point of zentaopms
 * Version 1
 */
class departmentEntry extends Entry
{
    public function get($departmentID)
    {
        $dept = $this->loadModel('dept')->getByID($departmentID);

        if(!$dept) return $this->send404();
        return $this->send(200, $dept);
    }

    public function put($departmentID)
    {
        $oldDept = $this->loadModel('dept')->getByID($departmentID);

        /* Set $_POST variables. */
        $fields = 'parent,name,manager';
        $this->batchSetPost($fields, $oldDept);

        $this->requireFields('name');
        $control = $this->loadController('dept', 'edit');
        $control->edit($departmentID);

        $this->getData();
        $department = $this->dept->getByID($departmentID);
        $this->send(200, $department);
    }

    public function delete($departmentID)
    {
        $control = $this->loadController('dept', 'delete');
        $control->delete($departmentID, 'true');

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $this->sendSuccess(200, 'success');
    }
}
