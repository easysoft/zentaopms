<?php
/**
 * The executionlinkstories entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class executionLinkStoriesEntry extends entry
{
    /**
     * POST method.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function post($executionID)
    {
        $control = $this->loadController('execution', 'linkStory');

        $storyType = $this->request('storyType', 'story');
        $stories   = $this->request('stories', array());
        if(empty($stories)) return $this->sendError(400, 'Need stories.');

        if(!$this->loadModel('execution')->getByID($executionID)) return $this->sendError(404, 'Not found');

        $this->batchSetPost('stories');
        $control->linkStory($executionID, '', 0, 'id_desc', 50, 1, '', $storyType);

        $data = $this->getData();
        if(!isset($data->status) or $data->status != 'success') return $this->sendError(400, array('message' => isset($data->message) ? $data->message : 'error'));

        return $this->getStories($executionID, $storyType);
    }

    /**
     * Get the linked stories of the execution.
     *
     * @param  int    $executionID
     * @param  string $storyType
     * @access private
     * @return string
     */
    private function getStories(int $executionID, string $storyType)
    {
        $control = $this->loadController('execution', 'story');
        $control->story($executionID, $storyType, $this->param('order', 'id_desc'), 'all', 0, 0, $this->param('limit', 20), $this->param('page', 1));

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
