<?php
/**
 * The stories entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * @return string
     */
    public function get($productID = 0)
    {
        if(!$productID) $productID = $this->param('product');
        if(!$productID) return $this->sendError(400, 'Need product id.');

        $control = $this->loadController('product', 'browse');
        $control->browse($productID, $this->param('branch', ''), $this->param('status', 'unclosed'), 0, $this->param('type', 'story'), $this->param('order', 'id_desc'), 0, $this->param('limit', 500), $this->param('page', 1));

        $data = $this->getData();
        if(!$data or !isset($data->status)) return $this->sendError(400, 'error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $stories      = $data->data->stories;
        $pager        = $data->data->pager;
        $requirements = $this->loadModel('story')->getRequirements($productID);

        $result  = array();
        $this->loadModel('product');
        foreach($stories as $story)
        {
            $product              = $this->product->getById($story->product);
            $story->productStatus = $product->status;
            if(isset($story->children))
            {
                $story->children = array_values((array)$story->children);
                foreach($story->children as $id => $children)
                {
                    $childrenProduct                     = $this->product->getById($children->product);
                    $story->children[$id]->productStatus = $childrenProduct->status;
                }
            }

            $result[] = $this->format($story, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList');
        }
        return $this->send(201, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'stories' => $result, 'requirements' => $requirements));
    }

    /**
     * POST method.
     *
     * @param  int    $productID
     * @access public
     * @return string
     */
    public function post($productID = 0)
    {
        if(!$productID) $productID = $this->param('product', 0);
        if(!$productID and isset($this->requestBody->product)) $productID = $this->requestBody->product;
        if(!$productID) return $this->sendError(400, 'Need product id.');

        $fields = 'title,spec,verify,module,reviewer,type,parent,moduleOptionMenu,source,sourceNote,category,pri,estimate,mailto,keywords,notifyemail,uid,URS,status';
        $this->batchSetPost($fields);

        $this->setPost('plans', array($this->request('plan')));
        $this->setPost('branches', array($this->request('branch')));
        $this->setPost('modules', array($this->request('module')));

        /* If reviewer is not post, set needNotReview. */
        $reviewer = $this->request('reviewer');
        if(empty($reviewer)) $this->setPost('needNotReview', 1);
        $this->setPost('product', $productID);
        $this->setPost('type', $this->param('type', 'story'));
        $this->setPost('status', $this->param('status', 'draft'));

        $control = $this->loadController('story', 'create');
        $this->requireFields('title,spec,pri,category');

        $control->create($productID, $this->param('branch', 0), $this->param('moduleID', 0), $this->param('storyID', 0), $this->param('objectID', 0), $this->param('bugID', 0));

        $data = $this->getData();
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        if(isset($data->result) and !isset($data->id)) return $this->sendError(400, $data->message);

        $story = $this->loadModel('story')->getByID($data->id);
        return $this->send(200, $this->format($story, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList'));
    }
}
