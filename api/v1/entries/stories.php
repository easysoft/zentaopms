<?php
/**
 * The stories entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class storiesEntry extends entry 
{
    /**
     * GET method.
     *
     * @param  int    $productID
     * @param  int    $projectID
     * @access public
     * @return void
     */
    public function get($productID = 0, $projectID = 0)
    {
        if(!$productID) $productID = $this->param('product');
        if(!$projectID) $projectID = $this->param('project');
        if(!$productID and !$projectID) return $this->sendError(400, 'Need product or project id.');

        if($projectID)
        {
            $control = $this->loadController('projectstory', 'story');
            $control->story($projectID, $productID, $this->param('branch', 0), $this->param('type', ''), 0, 'story', $this->param('order', ''), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
            $data = $this->getData();
        }
        else
        {
            $control = $this->loadController('product', 'browse');
            $control->browse($productID, $this->param('branch', 0), $this->param('type', ''), 0, 'story', $this->param('order', ''), $this->param('total', 0), $this->param('limit', 20), $this->param('page', 1));
            $data = $this->getData();
        }

        if(isset($data->status) and $data->status == 'success')
        {
            $stories = $data->data->stories;
            $pager   = $data->data->pager;
            $result  = array();
            foreach($stories as $story)
            {
                $result[] = $this->format($story, 'openedDate:time,assignedDate:time,reviewedDate:time,lastEditedDate:time,closedDate:time,deleted:bool');
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'stories' => $result));
        }

        if(isset($data->status) and $data->status == 'fail')
        {
            return $this->sendError(400, $data->message);
        }

        return $this->sendError(400, 'error');
    }

    /**
     * POST method.
     *
     * @param  int    $productID
     * @access public
     * @return void
     */
    public function post($productID = 0)
    {
        if(!$productID) $productID = $this->param('product');
        if(!$productID and isset($this->requestBody->product)) $productID = $this->requestBody->product;
        if(!$productID) return $this->sendError(400, 'Need product id.');

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
