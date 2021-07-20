<?php
/**
 * 禅道API的bugs资源类
 * 版本V1
 *
 * The bugs entry point of zentaopms
 * Version 1
 */
class bugsEntry extends entry 
{
    public function get($productID)
    {
        $control = $this->loadController('bug', 'browse');
        $control->browse($productID);
        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $bugs   = $data->data->bugs;
            $pager  = $data->data->pager;
            $result = array();
            foreach($bugs as $bug)
            {
                $result[] = $bug;
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'bugs' => $result));
        }
        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        return $this->sendError(400, 'error');
    }

    public function post($productID)
    {
        $fields = 'title,project,execution,openedBuild,assignedTo,pri,severity,type,story';
        $this->batchSetPost($fields);

        $this->setPost('product', $productID);

        $control = $this->loadController('bug', 'create');
        $this->requireFields('title,pri,severity,type,openedBuild');

        $control->create($productID);
        
        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $bug = $this->loadModel('bug')->getByID($data->id);

        $this->send(200, $bug);
    }
}
