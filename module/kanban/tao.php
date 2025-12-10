<?php
declare(strict_types=1);
/**
 * The tao file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming <sunguangming@easycorp.ltd>
 * @package     kanban
 * @version     $Id: model.php 5118 2021-10-22 10:18:41Z $
 * @link        https://www.zentao.net
 */
class kanbanTao extends kanbanModel
{
    /*
     * 创建看板。
     * Create a kanban.
     *
     * @param  object $kanban
     * @access public
     * @return int
     */
    protected function createKanban($kanban)
    {
        $this->dao->insert(TABLE_KANBAN)->data($kanban, 'copyRegion,copyKanbanID')
             ->autoCheck()
             ->batchCheck($this->config->kanban->create->requiredFields, 'notempty')
             ->checkIF(!$kanban->fluidBoard, 'colWidth', 'ge', $this->config->minColWidth)
             ->batchCheckIF($kanban->fluidBoard, 'minColWidth', 'ge', $this->config->minColWidth)
             ->checkIF($kanban->fluidBoard && $kanban->minColWidth >= $this->config->minColWidth, 'maxColWidth', 'gt', $kanban->minColWidth)
             ->check('name', 'unique', "space = {$kanban->space}")
             ->exec();
    }

    /**
     * 构造看板区域的数据结构。
     * Update a kanban.
     *
     * @param  array  $regionData
     * @param  array  $groups
     * @param  array  $laneGroup
     * @param  array  $columnGroup
     * @param  array  $cardGroup
     * @access public
     * @return array
     */
    protected function buildRegionData(array $regionData, array $groups, array $laneGroup, array $columnGroup, array $cardGroup): array
    {
        $laneCount  = 0;
        $groupData  = array();
        foreach($groups as $group)
        {
            $lanes = zget($laneGroup, $group->id, array());
            if(!$lanes) continue;

            $cols  = zget($columnGroup, $group->id, array());
            $items = zget($cardGroup, $group->id, array());

            /* 计算各个列上的卡片数量。 */
            $columnCount = array();
            $parentCols  = array();
            foreach($cols as $col) $parentCols[$col['id']] = $col['parent'];
            foreach($items as $colGroup)
            {
                foreach($colGroup as $colID => $cards)
                {
                    if(!isset($columnCount[$colID])) $columnCount[$colID] = 0;
                    $columnCount[$colID] += count($cards);

                    if(isset($parentCols[$colID]) && $parentCols[$colID] > 0)
                    {
                        if(!isset($columnCount[$parentCols[$colID]])) $columnCount[$parentCols[$colID]] = 0;
                        $columnCount[$parentCols[$colID]] += count($cards);
                    }
                }
            }

            foreach($cols as $colIndex => $col) $cols[$colIndex]['cards'] = isset($columnCount[$col['id']]) ? $columnCount[$col['id']] : 0;

            $laneCount += count($lanes);

            $groupData['id']            = $group->id;
            $groupData['key']           = "group{$group->id}";
            $groupData['data']['lanes'] = $lanes;
            $groupData['data']['cols']  = $cols;
            $groupData['data']['items'] = $items;

            $regionData['items'][] = $groupData;
        }
        $regionData['laneCount'] = $laneCount;

        return $regionData;
    }

    /**
     * 更新看板区域的排序。
     * Update sort of kanban region.
     *
     * @param  array  $regionIdList
     * @access public
     * @return void
     */
    protected function updateRegionSort(array $regionIdList)
    {
        $order = 1;
        foreach($regionIdList as $regionID)
        {
            $this->dao->update(TABLE_KANBANREGION)->set('`order`')->eq($order)->where('id')->eq($regionID)->exec();
            $order++;
        }
    }

    /**
     * 更新看板泳道的排序。
     * Update sort of kanban lane.
     *
     * @param  int    $regionID
     * @param  array  $lanes
     * @access public
     * @return void
     */
    protected function updateLaneSort(int $regionID, array $lanes)
    {
        $order = 1;
        foreach($lanes as $laneID)
        {
            $this->dao->update(TABLE_KANBANLANE)->set('`order`')->eq($order)->where('id')->eq($laneID)->andWhere('region')->eq($regionID)->exec();
            $order++;
        }
    }

    /**
     * 更新看板列的排序。
     * Update sort of kanban lane.
     *
     * @param  int    $regionID
     * @param  array  $columns
     * @access public
     * @return void
     */
    protected function updateColumnSort(int $regionID, array $columns)
    {
        $order = 1;
        foreach($columns as $columnID)
        {
            $this->dao->update(TABLE_KANBANCOLUMN)->set('`order`')->eq($order)->where('id')->eq($columnID)->andWhere('region')->eq($regionID)->exec();
            $order ++;
        }
    }

    /**
     * 为看板子列添加看板单元格。
     * Add kanban cell for child column.
     *
     * @param  int    $columnID
     * @param  int    $childColumnID
     * @param  int    $i
     * @access public
     * @return void
     */
    protected function addChildColumnCell(int $columnID, int $childColumnID, int $i = 0)
    {
        $cellList = $this->dao->select('*')->from(TABLE_KANBANCELL)->where('`column`')->eq($columnID)->fetchAll('', false);
        foreach($cellList as $cell)
        {
            $newCell = new stdclass();
            $newCell->kanban = $cell->kanban;
            $newCell->lane   = $cell->lane;
            $newCell->column = $childColumnID;
            $newCell->type   = 'common';
            $newCell->cards  = $i == 0 ? $cell->cards : '';

            $this->dao->insert(TABLE_KANBANCELL)->data($newCell)->exec();
            $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq('')->where('id')->eq($cell->id)->exec();
        }
    }

    /**
     * 更新看板列的父列。
     * Update parent column of kanban column.
     *
     * @param  object $column
     * @access public
     * @return void
     */
    protected function updateColumnParent(object $column)
    {
        $children = $this->dao->select('COUNT(1) AS count')->from(TABLE_KANBANCOLUMN)
            ->where('parent')->eq($column->parent)
            ->andWhere('id')->ne($column->id)
            ->andWhere('deleted')->eq('0')
            ->andWhere('archived')->eq('0')
            ->fetch('count');

        if(!$children) $this->dao->update(TABLE_KANBANCOLUMN)->set('parent')->eq(0)->where('id')->eq($column->parent)->exec();
    }

