<?php
/**
 * 禅道API的projects资源类
 * 版本V1
 *
 * The projects entry point of zentaopms
 * Version 1
 */
class projectsEntry extends entry
{
    public function get()
    {
        $control = $this->loadController('project', 'browse');
        $control->browse();
        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $pager = $data->data->pager;
            $result = array();
            foreach($data->data->projectStats as $project)
            {
                $result[] = $this->format($project, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time');
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'projects' => $result));
        }

        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        return $this->sendError(400, 'error');
    }

    public function post()
    {
        $fields = 'name,begin,end,products';
        $this->batchSetPost($fields);

        $this->setPost('acl', $this->request('acl', 'private'));
        $this->setPost('parent', $this->request('program', 0));
        $this->setPost('whitelist', $this->request('whitelist', array()));
        $this->setPost('PM', $this->request('PM', ''));
        $this->setPost('model', $this->request('model', 'scrum'));

        $control = $this->loadController('project', 'create');
        $this->requireFields('name,begin,end,products');

        $control->create($this->request('model', 'scrum'));

        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->result)) return $this->sendError(400, 'error');

        $project = $this->loadModel('project')->getByID($data->id);

        $this->send(201, $this->format($project, 'openedDate:time,lastEditedDate:time,closedDate:time,canceledDate:time'));
    }
}
