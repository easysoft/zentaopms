<?php
/**
 * The executionunlinkstories entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2026 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        https://www.zentao.net
 */
class executionUnlinkStoriesEntry extends entry
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
        $control = $this->loadController('execution', 'unlinkStory');

        $stories = $this->request('stories', array());
        if(empty($stories)) return $this->sendError(400, 'Need stories.');

        $execution = $this->loadModel('execution')->getByID($executionID);
        if(!$execution) return $this->sendError(404, 'Not found');

        $model = $this->loadModel('execution');
        foreach($stories as $storyID)
        {
            $model->unlinkStory($executionID, (int)$storyID);
            if(dao::isError()) return $this->sendError(400, dao::getError());

            /* If the story is not linked to any execution of the project, unlink it from the project too. */
            if($execution->type == 'kanban')
            {
                $executions       = $this->dao->select('*')->from(TABLE_EXECUTION)->where('parent')->eq($execution->parent)->fetchAll('id');
                $executionStories = $this->dao->select('project,story')->from(TABLE_PROJECTSTORY)->where('story')->eq((int)$storyID)->andWhere('project')->in(array_keys($executions))->fetchAll();
                if(empty($executionStories)) $model->unlinkStory($execution->parent, (int)$storyID);
            }
        }

        return $this->getStories($executionID);
    }

    /**
     * Get the linked stories of the execution.
     *
     * @param  int    $executionID
     * @access private
     * @return string
     */
    private function getStories(int $executionID)
    {
        $control = $this->loadController('execution', 'story');
        $control->story($executionID, $this->param('storyType', 'story'), $this->param('order', 'id_desc'), 'all', 0, 0, $this->param('limit', 20), $this->param('page', 1));

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