    /**
     * 转入卡片时，更新卡片的指派人。
     * Update assignedTo of card when move in.
     *
     * @param  int    $cardID
     * @param  string $oldAssignedToList
     * @param  array  $users
     * @access public
     * @return void
     */
    protected function updateCardAssignedTo($cardID, $oldAssignedToList, $users)
    {
        $assignedToList = explode(',', $oldAssignedToList);
        foreach($assignedToList as $index => $account)
        {
            if(!isset($users[$account])) unset($assignedToList[$index]);
        }

        $assignedToList = implode(',', $assignedToList);
        $assignedToList = trim($assignedToList, ',');

        if($oldAssignedToList != $assignedToList)
        {
            $this->dao->update(TABLE_KANBANCARD)->set('assignedTo')->eq($assignedToList)->where('id')->eq($cardID)->exec();
        }
    }

    /**
     * 获取可转入卡片的产品。
     * Get products can be imported.
     *
     * @param  string $objectType
     * @access public
     * @return array
     */
    protected function getCanImportProducts($objectType = 'productplan'): array
    {
        $productPairs = $this->loadModel('product')->getPairs('', 0, '', 'all');

        $excludeProducts = array();
        if($objectType == 'productPlan')
        {
            $excludeProducts = $this->dao->select('t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t2.type')->eq('project')
                ->andWhere('t2.model')->ne('scrum')
                ->andWhere('t2.hasProduct')->eq('0')
                ->fetchPairs();
        }
        elseif($objectType == 'release')
        {
            $excludeProducts = $this->dao->select('t1.product')->from(TABLE_PROJECTPRODUCT)->alias('t1')
                ->leftJoin(TABLE_PROJECT)->alias('t2')->on('t1.project = t2.id')
                ->where('t2.type')->eq('project')
                ->andWhere('t2.model')->eq('kanban')
                ->andWhere('t2.hasProduct')->eq('0')
                ->fetchPairs();
        }

        if(!empty($excludeProducts))
        {
            foreach($productPairs as $id)
            {
                if(isset($excludeProducts[$id])) unset($productPairs[$id]);
            }
        }

        return array($this->lang->kanban->allProducts) + $productPairs;
    }

    /**
     * 构造导入卡片的数据结构。
     * Build import card data structure.
     *
     * @param  object $objectType
     * @param  object $object
     * @param  string $fromType
     * @param  array  $creators
     * @access public
     * @return object
     */
    protected function buildObjectCard(object $objectCard, object $object, string $fromType, array $creators): object
    {
        if($fromType == 'productplan' or $fromType == 'release')
        {
            $objectCard->createdBy = zget($creators, $object->id, '');
            $objectCard->delay     = helper::today() > $objectCard->end ? true : false;
        }

        if($fromType =='execution')
        {
            if($object->status != 'done' and $object->status != 'closed' and $object->status != 'suspended')
            {
                $delay = helper::diffDate(helper::today(), $object->end);
                if($delay > 0) $objectCard->delay = $delay;
            }
            $objectCard->execType = $object->type;
            $objectCard->progress = $object->progress;

            $parentExecutions  = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(trim($object->path, ','))->andWhere('type')->in('stage,kanban,sprint')->orderBy('grade')->fetchPairs();
            $objectCard->title = implode('/', $parentExecutions);

            $children             = $this->dao->select('count(1) as children')->from(TABLE_EXECUTION)->where('parent')->eq($object->id)->andWhere('type')->in('stage,kanban,sprint')->andWhere('deleted')->eq(0)->fetch('children');
            $objectCard->children = !empty($children) ? $children : 0;
        }

        $objectCard->originDesc   = str_replace(array('<p>', '</p>'), "\n", $object->desc);
        $objectCard->originDesc   = strip_tags($objectCard->originDesc);
        $objectCard->desc         = strip_tags(htmlspecialchars_decode($object->desc));
        $objectCard->objectStatus = $objectCard->status;
        $objectCard->status       = $objectCard->progress == 100 ? 'done' : 'doing';

        return $objectCard;
    }

    /**
     * 更新执行下看板的卡片。
     * Update card of execution.
     *
     * @param  int    $executionID
     * @param  int    $colID
     * @param  int    $laneID
     * @param  string $cards
     * @access public
     * @return void
     */
    protected function updateExecutionCell(int $executionID, int $colID, int $laneID, string $cards)
    {
        $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($cards)
              ->where('kanban')->eq($executionID)
              ->andWhere('lane')->eq($laneID)
              ->andWhere('`column`')->eq($colID)
              ->exec();
    }

    /**
     * 获取产品计划看板的分支
     * Get branches for product plan kanban.
     *
     * @param  object $product
     * @param  string $branchID
     * @access public
     * @return array
     */
    protected function getBranchesForPlanKanban(object $product, string $branchID): array
    {
        $this->loadModel('branch');

        $branches = array();
        if($product->type == 'normal')
        {
            $branches = array('all' => $this->lang->productplan->allAB);
        }
        elseif($branchID == 'all')
        {
            $branches = $this->branch->getPairs($product->id, 'active');
        }
        elseif($branchID == BRANCH_MAIN)
        {
            $branches = array(BRANCH_MAIN => $this->lang->branch->main);
        }
        elseif($branchID)
        {
            foreach(explode(',', $branchID) as $id)
            {
                $branchName = $this->branch->getById($id);
                $branches[$id] = $branchName;
            }
        }

        return $branches;
    }

