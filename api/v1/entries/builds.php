<?php
/**
 * 禅道API的builds资源类
 * 版本V1
 *
 * The builds entry point of zentaopms
 * Version 1
 */
class buildsEntry extends entry
{
    public function get($projectID = 0)
    {
        $control = $this->loadController('project', 'build');
        $control->build($projectID, $this->param('type', 'all'), $this->param('param', 0));
        $data = $this->getData();

        if(!isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(400, $data->message);

        $result = array();
        foreach($data->data->projectBuilds as $productID => $builds)
        {
            foreach($builds as $build)
            {
                $result[] = $build;
            }
        }
        return $this->send(200, $result);
    }

    public function post($projectID = 0)
    {
        $project = $this->loadModel('project')->getByID($projectID);
        if(!$project) return $this->send404();

        $fields = 'type,title,severity,pri,assignedTo,deadline,desc';
        $this->batchSetPost($fields);

        $control = $this->loadController('build', 'create');
        $this->requireFields('type,title,severity');

        $control->create($projectID);

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and!isset($data->id)) return $this->sendError(400, $data->message);

        $build = $this->loadModel('build')->getByID($data->id);

        $this->send(201, $this->format($build, 'createdDate:time,editedDate:time,assignedDate:time'));
    }
}
