<?php
/**
 * The execution entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     1
 * @link        https://www.zentao.net
 */
class executionStoriesEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function get($executionID)
    {
        if(empty($executionID)) $this->param('execution', 0);
        if(empty($executionID)) return $this->sendError(400, 'Need execution id.');

        $control = $this->loadController('execution', 'story');
        $control->story($executionID, $this->param('storyType', 'story'), $this->param('order', 'id_desc'), $this->param('status', 'all'), 0, 0, $this->param('limit', 20), $this->param('page', 1));
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

    /**
     * POST method: link stories to the execution.
     *
     * @param  int    $executionID
     * @access public
     * @return string
     */
    public function post($executionID)
    {
        if(empty($executionID)) return $this->sendError(400, 'Need execution id.');

        if(!commonModel::hasPriv('execution', 'linkStory')) return $this->sendError(403, 'Access not allowed.');

        $stories = $this->request('stories', array());
        if(!is_array($stories)) $stories = array_filter(explode(',', (string)$stories));
        $stories = array_values(array_filter(array_map('intval', $stories)));
        if(empty($stories)) return $this->sendError(400, 'Need stories.');

        $executionModel = $this->loadModel('execution');
        $execution      = $executionModel->getByID($executionID);
        if(empty($execution)) return $this->sendError(404, 'Execution not found.');

        /* Same order as executionModel::linkStories(): a story has to be linked to the project as well. */
        if($execution->type != 'project' and !empty($execution->project)) $executionModel->linkStory((int)$execution->project, $stories);
        $executionModel->linkStory((int)$executionID, $stories);
        if(dao::isError()) return $this->sendError(400, dao::getError());

        return $this->send(201, array('execution' => (int)$executionID, 'stories' => $stories));
    }

    /**
     * DELETE method: unlink a story from the execution.
     *
     * @param  int    $executionID
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function delete($executionID, $storyID)
    {
        if(empty($executionID)) return $this->sendError(400, 'Need execution id.');
        if(empty($storyID))     return $this->sendError(400, 'Need story id.');
        if(!commonModel::hasPriv('execution', 'unlinkStory')) return $this->sendError(403, 'Access not allowed.');

        $executionModel = $this->loadModel('execution');
        $execution      = $executionModel->getByID($executionID);
        if(empty($execution)) return $this->sendError(404, 'Execution not found.');

        $executionModel->unlinkStory((int)$executionID, (int)$storyID);
        if(dao::isError()) return $this->sendError(400, dao::getError());

        return $this->sendSuccess(200, 'success');
    }
}