    /**
     * 获取专业研发看板分组视图下的看板数据。
     * Get kanban data for group view of RD kanban.
     *
     * @param  array  $regionData
     * @param  array  $groups
     * @param  array  $laneGroup
     * @param  array  $columnGroup
     * @param  array  $cardGroup
     * @param  string $searchValue
     * @access public
     * @return array
     */
    protected function buildRDRegionData(array $regionData, array $groups, array $laneGroup, array $columnGroup, array $cardGroup, string $searchValue = '')
    {
        $laneCount    = 0;
        $groupData    = array();
        $fromKanbanID = '';
        foreach($groups as $group)
        {
            $lanes = zget($laneGroup, $group->id, array());
            if(!$lanes) continue;

            $kanbanID = "group{$group->id}";

            $cols  = zget($columnGroup, $group->id, array());
            $items = zget($cardGroup, $group->id, array());

            if($searchValue != '' and empty($items)) continue;

            $lane = current($lanes);
            if($lane['type'] == 'parentStory')
            {
                $fromKanbanID = $kanbanID;
            }
            elseif($lane['type'] == 'story')
            {
                foreach($items as $colGroup)
                {
                    foreach($colGroup as $cards)
                    {
                        foreach($cards as $card)
                        {
                            $regionData['links'][] = array('fromKanban' => $fromKanbanID, 'toKanban' => $kanbanID, 'from' => $card['parent'], 'to' => $card['id']);
                        }
                    }
                }
            }

            /* 计算各个列上的卡片数量。 */
            $columnCount = array();
            $parentCols  = array();
            foreach($cols as $col) $parentCols[$col['id']] = $col['parent'];
            foreach($items as $colGroup)
            {
                foreach($colGroup as $colID => $cards)
                {
                    if(!isset($columnCount[$colID])) $columnCount[$colID] = 0;
                    $columnCount[$colID] += count($cards);

                    if(isset($parentCols[$colID]) && $parentCols[$colID] > 0)
                    {
                        if(!isset($columnCount[$parentCols[$colID]])) $columnCount[$parentCols[$colID]] = 0;
                        $columnCount[$parentCols[$colID]] += count($cards);
                    }
                }
            }

            foreach($cols as $colIndex => $col)
            {
                $cols[$colIndex]['cards']    = isset($columnCount[$col['id']]) ? $columnCount[$col['id']] : 0;
                $cols[$colIndex]['laneType'] = $lane['type'];
            }

            $lanes = array_values($lanes);
            $laneCount += count($lanes);

            $groupData['id']            = $group->id;
            $groupData['key']           = $kanbanID;
            $groupData['data']['lanes'] = $lanes;
            $groupData['data']['cols']  = $cols;
            $groupData['data']['items'] = $items;

            $regionData['items'][] = $groupData;
        }

        $regionData['laneCount'] = $laneCount;

        return $regionData;
    }

    /**
     * 初始化卡片数据。
     * Init card data.
     *
     * @param  object $card
     * @param  object $cell
     * @param  int    $order
     * @param  array  $avatarPairs
     * @param  array  $users
     * @access public
     * @return array
     */
    protected function initCardItem(object $card, object $cell, int $order, array $avatarPairs, array $users): array
    {
        $item = array();
        $item['column']       = $cell->column;
        $item['lane']         = $cell->lane;
        $item['title']        = !empty($card->title) ? htmlspecialchars_decode($card->title) : htmlspecialchars_decode($card->name);
        $item['id']           = $card->id;
        $item['name']         = $card->id;
        $item['pri']          = $card->pri;
        $item['color']        = $card->color;
        $item['assignedTo']   = $card->assignedTo;
        $item['parent']       = !empty($card->originParent) ? $card->originParent : 0;
        $item['parent']       = !empty($card->rawParent) ? $card->rawParent : 0;
        $item['isParent']     = !empty($card->isParent) ? $card->isParent: 0;
        $item['progress']     = !empty($card->progress) ? $card->progress : 0;
        $item['group']        = !empty($card->group) ? $card->group : '';
        $item['region']       = !empty($card->region) ? $card->region : '';
        $item['begin']        = !empty($card->begin) ? $card->begin : '';
        $item['end']          = !empty($card->end) ? $card->end  : '';
        $item['fromID']       = !empty($card->fromID) ? $card->fromID : 0;
        $item['fromType']     = !empty($card->fromType) ? $card->fromType : '';
        $item['desc']         = !empty($card->desc) ? $card->desc : '';
        $item['originDesc']   = !empty($card->originDesc) ? $card->originDesc : '';
        $item['delay']        = !empty($card->delay) ? $card->delay : 0;
        $item['status']       = !empty($card->status) ? $card->status : '';
        $item['objectStatus'] = !empty($card->objectStatus) ? $card->objectStatus : '';
        $item['deleted']      = !empty($card->deleted) ? $card->deleted : 0;
        $item['date']         = !empty($card->date) ? $card->date : '';
        $item['estimate']     = !empty($card->estimate) ? $card->estimate : 0;
        $item['deadline']     = !empty($card->deadline) ? $card->deadline : '';
        $item['severity']     = !empty($card->severity) ? $card->severity : '';
        $item['cardType']     = $cell->type;
        $item['avatarList']   = array();
        $item['realnames']    = '';
        $item['order']        = $order;

        if($card->assignedTo)
        {
            $assignedToList = explode(',', $card->assignedTo);
            foreach($assignedToList as $account)
            {
                if(!$account) continue;
                $maxTextLen = 2;
                $realname   = zget($users, $account, '');
                $mbLength   = mb_strlen($realname, 'utf-8');
                $strLength  = strlen($realname);

                $displayText = '';
                if($realname)
                {
                    if(isset($realname[0]) && $strLength === $mbLength)
                    {
                        /* Pure alphabet or numbers 纯英文情况 */
                        $displayText = strtoupper($realname[0]);
                    }
                    else if($strLength % $mbLength == 0 && $strLength % 3 == 0)
                    {
                        /* Pure chinese characters 纯中文的情况 */
                        $displayText = $mbLength <= $maxTextLen ? $realname : mb_substr($realname, $mbLength - $maxTextLen, $mbLength, 'utf-8');
                    }
                    else
                    {
                        /* Mix of Chinese and English 中英文混合的情况 */
                        $displayText = $mbLength <= $maxTextLen ? $realname : mb_substr($realname, 0, $maxTextLen, 'utf-8');
                    }
                }

                $userAvatar = zget($avatarPairs, $account, '');
                $userAvatar = $userAvatar ? "<img src='$userAvatar'/>" : $displayText;
                $item['avatarList'][]  = $userAvatar;
                $item['realnames']    .= $realname . ' ';
            }
        }

        if($cell->type == 'task')
        {
            $item['left']       = $card->left;
            $item['estStarted'] = $card->estStarted;
            $item['mode']       = $card->mode;
            $item['execution']  = $card->execution;
            $item['story']      = $card->story;
            $item['module']     = $card->module;

            $dbPrivs = array();
            $actions = array('edit', 'restart', 'pause', 'recordworkhour', 'activate', 'delete', 'cancel', 'assignto');
            foreach($actions as $action)
            {
                $dbPrivs[$action] = common::hasDBPriv($card, 'task', $action);
            }
            $item['dbPrivs']  = $dbPrivs;
            $item['canSplit'] = $this->loadModel('task')->isClickable($card, 'batchCreate') && !$card->mode;
        }

        return $item;
    }

