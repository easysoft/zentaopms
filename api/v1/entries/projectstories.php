<?php
/**
 * The project entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     1
 * @link        https://www.zentao.net
 */
class projectStoriesEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $projectID
     * @access public
     * @return string
     */
    public function get($projectID)
    {
        if(!$projectID) $projectID = $this->param('project');
        if(!$projectID) return $this->sendError(400, 'Need product id.');

        $control = $this->loadController('projectstory', 'story');
        $control->story($projectID, $this->param('product', 0), $this->param('branch', ''), $this->param('status', 'unclosed'), 0, 'story', $this->param('order', 'id_desc'), 0, $this->param('limit', 20), $this->param('page', 1));
        $data = $this->getData();

        if(isset($data->status) and $data->status == 'success')
        {
            $stories = $data->data->stories;
            $pager   = $data->data->pager;
            $result  = array();
            $this->loadModel('product');
            foreach($stories as $story)
            {
                $product              = $this->product->getById($story->product);
                $story->productStatus = $product->status;

                $result[] = $this->format($story, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList');
            }
            return $this->send(200, array('page' => $pager->pageID, 'total' => $pager->recTotal, 'limit' => $pager->recPerPage, 'stories' => $result));
        }

        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);
        return $this->sendError(400, 'error');
    }
}
