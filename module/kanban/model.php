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

        /* Get parent column type pairs. */
        $parentTypes = $this->dao->select('id, type')->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq(0)
            ->andWhere('lane')->in(array_keys($lanes))
            ->andWhere('parent')->eq(-1)
            ->fetchPairs('id', 'type');

        /* Get group objects. */
        $objectGroup['story'] = $this->loadModel('story')->getExecutionStories($executionID);
        $objectGroup['bug']   = $this->loadModel('bug')->getExecutionBugs($executionID);
        $objectGroup['task']  = $this->loadModel('execution')->getKanbanTasks($executionID, "id");

        /* Build kanban group data. */
        $kanbanGroup = array();
        foreach($lanes as $laneID => $lane)
        {
            $laneData   = array();
            $columnData = array();
            $laneType   = $lane->type;

            $laneData['id']              = $laneType;
            $laneData['name']            = $lane->name;
            $laneData['color']           = $lane->color;
            $laneData['order']           = $lane->order;
            $laneData['defaultCardType'] = $laneType;

            foreach($columns[$laneID] as $columnID => $column)
            {
                $columnData[$column->id]['id']         = $laneType . '-' . $column->type;
                $columnData[$column->id]['type']       = $column->type;
                $columnData[$column->id]['name']       = $column->name;
                $columnData[$column->id]['color']      = $column->color;
                $columnData[$column->id]['limit']      = $column->limit;
                $columnData[$column->id]['asParent']   = $column->parent == -1 ? true : false;

                if($column->parent > 0)
                {
                    $columnData[$column->id]['parentType'] = zget($parentTypes, $column->parent, '');
                }

                $cardOrder  = 1;
                $cardIdList = array_filter(explode(',', $column->cards));
                foreach($cardIdList as $cardID)
                {
                    $cardData = array();
                    $objects  = zget($objectGroup, $laneType, array());
                    $object   = zget($objects, $cardID, array());

                    $cardData['id']         = $object->id;
                    $cardData['order']      = $cardOrder;
                    $cardData['pri']        = $object->pri ? $object->pri : '';
                    $cardData['estimate']   = $laneType == 'bug' ? '' : $object->estimate;
                    $cardData['assignedTo'] = $object->assignedTo;
                    $cardData['deadline']   = $laneType == 'task' ? $object->deadline : '';
                    $cardData['severity']   = $laneType == 'bug' ? $object->severity : '';

                    if($laneType == 'task')
                    {
                        $cardData['name'] = $object->name;
                    }
                    else
                    {
                        $cardData['title'] = $object->title;
                    }

                    $laneData['cards'][$column->type][] = $cardData;
                    $cardOrder ++;
                }
            }

            $kanbanGroup[$laneType]['id']              = $laneType;
            $kanbanGroup[$laneType]['columns']         = array_values($columnData);
            $kanbanGroup[$laneType]['lanes'][]         = $laneData;
            $kanbanGroup[$laneType]['defaultCardType'] = $laneType;
        }

        return $kanbanGroup;
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
                if(strpos(',develop,test,', $colType) !== false) $data->parent = -1;
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
                if(strpos(',resolving,test,', $colType) !== false) $data->parent = -1;
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
                if($colType == 'develop') $data->parent = -1;
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

    /**
     * Get column by id.
     *
     * @param  int    $columnID
     * @access public
     * @return object
     */
    public function getColumnById($columnID)
    {
        $column = $this->dao->select('t1.*, t2.type as laneType')->from(TABLE_KANBANCOLUMN)->alias('t1')
            ->leftjoin(TABLE_KANBANLANE)->alias('t2')->on('t1.lane=t2.id')
            ->where('t1.id')->eq($columnID)
            ->andWhere('t1.deleted')->eq(0)
            ->fetch();

        if(!empty($column->parent)) $column->parentName = $this->dao->findById($column->parent)->from(TABLE_KANBANCOLUMN)->fetch('name');

        return $column;
    }

    /**
     * Get Column by column name.
     *
     * @param  string $name
     * @param  int    $laneID
     * @access public
     * @return object
     */
    public function getColumnByName($name, $laneID)
    {
        return $this->dao->select('*')
            ->from(TABLE_KANBANCOLUMN)
            ->where('name')->eq($name)
            ->andWhere('lane')->eq($laneID)
            ->fetch();
    }


    /**
     * Get lane by id.
     *
     * @param  int    $laneID
     * @access public
     * @return object
     */
    public function getLaneById($laneID)
    {
        return $this->dao->findById($laneID)->from(TABLE_KANBANLANE)->fetch();
    }

    /**
     * Set WIP limit.
     *
     * @param  int    $columnID
     * @access public
     * @return bool
     */
    public function setWIP($columnID)
    {
        $column = $this->getColumnById($columnID);
        $data   = fixer::input('post')
            ->cleanInt('limit')
            ->remove('WIPCount,noLimit')
            ->get();

        $this->dao->update(TABLE_KANBANCOLUMN)->data($data)
            ->autoCheck()
            ->checkIF($data->limit != -1, 'limit', 'gt', 0)
            ->batchcheck($this->config->kanban->setwip->requiredFields, 'notempty')
            ->where('id')->eq($columnID)
            ->exec();

        return dao::isError();
    }

    /**
     * Set lane info.
     *
     * @param  int    $laneID
     * @access public
     * @return bool
     */
    public function setLane($laneID)
    {
        $lane = fixer::input('post')->get();

        $this->dao->update(TABLE_KANBANLANE)->data($lane)
            ->autoCheck()
            ->batchcheck($this->config->kanban->setlane->requiredFields, 'notempty')
            ->where('id')->eq($laneID)
            ->exec();

        return dao::isError();
    }

    /**
     * Update lane column.
     *
     * @param  int    $columnID
     * @param  object $column
     * @access public
     * @return array
     */
    public function updateLaneColumn($columnID, $column)
    {
        $data = fixer::input('post')->get();

        $this->dao->update(TABLE_KANBANCOLUMN)->data($data)
            ->autoCheck()
            ->batchcheck($this->config->kanban->setlaneColumn->requiredFields, 'notempty')
            ->where('id')->eq($columnID)
            ->exec();

        if(dao::isError()) return;

        $changes = common::createChanges($column, $data);
        return $changes;
    }
}
