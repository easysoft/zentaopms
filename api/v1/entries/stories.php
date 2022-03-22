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
     * @access public
     * @return void
     */
    public function get($productID = 0)
    {
        if(!$productID) $productID = $this->param('product');
        if(!$productID) return $this->sendError(400, 'Need product id.');

        $control = $this->loadController('product', 'browse');
        $control->browse($productID, $this->param('branch', ''), $this->param('status', 'unclosed'), 0, $this->param('type', 'story'), $this->param('order', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $stories = $data->data->stories;
        $pager   = $data->data->pager;
        $result  = array();
        foreach($stories as $story)
        {
            if(isset($story->children)) $story->children = array_values((array)$story->children);
            $result[] = $this->format($story, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList');
        }
        return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'stories' => $result));
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
        if(!$productID) $productID = $this->param('product', 0);
        if(!$productID and isset($this->requestBody->product)) $productID = $this->requestBody->product;
        if(!$productID) return $this->sendError(400, 'Need product id.');

        $fields = 'title,spec,verify,reviewer,type,plan,module,moduleOptionMenu,source,sourceNote,category,pri,estimate,mailto,keywords,notifyemail,uid';
        $this->batchSetPost($fields);

        /* If reviewer is not post, set needNotReview. */
        $reviewer = $this->request('reviewer');
        if(empty($reviewer)) $this->setPost('needNotReview', 1);
        $this->setPost('product', $productID);
        $this->setPost('type', $this->param('type', 'story'));

        $control = $this->loadController('story', 'create');
        $this->requireFields('title,spec,pri,category');

        $control->create($productID);

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $story = $this->loadModel('story')->getByID($data->id);
        $this->send(200, $this->format($story, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList'));
    }
}
