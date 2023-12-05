<?php
/**
 * The story entry point of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     entries
 * @version     1
 * @link        http://www.zentao.net
 */
class storyEntry extends entry
{
    /**
     * GET method.
     *
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function get($storyID)
    {
        $this->resetOpenApp($this->param('tab', 'product'));

        $control = $this->loadController('story', 'view');
        $control->view($storyID);

        $data = $this->getData();

        if(!$data or !isset($data->status)) return $this->send400('error');
        if(isset($data->status) and $data->status == 'fail') return $this->sendError(zget($data, 'code', 400), $data->message);

        $story = $data->data->story;

        if(!empty($story->children)) $story->children  = array_values((array)$story->children);
        if(isset($story->planTitle)) $story->planTitle = array_values((array)$story->planTitle);
        if($story->parent > 0) $story->parentPri = $this->dao->select('pri')->from(TABLE_STORY)->where('id')->eq($story->parent)->fetch('pri');

        /* Set product name and status*/
        $story->productName   = $data->data->product->name;
        $story->productStatus = $data->data->product->status;

        /* Set module title */
        $moduleTitle = '';
        if(empty($story->module)) $moduleTitle = '/';
        if($story->module)
        {
            $modulePath = $data->data->modulePath;
            foreach($modulePath as $key => $module)
            {
                $moduleTitle .= $module->name;
                if(isset($modulePath[$key + 1])) $moduleTitle .= '/';
            }
        }
        $story->moduleTitle = $moduleTitle;

        $storyTasks = array();
        foreach($story->tasks as $executionTasks)
        {
            foreach($executionTasks as $task)
            {
                if(!isset($data->data->executions->{$task->execution})) continue;
                $storyTasks[] = $this->filterFields($task, 'id,name,type,status,assignedTo');
            }
        }
        $story->tasks = $this->format($storyTasks, 'assignedTo:user');

        $story->bugs = array();
        foreach($data->data->bugs as $bug) $story->bugs[] = $this->filterFields($bug, 'id,title,status,pri,severity');

        $story->cases = array();
        foreach($data->data->cases as $case) $story->cases[] = $this->filterFields($case, 'id,title,pri,status');

        $story->requirements = array();
        foreach($data->data->relations as $relation) $story->requirements[] = $this->filterFields($relation, 'id,title');

        $story->actions = $this->loadModel('action')->processActionForAPI($data->data->actions, $data->data->users, $this->lang->story);

        $preAndNext = $data->data->preAndNext;
        $story->preAndNext = array();
        $story->preAndNext['pre']  = $preAndNext->pre  ? $preAndNext->pre->id : '';
        $story->preAndNext['next'] = $preAndNext->next ? $preAndNext->next->id : '';

        return $this->send(200, $this->format($story, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList'));
    }

    /**
     * PUT method.
     *
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function put($storyID)
    {
        $oldStory = $this->loadModel('story')->getByID($storyID);

        /* Set $_POST variables. */
        $fields = 'title,product,parent,reviewer,type,plan,module,source,sourceNote,category,pri,estimate,mailto,keywords,uid,stage,notifyEmail';
        $this->batchSetPost($fields, $oldStory);

        $control = $this->loadController('story', 'edit');
        $control->edit($storyID);

        $data = $this->getData();

        if(isset($data->result) and $data->result == 'fail') return $this->sendError(400, $data->message);
        if(!isset($data->data)) return $this->sendError(400, 'error');

        $story = $this->story->getByID($storyID);
        return $this->send(200, $this->format($story, 'openedBy:user,openedDate:time,assignedTo:user,assignedDate:time,reviewedBy:user,reviewedDate:time,lastEditedBy:user,lastEditedDate:time,closedBy:user,closedDate:time,deleted:bool,mailto:userList'));
    }

    /**
     * DELETE method.
     *
     * @param  int    $storyID
     * @access public
     * @return string
     */
    public function delete($storyID)
    {
        $control = $this->loadController('story', 'delete');
        $control->delete($storyID, 'yes');

        $this->getData();
        return $this->sendSuccess(200, 'success');
    }
}
