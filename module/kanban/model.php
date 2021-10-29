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
     * @param  string $browseType all|story|bug|task
     * @access public
     * @return array
     */
    public function getExecutionKanban($executionID, $browseType = 'all')
    {
        $lanes = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($browseType != 'all')->andWhere('type')->eq($browseType)
            ->fetchAll('id');

        if(empty($lanes)) return array();

        foreach($lanes as $lane) $this->updateCards($lane);

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
        if($browseType == 'all' or $browseType == 'story') $objectGroup['story'] = $this->loadModel('story')->getExecutionStories($executionID);
        if($browseType == 'all' or $browseType == 'bug')   $objectGroup['bug']   = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($browseType == 'all' or $browseType == 'task')  $objectGroup['task']  = $this->loadModel('execution')->getKanbanTasks($executionID, "id");

        /* Get objects cards menus. */
        if($browseType == 'all' or $browseType == 'story') $storyCardMenu = $this->getKanbanCardMenu($executionID, $objectGroup['story'], 'story');
        if($browseType == 'all' or $browseType == 'bug')   $bugCardMenu   = $this->getKanbanCardMenu($executionID, $objectGroup['bug'], 'bug');
        if($browseType == 'all' or $browseType == 'task')  $taskCardMenu  = $this->getKanbanCardMenu($executionID, $objectGroup['task'], 'task');

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
                $columnData[$column->id]['columnID']   = $columnID;
                $columnData[$column->id]['type']       = $column->type;
                $columnData[$column->id]['name']       = $column->name;
                $columnData[$column->id]['color']      = $column->color;
                $columnData[$column->id]['limit']      = $column->limit;
                $columnData[$column->id]['laneType']   = $laneType;
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

                    if($laneType == 'story') $cardData['menus'] = $storyCardMenu[$object->id];
                    if($laneType == 'bug')   $cardData['menus'] = $bugCardMenu[$object->id];
                    if($laneType == 'task')  $cardData['menus'] = $taskCardMenu[$object->id];

                    $laneData['cards'][$column->type][] = $cardData;
                    $cardOrder ++;
                }
                if(!isset($laneData['cards'][$column->type])) $laneData['cards'][$column->type] = array();
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
     * @param  object $lane
     * @access public
     * @return void
     */
    public function updateCards($lane)
    {
        $laneType    = $lane->type;
        $executionID = $lane->execution;
        $cardPairs = $this->dao->select('*')->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq(0)
            ->andWhere('lane')->eq($lane->id)
            ->fetchPairs('type' ,'cards');

        if($laneType == 'story')
        {
            $stories = $this->loadModel('story')->getExecutionStories($executionID);
            foreach($stories as $storyID => $story)
            {
                foreach($this->config->kanban->storyColumnStageList as $colType => $stage)
                {
                    if(strpos(',ready,develop,test,', $colType) !== false) continue;
                    if($colType == 'backlog' and $story->stage == $stage and strpos($cardPairs['ready'], ",$storyID,") === false and strpos($cardPairs['backlog'], ",$storyID,") === false)
                    {
                        $cardPairs['backlog'] .= empty($cardPairs['backlog']) ? ',' . $storyID . ',' : $storyID . ',';
                    }
                    elseif($story->stage == $stage and strpos($cardPairs[$colType], ",$storyID,") === false)
                    {
                        $cardPairs[$colType] .= empty($cardPairs[$colType]) ? ',' . $storyID . ',' : $storyID . ',';
                    }
                    elseif($story->stage != $stage and strpos($cardPairs[$colType], ",$storyID,") !== false)
                    {
                        $cardPairs[$colType] = str_replace(",$storyID,", ',', $cardPairs[$colType]);
                    }
                }
            }
        }
        elseif($laneType == 'bug')
        {
            $bugs = $this->loadModel('bug')->getExecutionBugs($executionID);
            foreach($bugs as $bugID => $bug)
            {
                foreach($this->config->kanban->bugColumnStatusList as $colType => $status)
                {
                    if(strpos(',resolving,fixing,test,testing,tested,', $colType) !== false) continue;
                    if($colType == 'unconfirmed' and $bug->status == $status and $bug->confirmed == 0 and strpos($cardPairs['unconfirmed'], ",$bugID,") === false and strpos($cardPairs['fixing'], ",$bugID,") === false)
                    {
                        $cardPairs['unconfirmed'] .= empty($cardPairs['unconfirmed']) ? ',' . $bugID . ',' : $bugID . ',';
                    }
                    elseif($colType == 'confirmed' and $bug->status == $status and $bug->confirmed == 1 and strpos($cardPairs['confirmed'], ",$bugID,") === false and strpos($cardPairs['fixing'], ",$bugID,") === false)
                    {
                        $cardPairs['confirmed'] .= empty($cardPairs['confirmed']) ? ',' . $bugID . ',' : $bugID . ',';
                    }
                    elseif($colType == 'fixed' and $bug->status == $status and strpos($cardPairs['fixed'], ",$bugID,") === false and strpos($cardPairs['testing'], ",$bugID,") === false and strpos($cardPairs['tested'], ",$bugID,") === false)
                    {
                        $cardPairs['confirmed'] .= empty($cardPairs['confirmed']) ? ',' . $bugID . ',' : $bugID . ',';
                    }
                    elseif($bug->status == $status and strpos($cardPairs[$colType], ",$bugID,") === false)
                    {
                        $cardPairs[$colType] .= empty($cardPairs[$colType]) ? ',' . $bugID . ',' : $bugID . ',';
                    }
                    elseif($bug->status != $status and strpos($cardPairs[$colType], ",$bugID,") !== false)
                    {
                        $cardPairs[$colType] = str_replace(",$bugID,", ',', $cardPairs[$colType]);
                    }
                }
            }
        }
        elseif($laneType == 'task')
        {
            $tasks = $this->loadModel('execution')->getKanbanTasks($executionID);
            foreach($tasks as $taskID => $task)
            {
                foreach($this->config->kanban->taskColumnStatusList as $colType => $status)
                {
                    if($colType == 'develop') continue;
                    if($task->status == $status and strpos($cardPairs[$colType], ",$taskID,") === false)
                    {
                        $cardPairs[$colType] .= empty($cardPairs[$colType]) ? ',' . $taskID . ',' : $taskID . ',';
                    }
                    elseif($task->status != $status and strpos($cardPairs[$colType], ",$taskID,") !== false)
                    {
                        $cardPairs[$colType] = str_replace(",$taskID,", ',', $cardPairs[$colType]);
                    }
                }
            }
        }

        foreach($cardPairs as $colType => $cards)
        {
            $this->dao->update(TABLE_KANBANCOLUMN)->set('cards')->eq($cards)->where('lane')->eq($lane->id)->andWhere('type')->eq($colType)->exec();
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
        $oldColumn = $this->getColumnById($columnID);
        $column    = fixer::input('post')
            ->cleanInt('limit')
            ->remove('WIPCount,noLimit')
            ->get();

        /* Check column limit. */
        $sumChildLimit = 0;
        if($oldColumn->parent == -1 and $column->limit != -1)
        {
            $childColumns = $this->dao->select('id,`limit`')->from(TABLE_KANBANCOLUMN)->where('parent')->eq($columnID)->fetchAll();
            foreach($childColumns as $childColumn)
            {
                if($childColumn->limit == -1)
                {
                    dao::$errors['limit'] = $this->lang->kanban->error->parentLimitNote;
                    return false;
                }

                $sumChildLimit += $childColumn->limit;
            }

            if($sumChildLimit > $column->limit)
            {
                dao::$errors['limit'] = $this->lang->kanban->error->parentLimitNote;
                return false;
            }
        }
        elseif($oldColumn->parent > 0)
        {
            $parentColumn = $this->getColumnByID($oldColumn->parent);
            if($parentColumn->limit != -1)
            {
                $siblingLimit = $this->dao->select('`limit`')->from(TABLE_KANBANCOLUMN)
                    ->where('`parent`')->eq($oldColumn->parent)
                    ->andWhere('id')->ne($columnID)
                    ->fetch('limit');

                $sumChildLimit = $siblingLimit + $column->limit;

                if($column->limit == -1 or $siblingLimit == -1 or $sumChildLimit > $parentColumn->limit)
                {
                    dao::$errors['limit'] = $this->lang->kanban->error->childLimitNote;
                    return false;
                }
            }
        }

        $this->dao->update(TABLE_KANBANCOLUMN)->data($column)
            ->autoCheck()
            ->checkIF($column->limit != -1, 'limit', 'gt', 0)
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

    /**
     * Get Kanban cards menus by execution id.
     *
     * @param  int    $executionID
     * @param  array  $objects
     * @param  string $objecType story|bug|task
     * @access public
     * @return array
     */
    public function getKanbanCardMenu($executionID, $objects, $objecType)
    {
        $menus = array();
        switch ($objecType)
        {
            case 'story':
                if(!isset($this->story)) $this->loadModel('story');

                $objects = $this->story->mergeReviewer($objects);
                foreach($objects as $story)
                {
                    $menu = array();

                    $toTaskPriv = strpos('draft,closed', $story->status) !== false ? false : true;
                    if(common::hasPriv('story', 'edit') and $this->story->isClickable($story, 'edit'))         $menu[] = array('label' => $this->lang->story->edit, 'icon' => 'edit', 'url' => helper::createLink('story', 'edit', "storyID=$story->id", '', true), 'size' => '95%');
                    if(common::hasPriv('story', 'change') and $this->story->isClickable($story, 'change'))     $menu[] = array('label' => $this->lang->story->change, 'icon' => 'alter', 'url' => helper::createLink('story', 'change', "storyID=$story->id", '', true), 'size' => '95%');
                    if(common::hasPriv('story', 'review') and $this->story->isClickable($story, 'review'))     $menu[] = array('label' => $this->lang->story->review, 'icon' => 'search', 'url' => helper::createLink('story', 'review', "storyID=$story->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'create') and $toTaskPriv)                                      $menu[] = array('label' => $this->lang->execution->wbs, 'icon' => 'plus', 'url' => helper::createLink('task', 'create', "executionID=$executionID&storyID=$story->id&moduleID=$story->module", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'batchCreate') and $toTaskPriv)                                 $menu[] = array('label' => $this->lang->execution->batchWBS, 'icon' => 'pluses', 'url' => helper::createLink('task', 'batchCreate', "executionID=$executionID&storyID=$story->id&moduleID=0&taskID=0&iframe=true", '', true), 'size' => '95%');
                    if(common::hasPriv('story', 'activate') and $this->story->isClickable($story, 'activate')) $menu[] = array('label' => $this->lang->story->change, 'icon' => 'magic', 'url' => helper::createLink('story', 'activate', "storyID=$story->id", '', true));
                    if(common::hasPriv('execution', 'unlinkStory'))                                            $menu[] = array('label' => $this->lang->execution->unlinkStory, 'icon' => 'unlink', 'url' => helper::createLink('execution', 'unlinkStory', "executionID=$executionID&storyID=$story->story&confirm=no", '', true));

                    $menus[$story->id] = $menu;
                }
                break;
            case 'bug':
                if(!isset($this->bug)) $this->loadModel('bug');

                foreach($objects as $bug)
                {
                    $menu = array();

                    if(common::hasPriv('bug', 'edit') and $this->bug->isClickable($bug, 'edit'))             $menu[] = array('label' => $this->lang->bug->edit, 'icon' => 'edit', 'url' => helper::createLink('bug', 'edit', "bugID=$bug->id", '', true), 'size' => '95%');
                    if(common::hasPriv('bug', 'confirmBug') and $this->bug->isClickable($bug, 'confirmBug')) $menu[] = array('label' => $this->lang->bug->confirmBug, 'icon' => 'ok', 'url' => helper::createLink('bug', 'confirmBug', "bugID=$bug->id", '', true));
                    if(common::hasPriv('bug', 'resolve') and $this->bug->isClickable($bug, 'resolve'))       $menu[] = array('label' => $this->lang->bug->resolve, 'icon' => 'checked', 'url' => helper::createLink('bug', 'resolve', "bugID=$bug->id", '', true));
                    if(common::hasPriv('bug', 'close') and $this->bug->isClickable($bug, 'close'))           $menu[] = array('label' => $this->lang->bug->close, 'icon' => 'plus', 'url' => helper::createLink('bug', 'close', "bugID=$bug->id", '', true));
                    if(common::hasPriv('bug', 'create') and $this->bug->isClickable($bug, 'create'))         $menu[] = array('label' => $this->lang->bug->copy, 'icon' => 'pluses', 'url' => helper::createLink('bug', 'create', "productID=$bug->product&branch=$bug->branch&extras=bugID=$bug->id", '', true), 'size' => '95%');
                    if(common::hasPriv('bug', 'activate') and $this->bug->isClickable($bug, 'activate'))     $menu[] = array('label' => $this->lang->bug->activate, 'icon' => 'magic', 'url' => helper::createLink('bug', 'activate', "bugID=$bug->id", '', true));
                    if(common::hasPriv('story', 'create') and $bug->status != 'closed')                      $menu[] = array('label' => $this->lang->bug->toStory, 'icon' => 'unlink', 'url' => helper::createLink('story', 'create', "product=$bug->product&branch=$bug->branch&module=0&story=0&execution=0&bugID=$bug->id", '', true), 'size' => '95%');

                    $menus[$bug->id] = $menu;
                }
                break;
            case 'task':
                if(!isset($this->task)) $this->loadModel('task');

                foreach($objects as $task)
                {
                    $menu = array();

                    if(common::hasPriv('task', 'edit') and $this->task->isClickable($task, 'edit'))                     $menu[] = array('label' => $this->lang->task->edit, 'icon' => 'edit', 'url' => helper::createLink('task', 'edit', "taskID=$task->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'pause') and $this->task->isClickable($task, 'pause'))                   $menu[] = array('label' => $this->lang->task->pause, 'icon' => 'ok', 'url' => helper::createLink('task', 'pause', "taskID=$task->id", '', true));
                    if(common::hasPriv('task', 'restart') and $this->task->isClickable($task, 'restart'))               $menu[] = array('label' => $this->lang->task->restart, 'icon' => 'play', 'url' => helper::createLink('task', 'restart', "taskID=$task->id", '', true));
                    if(common::hasPriv('task', 'recordEstimate') and $this->task->isClickable($task, 'recordEstimate')) $menu[] = array('label' => $this->lang->task->recordEstimate, 'icon' => 'time', 'url' => helper::createLink('task', 'recordEstimate', "taskID=$task->id", '', true));
                    if(common::hasPriv('task', 'activate') and $this->task->isClickable($task, 'activate'))             $menu[] = array('label' => $this->lang->task->activate, 'icon' => 'magic', 'url' => helper::createLink('task', 'activate', "taskID=$task->id", '', true));
                    if(common::hasPriv('task', 'batchCreate') and $this->task->isClickable($task, 'batchCreate'))       $menu[] = array('label' => $this->lang->task->children, 'icon' => 'split', 'url' => helper::createLink('task', 'batchCreate', "execution=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'create') and $this->task->isClickable($task, 'create'))                 $menu[] = array('label' => $this->lang->task->copy, 'icon' => 'copy', 'url' => helper::createLink('task', 'create', "projctID=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'cancel') and $this->task->isClickable($task, 'cancel'))                 $menu[] = array('label' => $this->lang->task->cancel, 'icon' => 'ban-circle', 'url' => helper::createLink('task', 'cancel', "taskID=$task->id", '', true));

                    $menus[$task->id] = $menu;
                }
                break;
        }
        return $menus;
    }
}
