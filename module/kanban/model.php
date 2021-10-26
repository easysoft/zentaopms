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
     * Get Kanban by execution id.
     *
     * @param  int    $executionID
     * @param  string $objectType all|story|bug|task
     * @access public
     * @return array
     */
    public function getExecutionKanban($executionID, $objectType = 'all')
    {
        $lanes = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($objectType != 'all')->andWhere('type')->eq($objectType)
            ->fetchAll('id');

        if(empty($lanes)) return array();
        $columns = $this->dao->select('*')->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq(0)
            ->andWhere('lane')->in(array_keys($lanes))
            ->fetchGroup('lane', 'id');

        foreach($columns as $laneID => $cols)
        {
            foreach($cols as $colID => $col)
            {
                if($col->parent > 0)
                {
                    $cols[$col->parent]->childs[$colID] = $col;
                    unset($cols[$colID]);
                }
            }
        }

        foreach($lanes as $laneID => $lane) $lane->columns = $columns[$laneID];
        return $lanes;
    }

    /**
     * Add execution Kanban lanes and columns.
     *
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function createLanes($executionID)
    {
        foreach($this->config->kanban->default as $type => $lane)
        {
            $lane->type      = $type;
            $lane->execution = $executionID;
            $this->dao->insert(TABLE_KANBANLANE)->data($lane)->exec();

            $laneID = $this->dao->lastInsertId();
            $this->createColumns($laneID, $type, $executionID);
        }
    }

    /**
     * createColumn
     *
     * @param  int    $laneID
     * @param  string $type story|bug|task
     * @param  int    $executionID
     * @access public
     * @return void
     */
    public function createColumns($laneID, $type, $executionID)
    {
        $objects = array();
        if($type == 'story') $objects = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, 't2.id_desc');
        if($type == 'bug')   $objects = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($type == 'task')  $objects = $this->loadModel('execution')->getKanbanTasks($executionID);

        $devColumnID = $testColumnID = $resolvingColumnID = 0;
        if($type == 'story')
        {
            foreach($this->lang->kanban->storyColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->lane  = $laneID;
                $data->name  = $name;
                $data->type  = $colType;
                $data->cards = '';

                if(strpos(',developing,developed,', $colType) !== false) $data->parent = $devColumnID;
                if(strpos(',testing,tested,', $colType) !== false) $data->parent = $testColumnID;
                if(strpos(',ready,develop,test,', $colType) === false)
                {
                    $storyStatus = $this->config->kanban->storyColumnStatusList[$colType];
                    $storyStage  = $this->config->kanban->storyColumnStageList[$colType];
                    foreach($objects as $storyID => $story)
                    {
                        if($story->status == $storyStatus and $story->stage == $storyStage) $data->cards .= $storyID . ',';
                    }
                    if(!empty($data->cards)) $data->cards = ',' . $data->cards;
                }

                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                if($colType == 'develop') $devColumnID  = $this->dao->lastInsertId();
                if($colType == 'test')    $testColumnID = $this->dao->lastInsertId();
            }
        }
        elseif($type == 'bug')
        {
            foreach($this->lang->kanban->bugColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->lane  = $laneID;
                $data->name  = $name;
                $data->type  = $colType;
                $data->cards = '';
                if(strpos(',fixing,fixed,', $colType) !== false) $data->parent = $resolvingColumnID;
                if(strpos(',testing,tested,', $colType) !== false) $data->parent = $testColumnID;
                if(strpos(',resolving,fixing,test,testing,tested,', $colType) === false)
                {
                    $bugStatus = $this->config->kanban->bugColumnStatusList[$colType];
                    foreach($objects as $bugID => $bug)
                    {
                        if($colType == 'unconfirmed' and $bug->status == $bugStatus and $bug->confirmed == 0)
                        {
                            $data->cards .= $bugID . ',';
                        }
                        elseif($colType == 'confirmed' and $bug->status == $bugStatus and $bug->confirmed == 1)
                        {
                            $data->cards .= $bugID . ',';
                        }
                        elseif($bug->status == $bugStatus)
                        {
                            $data->cards .= $bugID . ',';
                        }
                    }
                    if(!empty($data->cards)) $data->cards = ',' . $data->cards;
                }
                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                if($colType == 'resolving') $resolvingColumnID = $this->dao->lastInsertId();
                if($colType == 'test')      $testColumnID      = $this->dao->lastInsertId();
            }
        }
        elseif($type == 'task')
        {
            foreach($this->lang->kanban->taskColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->lane  = $laneID;
                $data->name  = $name;
                $data->type  = $colType;
                $data->cards = '';
                if(strpos(',developing,developed,', $colType) !== false) $data->parent = $devColumnID;
                if(strpos(',develop,', $colType) === false)
                {
                    $taskStatus = $this->config->kanban->taskColumnStatusList[$colType];
                    foreach($objects as $taskID => $task)
                    {
                        if($task->status == $taskStatus) $data->cards .= $taskID . ',';

                    }
                    if(!empty($data->cards)) $data->cards = ',' . $data->cards;
                }
                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                if($colType == 'develop') $devColumnID = $this->dao->lastInsertId();
            }
        }
    }

    /**
     * Update column cards.
     *
     * @param  int    $executionID
     * @param  string $objectType story|bug|task
     * @param  object $columns
     * @access public
     * @return void
     */
    public function updateCards($executionID, $objectType, $columns)
    {
        $objects = array();
        if($objectType == 'story') $objects = $this->loadModel('story')->getExecutionStories($executionID);
        if($objectType == 'bug')   $objects = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($objectType == 'task')  $objects = $this->loadModel('execution')->getKanbanTasks($executionID);
        if($objectType == 'story')
        {
            $data = new stdClass();
            foreach($columns as $colID => $col)
            {
                foreach($objects as $storyID => $story)
                {
                    if($col->type == 'backlog' and $story->status == 'active' and $story->stage == 'projected') $data->cards .= $storyID . ',';

                    if($colType == 'closed' and $story->status == 'closed' and $story->stage == 'closed') $data->cards .= $storyID . ',';

                    if($story->stage == $colType and strpos(',backlog,closed,', $colType) === false and $story->status == 'active') $data->cards .= $storyID . ',';
                }
                if(!empty($data->cards)) $data->cards = ',' . $data->cards;
            }

            $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
            if($colType == 'develop') $devColumnID  = $this->dao->lastInsertId();
            if($colType == 'test')    $testColumnID = $this->dao->lastInsertId();
        }
        elseif($type == 'bug')
        {
            foreach($this->lang->kanban->bugColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->lane  = $laneID;
                $data->name  = $name;
                $data->type  = $colType;
                $data->cards = '';
                if(strpos(',fixing,fixed,', $colType) !== false) $data->parent = $resolvingColumnID;
                if(strpos(',testing,tested,', $colType) !== false) $data->parent = $testColumnID;
                if(strpos(',resolving,fixing,test,testing,tested,', $colType) === false)
                {
                    foreach($objects as $bugID => $bug)
                    {
                        if($colType == 'unconfirmed' and $bug->status == 'active' and $bug->confirmed == 0) $data->cards .= $bugID . ',';

                        if($colType == 'confirmed' and $bug->status == 'active' and $bug->confirmed == 1) $data->cards .= $bugID . ',';

                        if($colType == 'fixed' and $bug->status == 'resolved') $data->cards .= $bugID . ',';

                        if($colType == 'closed' and $bug->status == 'closed') $data->cards .= $bugID . ',';
                    }
                    $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                    if($colType == 'resolving') $resolvingColumnID = $this->dao->lastInsertId();
                    if($colType == 'test')      $testColumnID      = $this->dao->lastInsertId();
                }
            }
        }
        elseif($type == 'task')
        {
            foreach($this->lang->kanban->taskColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->lane  = $laneID;
                $data->name  = $name;
                $data->type  = $colType;
                $data->cards = '';
                if(strpos(',developing,developed,', $colType) !== false) $data->parent = $devColumnID;
                if(strpos(',develop,', $colType) === false)
                {
                    foreach($objects as $taskID => $task)
                    {
                        if($colType == 'developing' and $task->status == 'doing') $data->cards .= $taskID . ',';

                        if($colType == 'developed' and $task->status == 'done') $data->cards .= $taskID . ',';

                        if(strpos(',wait,pause,canceled,closed,', $colType) !== false and $task->status == $colType) $data->cards .= $taskID . ',';
                    }

                    $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
                    if($colType == 'develop') $devColumnID = $this->dao->lastInsertId();
                }
            }
        }
    }
}
