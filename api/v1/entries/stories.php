<?php
/**
 * 禅道API的stories资源类
 * 版本V1
 *
 * The stories entry point of zentaopms
 * Version 1
 */
class storiesEntry extends entry 
{
    public function get($productID)
    {
        $control = $this->loadController('product', 'browse');
        $control->browse($productID, $this->param('branch', 0), $this->param('type', ''), 0, 'story', $this->param('order', ''), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $stories = $data->data->stories;
            $pager   = $data->data->pager;
            $result  = array();
            foreach($stories as $story)
            {
                $result[] = $this->format($story, 'openedDate:time,assignedDate:time,reviewedDate:time,lastEditedDate:time,closedDate:time');
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'stories' => $result));
        }

        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        return $this->sendError(400, 'error');
    }

    public function post($productID)
    {
        $fields = 'title,spec,verify,reviewer,type';
        $this->batchSetPost($fields);

        /* If reviewer is not post, set needNotReview. */
        if(empty($this->request('reviewer'))) $this->setPost('needNotReview', 1);
        $this->setPost('product', $productID);

        $control = $this->loadController('story', 'create');
        $this->requireFields('title,spec,type');

        $control->create($productID);
        
        $data = $this->getData();
        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $story = $this->loadModel('story')->getByID($data->id);

        $this->send(200, $this->format($story, 'openedDate:time,assignedDate:time,reviewedDate:time,lastEditedDate:time,closedDate:time'));
    }
}
