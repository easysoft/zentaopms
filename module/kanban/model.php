<?php
/**
 * The model file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: model.php 5118 2021-10-22 10:18:41Z $
 * @link        https://www.zentao.net
 */
?>
<?php
class kanbanModel extends model
{
    /**
     * Get lanes by execution id.
     *
     * @param  int    $executionID
     * @param  string $objectType all|story|bug|task
     * @access public
     * @return array
     */
    public function getLanesByExecution($executionID, $objectType = 'all')
    {
        return $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($objectType != 'all')->andWhere('type')->eq($objectType)
            ->fetchAll('id');
    }

    /**
     * Add execution Kanban lanes.
     *
     * @param  int|array $executionIdList
     * @access public
     * @return void
     */
    public function addKanbanLanes($executionIdList)
    {
        if(is_numeric($executionIdList)) $executionIdList = (array)$executionIdList;
        if(!is_array($executionIdList) or empty($executionIdList)) return;

        /* Set lane Information. */
        global $lang;
        $storyLane = clone $bugLane = clone $taskLane = new stdClass();
        $storyLane->type  = 'story';
        $storyLane->name  = $lang->SRCommon;
        $storyLane->color = '#7ec5ff';
        $storyLane->order = '5';

        $bugLane->type  = 'bug';
        $bugLane->name  = $lang->bug->common;
        $bugLane->color = '#ba55d3';
        $bugLane->order = '10';

        $taskLane->type  = 'task';
        $taskLane->name  = $lang->task->common;
        $taskLane->color = '#4169e1';
        $taskLane->order = '15';

        /* Get stories, bugs and tasks. */
        $stories = $this->dao->select('t1.*,t2.project as project')->from(TABLE_STORY)->alias('t1')
            ->leftJoin(TABLE_PROJECTSTORY)->alias('t2')->on('t1.id=t2.story')
            ->where('t1.deleted')->eq(0)
            ->andWhere('t1.parent')->ne('-1')
            ->andWhere('t2.project')->in($executionIdList)
            ->fetchGroup('project', 'id');

        $bugs = $this->dao->select('*')->from(TABLE_BUG)
            ->where('deleted')->eq(0)
            ->andWhere('execution')->in($executionIdList)
            ->fetchGroup('execution', 'id');

        $tasks = $this->dao->select('*')->from(TABLE_TASK)
            ->where('deleted')->eq(0)
            ->andWhere('parent')->ne('-1')
            ->andWhere('execution')->in($executionIdList)
            ->fetchGroup('execution', 'id');

        foreach($executionIdList as $executionID)
        {
            $storyLaneID     = 0;
            $bugLaneID       = 0;
            $taskLaneID      = 0;
            $developColumn   = 0;
            $testColumn      = 0;
            $resolvingColumn = 0;

            $lanes[$executionID] = new stdClass();
            $storyLane->execution = $bugLane->execution = $taskLane->execution = $executionID;

            $this->dao->insert(TABLE_KANBANLANE)->data($storyLane)->exec();
            $storyLaneID = $this->dao->lastInsertId();

            $this->dao->insert(TABLE_KANBANLANE)->data($bugLane)->exec();
            $bugLaneID = $this->dao->lastInsertId();

            $this->dao->insert(TABLE_KANBANLANE)->data($taskLane)->exec();
            $taskLaneID = $this->dao->lastInsertId();

            $executionStories = empty($stories[$executionID]) ? array() : $stories[$executionID];
            foreach($this->lang->kanban->storyColumn as $type => $name)
            {
                $data = new stdClass();
                $data->lane  = $storyLaneID;
                $data->name  = $name;
                $data->type  = $type;
                $data->cards = '';
                if(strpos(',developing,developed,', $type) !== false) $data->parent = $developColumn;
                if(strpos(',testing,tested,', $type) !== false) $data->parent = $testColumn;
                if(strpos(',ready,develop,test,', $type) === false)
                {
                    foreach($executionStories as $storyID => $story)
                    {
                        if($type == 'backlog' and $story->status == 'active' and $story->stage == 'projected')
                        {
                            $data->cards .= $storyID . ',';
                            unset($executionStories[$storyID]);
                        }

                        if($type == 'closed' and $story->status == 'closed' and $story->stage == 'closed')
                        {
                            $data->cards .= $storyID . ',';
                            unset($executionStories[$storyID]);
                        }

                        if($story->stage == $type and strpos(',backlog,closed,', $type) === false and $story->status == 'active')
                        {
                            $data->cards .= $storyID . ',';
                            unset($executionStories[$storyID]);
                        }
                    }
                    if(!empty($data->cards)) $data->cards = ',' . $data->cards;
                }
                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                if($type == 'develop') $developColumn = $this->dao->lastInsertId();
                if($type == 'test')    $testColumn    = $this->dao->lastInsertId();
            }

            $executionBugs = empty($bugs[$executionID]) ? array() : $bugs[$executionID];
            foreach($this->lang->kanban->bugColumn as $type => $name)
            {
                $data = new stdClass();
                $data->lane  = $bugLaneID;
                $data->name  = $name;
                $data->type  = $type;
                $data->cards = '';
                if(strpos(',fixing,fixed,', $type) !== false) $data->parent = $resolvingColumn;
                if(strpos(',testing,tested,', $type) !== false) $data->parent = $testColumn;
                if(strpos(',resolving,fixing,test,testing,tested,', $type) === false)
                {
                    foreach($executionBugs as $bugID => $bug)
                    {
                        if($type == 'unconfirmed' and $bug->status == 'active' and $bug->confirmed == 0)
                        {
                            $data->cards .= $bugID . ',';
                            unset($executionBugs[$bugID]);
                        }

                        if($type == 'confirmed' and $bug->status == 'active' and $bug->confirmed == 1)
                        {
                            $data->cards .= $bugID . ',';
                            unset($executionBugs[$bugID]);
                        }

                        if($type == 'fixed' and $bug->status == 'resolved')
                        {
                            $data->cards .= $bugID . ',';
                            unset($executionBugs[$bugID]);
                        }

                        if($type == 'closed' and $bug->status == 'closed')
                        {
                            $data->cards .= $bugID . ',';
                            unset($executionBugs[$bugID]);
                        }
                    }
                    $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                    if($type == 'resolving') $resolvingColumn = $this->dao->lastInsertId();
                    if($type == 'test')      $testColumn      = $this->dao->lastInsertId();
                }
            }

            $executionTasks = empty($tasks[$executionID]) ? array() : $tasks[$executionID];
            foreach($this->lang->kanban->taskColumn as $type => $name)
            {
                $data = new stdClass();
                $data->lane  = $taskLaneID;
                $data->name  = $name;
                $data->type  = $type;
                $data->cards = '';
                if(strpos(',developing,developed,', $type) !== false) $data->parent = $developColumn;
                if(strpos(',develop,', $type) === false)
                {
                    foreach($executionTasks as $taskID => $task)
                    {
                        if($type == 'developing' and $task->status == 'doing')
                        {
                            $data->cards .= $taskID . ',';
                            unset($executionTasks[$taskID]);
                        }

                        if($type == 'developed' and $task->status == 'done')
                        {
                            $data->cards .= $taskID . ',';
                            unset($executionTasks[$taskID]);
                        }

                        if(strpos(',wait,pause,canceled,closed,', $type) !== false and $task->status == $type)
                        {
                            $data->cards .= $taskID . ',';
                            unset($executionTasks[$taskID]);
                        }
                    }
                    $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                    if($type == 'develop') $developColumn = $this->dao->lastInsertId();
                }
            }
        }
    }
}