    /**
     * 处理看板中多人任务的指派人。
     * Process assignedTo of multi tasks.
     *
     * @param  array  $cardList
     * @access public
     * @return array
     */
    protected function appendTeamMember(array $cardList): array
    {
        $multiTasks = array();
        foreach($cardList as $id => $task)
        {
            if($task->mode == 'multi') $multiTasks[$id] = $task;
        }

        $taskTeams = $this->dao->select('t1.account,t1.task,t2.realname')->from(TABLE_TASKTEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.task')->in(array_keys($multiTasks))
            ->orderBy('t1.order')
            ->fetchGroup('task', 'account');
        foreach($multiTasks as $taskID => $task)
        {
            $teamPairs = array();
            foreach($taskTeams[$taskID] as $account => $team) $teamPairs[$account] = $team->realname;
            $task->teamMember  = $teamPairs;
            $cardList[$taskID] = $task;
        }

        return $cardList;
    }

    /**
     * 构造看板的分组视图。
     * Build group view of kanban.
     *
     * @param  array $lanes
     * @param  array $columns
     * @param  array $cardGroup
     * @param  string $searchValue
     * @param  string $groupBy
     * @param  string $browseType
     * @param  array $menus
     * @access public
     * @return array
     */
    protected function buildGroupKanban(array $lanes, array $columns, array $cardGroup, string $searchValue, string $groupBy, string $browseType, array $menus): array
    {
        $columnList  = array();
        $laneList    = array();
        $cardList    = array();
        $avatarPairs = $this->loadModel('user')->getAvatarPairs();
        $users       = $this->loadModel('user')->getPairs('noletter');
        $module      = $browseType == 'parentStory' ? 'story' : $browseType;
        foreach($lanes as $laneID => $lane)
        {
            $laneData = array();
            $laneData['id']     = $groupBy . $laneID;
            $laneData['type']   = $browseType;
            $laneData['name']   = $laneData['id'];
            $laneData['region'] = $lane->execution;
            $laneData['title']  = (($groupBy == 'pri' or $groupBy == 'severity') and $laneID) ? $this->lang->$module->$groupBy . ':' . $lane->name : $lane->name;
            $laneData['color']  = $lane->color;
            $laneData['order']  = $lane->order;

            if(empty($laneID) and !in_array($groupBy, array('module', 'story', 'pri', 'severity'))) $laneID = '';

            /* Construct kanban column data. */
            foreach($columns as $column)
            {
                $cardIdList = array_unique(array_filter(explode(',', $column->cards)));
                $columnData = $this->buildGroupColumn($columnList, $column, $laneData, $browseType);

                list($cardCount, $cardData) = $this->buildGroupCard($cardGroup, $cardIdList, $column, (string)$laneID, $groupBy, $browseType, $searchValue, $avatarPairs, $users, $menus);

                $columnData['cards'] += $cardCount;
                $cardList[$laneData['id']][$column->column] = $cardData;
                $columnList[$column->column] = $columnData;
            }
            $laneList[] = $laneData;
        }

        foreach($columnList as $column)
        {
            if(isset($column['parentName']) && isset($columnList[$column['parentName']])) $columnList[$column['parentName']]['cards'] += $column['cards'];
        }

        return array($laneList, $columnList, $cardList);
    }

    /**
     * 构造看板分组视图的列。
     * Build column of group view.
     *
     * @param  array  $columnsL
     * @param  object $column
     * @param  array  $laneData
     * @param  string $browseType
     * @access public
     * @return array
     */
    protected function buildGroupColumn(array $columnList, object $column, array $laneData, string $browseType): array
    {
        if(!isset($columnList[$column->column]))
        {
            $columnData = array();
            $columnData['id']         = $column->column;
            $columnData['type']       = $column->columnType;
            $columnData['name']       = $column->column;
            $columnData['title']      = $column->columnName;
            $columnData['color']      = $column->color;
            $columnData['limit']      = $column->limit;
            $columnData['region']     = $laneData['region'];
            $columnData['laneName']   = $column->lane;
            $columnData['group']      = $browseType;
            $columnData['cards']      = 0;
            $columnData['actionList'] = array('setColumn', 'setWIP');
            if($column->parent > 0) $columnData['parentName'] = $column->parent;
        }
        else
        {
            $columnData = $columnList[$column->column];
        }

        return $columnData;
    }

    /**
     * 构造看板分组视图的卡片。
     * Build card of group view.
     *
     * @param  array  $cardGroup
     * @param  array  $cardIdList
     * @param  object $column
     * @param  string $laneID
     * @param  string $groupBy
     * @param  string $browseType
     * @param  string $searchValue
     * @param  array  $avatarPairs
     * @param  array  $users
     * @param  array  $menus
     * @access public
     * @return array
     */
    protected function buildGroupCard(array $cardGroup, array $cardIdList, object $column, string $laneID, string $groupBy, string $browseType, string $searchValue, array $avatarPairs, array $users, array $menus): array
    {
        $cardCount = 0;
        $cardList  = array();
        $objects   = zget($cardGroup, $column->columnType, array());
        foreach($cardIdList as $cardID)
        {
            $object = zget($objects, $cardID, array());

            if(empty($object)) continue;
            if(in_array($groupBy, array('module', 'story', 'pri', 'severity')) and $object->$groupBy != $laneID) continue;
            if(in_array($groupBy, array('type', 'category', 'source')) and $object->$groupBy != $laneID) continue;
            if($groupBy == 'assignedTo')
            {
                $laneID = (string)$laneID;
                if(empty($object->$groupBy)) $object->$groupBy = '';
                if(empty($object->teamMember) and $object->$groupBy != $laneID) continue;
                if(!empty($object->teamMember) and !in_array($laneID, array_keys($object->teamMember), true)) continue;
            }

            $cardData = $this->buildExecutionCard($object, $column, $browseType, $searchValue, $menus);
            if(empty($cardData)) continue;

            $cardData['cardType'] = $browseType;
            if($groupBy == 'assignedTo' && $object->$groupBy !== $laneID) $cardData['assignedTo'] = $laneID;
            if($cardData['assignedTo'])
            {
                $userAvatar = zget($avatarPairs, $cardData['assignedTo'], '');
                $userAvatar = $userAvatar ? "<img src='$userAvatar'/>" : strtoupper(mb_substr($cardData['assignedTo'], 0, 1, 'utf-8'));
                $cardData['avatarList'][] = $userAvatar;
                $cardData['realnames']    = zget($users, $cardData['assignedTo'], '');
            }
            $cardList[] = $cardData;
            $cardCount ++;
        }

        return array($cardCount, $cardList);
    }

    /**
     * 获取看板分组视图的对象键值对。
     * Get object pairs for group view.
     *
     * @param  string $groupBy
     * @param  array  $groupByList
     * @param  string $browseType
     * @param  string $orderBy
     * @access public
     * @return array
     */
    protected function getObjectPairs(string $groupBy, array $groupByList, string $browseType, string $orderBy): array
    {
        if($browseType == 'parentStory') $browseType = 'story';
        $objectPairs = array();
        if(in_array($groupBy, array('module', 'story', 'assignedTo')))
        {
            if($groupBy == 'module')
            {
                $objectPairs += $this->dao->select('id,name')->from(TABLE_MODULE)->where('type')->in('story,task,bug')->andWhere('deleted')->eq('0')->andWhere('id')->in($groupByList)->fetchPairs();
            }
            elseif($groupBy == 'story')
            {
                $objectPairs += $this->dao->select('id,title')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('id')->in($groupByList)->orderBy($orderBy)->fetchPairs();
            }
            else
            {
                $objectPairs += $this->dao->select('account,realname')->from(TABLE_USER)->where('account')->in($groupByList)->fetchPairs();
                if(isset($groupByList['closed'])) $objectPairs['closed'] = 'Closed';
            }
        }
        else
        {
            unset($this->lang->$browseType->{$groupBy . 'List'}[0]);
            unset($this->lang->$browseType->{$groupBy . 'List'}['']);
            $objectPairs += $this->lang->$browseType->{$groupBy . 'List'};
        }

        if(in_array($groupBy, array('module', 'story', 'pri', 'severity'))) $objectPairs[0] = $this->lang->$browseType->$groupBy . ': ' . $this->lang->kanban->noGroup;
        if(in_array($groupBy, array('assignedTo', 'source'))) $objectPairs[] = $this->lang->$browseType->$groupBy . ': ' . $this->lang->kanban->noGroup;
        if($browseType == 'bug' and $groupBy == 'type') $objectPairs[0] = $this->lang->$browseType->$groupBy . ': ' . $this->lang->kanban->noGroup;

        return $objectPairs;
    }

    /**
     * 更新专业研发看板中的用需、业需、父需求泳道上的卡片。
     * Update card of feature, epic, parent story lane in RD kanban.
     *
     * @param  array  $cardPairs
     * @param  int    $executionID
     * @param  string $otherCardList
     * @param  string $laneType
     * @access public
     * @return array
     */
    protected function refreshERURCards(array $cardPairs, int $executionID, string $otherCardList, $laneType = 'story'): array
    {
        $storyType = $laneType == 'parentStory' ? 'story' : $laneType;
        $stories = $this->loadModel('story')->getExecutionStories($executionID, 0, 't1.`order`_desc', 'allStory', '0', $storyType, $otherCardList);
        foreach($stories as $storyID => $story)
        {
            if($laneType == 'parentStory' && $story->isParent != '1') continue;
            foreach($this->lang->kanban->ERURColumn as $stage => $langItem)
            {
                if($story->stage != $stage and strpos((string)$cardPairs[$stage], ",$storyID,") !== false)
                {
                    $cardPairs[$stage] = str_replace(",$storyID,", ',', $cardPairs[$stage]);
                }

                if($story->stage == $stage and strpos((string)$cardPairs[$stage], ",$storyID,") === false)
                {
                    $cardPairs[$stage] = empty($cardPairs[$stage]) ? ",$storyID," : ",$storyID" . $cardPairs[$stage];
                }
            }
        }

        return $cardPairs;
    }

    /**
     * 更新专业研发看板中的需求泳道上的卡片。
     * Update card of story lane in RD kanban.
     *
     * @param  array  $cardPairs
     * @param  int    $executionID
     * @param  string $otherCardList
     * @access public
     * @return array
     */
    protected function refreshStoryCards(array $cardPairs, int $executionID, string $otherCardList): array
    {
        $stories = $this->loadModel('story')->getExecutionStories($executionID, 0, 't1.`order`_desc', 'allStory', '0', 'story', $otherCardList);
        foreach($stories as $storyID => $story)
        {
            foreach($this->config->kanban->storyColumnStageList as $colType => $stage)
            {
                if(!isset($cardPairs[$colType])) continue;
                if($story->stage != $stage and strpos($cardPairs[$colType], ",$storyID,") !== false)
                {
                    $cardPairs[$colType] = str_replace(",$storyID,", ',', $cardPairs[$colType]);
                }

                if(strpos(',ready,backlog,design,develop,test,', $colType) !== false) continue;

                if($story->stage == $stage and strpos($cardPairs[$colType], ",$storyID,") === false)
                {
                    $cardPairs[$colType] = empty($cardPairs[$colType]) ? ",$storyID," : ",$storyID" . $cardPairs[$colType];
                }
            }

            if(strpos('wait,projected', $story->stage) !== false and strpos($cardPairs['ready'], ",$storyID,") === false and strpos($cardPairs['backlog'], ",$storyID,") === false)
            {
                $cardPairs['backlog'] = empty($cardPairs['backlog']) ? ",$storyID," : ",$storyID" . $cardPairs['backlog'];
            }
        }

        return $cardPairs;
    }

    /**
     * 更新专业研发看板中的Bug泳道上的卡片。
     * Update card of bug lane in RD kanban.
     *
     * @param  array  $cardPairs
     * @param  int    $executionID
     * @param  string $otherCardList
     * @access public
     * @return array
     */
    protected function refreshBugCards(array $cardPairs, int $executionID, string $otherCardList): array
    {
        $bugs = $this->loadModel('bug')->getExecutionBugs($executionID, 0, 'all', '0', '', 0, 'id_desc', $otherCardList);
        foreach($bugs as $bugID => $bug)
        {
            foreach($this->config->kanban->bugColumnStatusList as $colType => $status)
            {
                if($bug->status != $status and strpos($cardPairs[$colType], ",$bugID,") !== false)
                {
                    $cardPairs[$colType] = str_replace(",$bugID,", ',', $cardPairs[$colType]);
                }

                if(strpos(',resolving,test,testing,tested,', $colType) !== false) continue;

                if($colType == 'unconfirmed' and $bug->status == $status and $bug->confirmed == 0 and strpos($cardPairs['unconfirmed'], ",$bugID,") === false and strpos($cardPairs['fixing'], ",$bugID,") === false and $bug->activatedCount == 0)
                {
                    $cardPairs['unconfirmed'] = empty($cardPairs['unconfirmed']) ? ",$bugID," : ",$bugID" . $cardPairs['unconfirmed'];
                    if(strpos($cardPairs['closed'], ",$bugID,") !== false) $cardPairs['closed'] = str_replace(",$bugID,", ',', $cardPairs['closed']);
                }
                elseif($colType == 'confirmed' and $bug->status == $status and $bug->confirmed == 1 and strpos($cardPairs['confirmed'], ",$bugID,") === false and strpos($cardPairs['fixing'], ",$bugID,") === false and $bug->activatedCount == 0)
                {
                    $cardPairs['confirmed'] = empty($cardPairs['confirmed']) ? ",$bugID," : ",$bugID" . $cardPairs['confirmed'];
                    if(strpos($cardPairs['unconfirmed'], ",$bugID,") !== false) $cardPairs['unconfirmed'] = str_replace(",$bugID,", ',', $cardPairs['unconfirmed']);
                }
                elseif($colType == 'fixing' and $bug->status == $status and $bug->activatedCount > 0 and strpos($cardPairs['fixing'], ",$bugID,") === false)
                {
                    $cardPairs['fixing'] = empty($cardPairs['fixing']) ? ",$bugID," : ",$bugID" . $cardPairs['fixing'];
                    if(strpos($cardPairs['confirmed'], ",$bugID,") !== false)   $cardPairs['confirmed']   = str_replace(",$bugID,", ',', $cardPairs['confirmed']);
                    if(strpos($cardPairs['unconfirmed'], ",$bugID,") !== false) $cardPairs['unconfirmed'] = str_replace(",$bugID,", ',', $cardPairs['unconfirmed']);
                }
                elseif($colType == 'fixed' and $bug->status == $status and strpos($cardPairs['fixed'], ",$bugID,") === false and strpos($cardPairs['testing'], ",$bugID,") === false and strpos($cardPairs['tested'], ",$bugID,") === false)
                {
                    $cardPairs['fixed'] = empty($cardPairs['fixed']) ? ",$bugID," : ",$bugID" . $cardPairs['fixed'];
                    if(strpos($cardPairs['testing'], ",$bugID,") !== false) $cardPairs['testing'] = str_replace(",$bugID,", ',', $cardPairs['testing']);
                    if(strpos($cardPairs['tested'], ",$bugID,") !== false)  $cardPairs['tested']  = str_replace(",$bugID,", ',', $cardPairs['tested']);
                }
                elseif($colType == 'closed' and $bug->status == 'closed' and strpos($cardPairs[$colType], ",$bugID,") === false)
                {
                    $cardPairs[$colType] = empty($cardPairs[$colType]) ? ",$bugID," : ",$bugID". $cardPairs[$colType];
                }
            }
        }

        return $cardPairs;
    }

    /**
     * 更新专业研发看板中的任务泳道上的卡片。
     * Update card of bug lane in RD kanban.
     *
     * @param  array  $cardPairs
     * @param  int    $executionID
     * @param  string $otherCardList
     * @access public
     * @return array
     */
    protected function refreshTaskCards(array $cardPairs, int $executionID, string $otherCardList): array
    {
        $tasks = $this->loadModel('execution')->getKanbanTasks($executionID, 'status_asc, id_desc', explode(',', $otherCardList));
        foreach($tasks as $taskID => $task)
        {
            $task->status = $task->status == 'changed' ? $task->rawStatus : $task->status;
            foreach($this->config->kanban->taskColumnStatusList as $colType => $status)
            {
                if($colType == 'develop') continue;
                if(!isset($cardPairs[$colType])) continue;

                if($task->status == $status and strpos($cardPairs[$colType], ",$taskID,") === false)
                {
                    $cardPairs[$colType] = empty($cardPairs[$colType]) ? ",$taskID," : ",$taskID". $cardPairs[$colType];
                }
                elseif($task->status != $status and strpos($cardPairs[$colType], ",$taskID,") !== false)
                {
                    $cardPairs[$colType] = str_replace(",$taskID,", ',', $cardPairs[$colType]);
                }
            }
        }

        return $cardPairs;
    }

    /**
     * 获取父需求类型的卡片操作菜单。
     * Get menu of story card.
     *
     * @param  int    $executionID
     * @param  array  $objects
     * @access public
     * @return array
     */
    protected function getERURCardMenu(int $executionID, array $objects): array
    {
        $execution = $this->loadModel('execution')->getByID($executionID);

        $menus = array();
        $objects = $this->loadModel('story')->mergeReviewer($objects);
        foreach($objects as $story)
        {
            $menu = array();

            if(common::hasPriv($story->type, 'edit') and $this->story->isClickable($story, 'edit'))         $menu[] = array('label' => $this->lang->story->edit, 'icon' => 'edit', 'url' => helper::createLink($story->type, 'edit', "storyID=$story->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv($story->type, 'change') and $this->story->isClickable($story, 'change'))     $menu[] = array('label' => $this->lang->story->change, 'icon' => 'alter', 'url' => helper::createLink($story->type, 'change', "storyID=$story->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv($story->type, 'review') and $this->story->isClickable($story, 'review'))     $menu[] = array('label' => $this->lang->story->review, 'icon' => 'search', 'url' => helper::createLink($story->type, 'review', "storyID=$story->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv($story->type, 'activate') and $this->story->isClickable($story, 'activate')) $menu[] = array('label' => $this->lang->story->activate, 'icon' => 'magic', 'url' => helper::createLink($story->type, 'activate', "storyID=$story->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('execution', 'unlinkStory') && $execution->hasProduct && empty($story->frozen)) $menu[] = array('label' => $this->lang->execution->unlinkStory, 'icon' => 'unlink', 'url' => helper::createLink('execution', 'unlinkStory', "executionID=$executionID&storyID=$story->story&confirm=no&from=taskkanban"));
            if(common::hasPriv($story->type, 'delete') && empty($story->frozen))                               $menu[] = array('label' => $this->lang->story->delete, 'icon' => 'trash', 'url' => helper::createLink($story->type, 'delete', "storyID=$story->id&confirm=no&from=taskkanban"));

            $menus[$story->id] = $menu;
        }

        return $menus;
    }

    /**
     * 获取需求类型的卡片操作菜单。
     * Get menu of story card.
     *
     * @param  object $execution
     * @param  array  $objects
     * @access public
     * @return array
     */
    protected function getStoryCardMenu(object $execution, array $objects): array
    {
        $menus = array();
        $objects = $this->loadModel('story')->mergeReviewer($objects);
        foreach($objects as $story)
        {
            $menu = array();

            $toTaskPriv = strpos('draft,reviewing,closed', $story->status) !== false ? false : true;
            if(common::hasPriv('story', 'edit') && $this->story->isClickable($story, 'edit'))         $menu[] = array('label' => $this->lang->story->edit, 'icon' => 'edit', 'url' => helper::createLink('story', 'edit', "storyID=$story->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('story', 'change') && $this->story->isClickable($story, 'change'))     $menu[] = array('label' => $this->lang->story->change, 'icon' => 'alter', 'url' => helper::createLink('story', 'change', "storyID=$story->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('story', 'review') && $this->story->isClickable($story, 'review'))     $menu[] = array('label' => $this->lang->story->review, 'icon' => 'search', 'url' => helper::createLink('story', 'review', "storyID=$story->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'create') && $toTaskPriv)                                      $menu[] = array('label' => $this->lang->execution->wbs, 'icon' => 'plus', 'url' => helper::createLink('task', 'create', "executionID={$execution->id}&storyID=$story->id&moduleID=$story->module"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'batchCreate') && $toTaskPriv)                                 $menu[] = array('label' => $this->lang->execution->batchWBS, 'icon' => 'pluses', 'url' => helper::createLink('task', 'batchCreate', "executionID={$execution->id}&storyID=$story->id&moduleID=0&taskID=0&iframe=true"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('story', 'activate') && $this->story->isClickable($story, 'activate')) $menu[] = array('label' => $this->lang->story->activate, 'icon' => 'magic', 'url' => helper::createLink('story', 'activate', "storyID=$story->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('execution', 'unlinkStory') && $execution->hasProduct && empty($story->frozen)) $menu[] = array('label' => $this->lang->execution->unlinkStory, 'icon' => 'unlink', 'url' => helper::createLink('execution', 'unlinkStory', "executionID={$execution->id}&storyID=$story->story&confirm=no&from=taskkanban"));
            if(common::hasPriv('story', 'delete') && empty($story->frozen))                                    $menu[] = array('label' => $this->lang->story->delete, 'icon' => 'trash', 'url' => helper::createLink('story', 'delete', "storyID=$story->id&confirm=no&from=taskkanban"));

            $menus[$story->id] = $menu;
        }

        return $menus;
    }

    /**
     * 获取Bug类型的卡片操作菜单。
     * Get menu of bug card.
     *
     * @param  array  $objects
     * @access public
     * @return array
     */
    protected function getBugCardMenu(array $objects): array
    {
        $menus = array();
        $this->loadModel('bug');
        foreach($objects as $bug)
        {
            $menu = array();

            if(common::hasPriv('bug', 'edit')     && $this->bug->isClickable($bug, 'edit'))     $menu[] = array('label' => $this->lang->bug->edit, 'icon' => 'edit', 'url' => helper::createLink('bug', 'edit', "bugID=$bug->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('bug', 'confirm')  && $this->bug->isClickable($bug, 'confirm'))  $menu[] = array('label' => $this->lang->bug->confirm, 'icon' => 'ok', 'url' => helper::createLink('bug', 'confirm', "bugID=$bug->id&extra=&from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('bug', 'resolve')  && $this->bug->isClickable($bug, 'resolve'))  $menu[] = array('label' => $this->lang->bug->resolve, 'icon' => 'checked', 'url' => helper::createLink('bug', 'resolve', "bugID=$bug->id&extra=&from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('bug', 'close')    && $this->bug->isClickable($bug, 'close'))    $menu[] = array('label' => $this->lang->bug->close, 'icon' => 'off', 'url' => helper::createLink('bug', 'close', "bugID=$bug->id&extra=&from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('bug', 'create')   && $this->bug->isClickable($bug, 'create'))   $menu[] = array('label' => $this->lang->bug->copy, 'icon' => 'copy', 'url' => helper::createLink('bug', 'create', "productID=$bug->product&branch=$bug->branch&extras=bugID=$bug->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('bug', 'activate') && $this->bug->isClickable($bug, 'activate')) $menu[] = array('label' => $this->lang->bug->activate, 'icon' => 'magic', 'url' => helper::createLink('bug', 'activate', "bugID=$bug->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('story', 'create') && $bug->status != 'closed')                  $menu[] = array('label' => $this->lang->bug->toStory, 'icon' => 'lightbulb', 'url' => helper::createLink('story', 'create', "product=$bug->product&branch=$bug->branch&module=0&story=0&execution=0&bugID=$bug->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('bug', 'delete'))                                                $menu[] = array('label' => $this->lang->bug->delete, 'icon' => 'trash', 'url' => helper::createLink('bug', 'delete', "bugID=$bug->id&confirm=no&from=taskkanban"), 'confirm' => $this->lang->bug->notice->confirmDelete);

            $menus[$bug->id] = $menu;
        }

        return $menus;
    }

    /**
     * 获取任务类型的卡片操作菜单。
     * Get menu of bug card.
     *
     * @param  array  $objects
     * @param  int    $executionID
     * @access public
     * @return array
     */
    protected function getTaskCardMenu(array $objects, int $executionID): array
    {
        $menus = array();

        $canStartExecution = true;
        if($this->config->edition == 'ipd')
        {
            $executionStatus = $this->loadModel('execution')->checkStageStatus($executionID, 'start');
            $canStartExecution = empty($executionStatus['disabled']);
        }

        $this->loadModel('task');
        foreach($objects as $task)
        {
            $menu = array();

            if(common::hasPriv('task', 'edit') and common::hasDBPriv($task, 'task', 'edit') and $this->task->isClickable($task, 'edit'))                                                      $menu['edit']           = array('label' => $this->lang->task->edit, 'icon' => 'edit', 'url' => helper::createLink('task', 'edit', "taskID=$task->id&comment=false&kanbanGroup=default&from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'finish') and common::hasDBPriv($task, 'task', 'finish') and $this->task->isClickable($task, 'finish') and $canStartExecution)                         $menu['finish']         = array('label' => $this->lang->task->finish, 'icon' => 'checked', 'url' => helper::createLink('task', 'finish', "taskID=$task->id&extra=from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'pause') and common::hasDBPriv($task, 'task', 'pause') and $this->task->isClickable($task, 'pause'))                                                   $menu['pause']          = array('label' => $this->lang->task->pause, 'icon' => 'pause', 'url' => helper::createLink('task', 'pause', "taskID=$task->id&extra=from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'start') and common::hasDBPriv($task, 'task', 'start') and $this->task->isClickable($task, 'start'))                                                   $menu['start']          = array('label' => $this->lang->task->start, 'icon' => 'play', 'url' => helper::createLink('task', 'start', "taskID=$task->id&from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'restart') and common::hasDBPriv($task, 'task', 'restart') and $this->task->isClickable($task, 'restart'))                                             $menu['restart']        = array('label' => $this->lang->task->restart, 'icon' => 'play', 'url' => helper::createLink('task', 'restart', "taskID=$task->id&from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'recordWorkhour') and common::hasDBPriv($task, 'task', 'recordWorkhour') and $this->task->isClickable($task, 'recordWorkhour') and $canStartExecution) $menu['recordWorkhour'] = array('label' => $this->lang->task->recordWorkhour, 'icon' => 'time', 'url' => helper::createLink('task', 'recordWorkhour', "taskID=$task->id&from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'activate') and common::hasDBPriv($task, 'task', 'activate') and $this->task->isClickable($task, 'activate'))                                          $menu['activate']       = array('label' => $this->lang->task->activate, 'icon' => 'magic', 'url' => helper::createLink('task', 'activate', "taskID=$task->id&extra=from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'batchCreate') and common::hasDBPriv($task, 'task', 'batchCreate') and $this->task->isClickable($task, 'batchCreate') and !$task->mode)                $menu['batchCreate']    = array('label' => $this->lang->task->children, 'icon' => 'split', 'url' => helper::createLink('task', 'batchCreate', "execution=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'create') and common::hasDBPriv($task, 'task', 'create') and $this->task->isClickable($task, 'create'))                                                $menu['create']         = array('label' => $this->lang->task->copy, 'icon' => 'copy', 'url' => helper::createLink('task', 'create', "projctID=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'cancel') and common::hasDBPriv($task, 'task', 'cancel') and $this->task->isClickable($task, 'cancel'))                                                $menu['cancel']         = array('label' => $this->lang->task->cancel, 'icon' => 'ban-circle', 'url' => helper::createLink('task', 'cancel', "taskID=$task->id&extra=from=taskkanban"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('task', 'delete') and common::hasDBPriv($task, 'task', 'delete'))                                                                                              $menu['delete']         = array('label' => $this->lang->task->delete, 'icon' => 'trash', 'url' => helper::createLink('task', 'delete', "executionID=$task->execution&taskID=$task->id&confirm=no&from=taskkanban"), 'confirm' => $task->isParent ? $this->lang->task->confirmDeleteParent : $this->lang->task->confirmDelete);

            $menus[$task->id] = $menu;
        }

        return $menus;
    }

    /**
     * 获取风险看板操作菜单。
     * Get the Kanban risk menu.
     *
     * @param  int         $executionID
     * @access public
     * @return array
     */
    public function getRiskCardMenu($risks)
    {
        $menus = array();
        $riskModel = $this->loadModel('risk');
        foreach($risks as $risk)
        {
            $menu = array();
            if(common::hasPriv('risk', 'edit') and $riskModel->isClickable($risk, 'edit'))         $menu[] = array('label' => $this->lang->risk->edit, 'icon' => 'edit', 'url' => helper::createLink('risk', 'edit', "riskID=$risk->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('risk', 'track') and $riskModel->isClickable($risk, 'track'))       $menu[] = array('label' => $this->lang->risk->track, 'icon' => 'checked', 'url' => helper::createLink('risk', 'track', "riskID=$risk->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('risk', 'activate') and $riskModel->isClickable($risk, 'activate')) $menu[] = array('label' => $this->lang->risk->activate, 'icon' => 'magic', 'url' => helper::createLink('risk', 'activate', "riskID=$risk->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('risk', 'hangup') and $riskModel->isClickable($risk, 'hangup'))     $menu[] = array('label' => $this->lang->risk->hangup, 'icon' => 'pause', 'url' => helper::createLink('risk', 'hangup', "riskID=$risk->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('risk', 'cancel') and $riskModel->isClickable($risk, 'cancel'))     $menu[] = array('label' => $this->lang->risk->cancel, 'icon' => 'ban-circle', 'url' => helper::createLink('risk', 'cancel', "riskID=$risk->id"), 'modal' => true, 'size' => 'lg');
            if(common::hasPriv('risk', 'close') and $riskModel->isClickable($risk, 'close'))      $menu[] = array('label' => $this->lang->risk->close, 'icon' => 'off', 'url' => helper::createLink('risk', 'close', "riskID=$risk->id"), 'modal' => true, 'size' => 'lg');

            $menus[$risk->id] = $menu;
        }

        return $menus;
    }
}
