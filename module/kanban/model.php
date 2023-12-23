<?php
declare(strict_types=1);
/**
 * The model file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian <tianshujie@easycorp.ltd>
 * @package     kanban
 * @version     $Id: model.php 5118 2021-10-22 10:18:41Z $
 * @link        https://www.zentao.net
 */
class kanbanModel extends model
{
    /**
     * 创建看板分组。
     * Create a kanban group.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @access public
     * @return int|false
     */
    public function createGroup(int $kanbanID, int $regionID): int|false
    {
        $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANGROUP)
            ->where('region')->eq($regionID)
            ->fetch('maxOrder');

        $order = $maxOrder ? $maxOrder + 1 : 1;

        $group = new stdclass();
        $group->kanban = $kanbanID;
        $group->region = $regionID;
        $group->order  = $order;

        $this->dao->insert(TABLE_KANBANGROUP)->data($group)->autoCheck()->exec();
        if(dao::isError()) return false;

        return $this->dao->lastInsertID();
    }

    /**
     * 创建默认看板区域。
     * Create a default kanban region.
     *
     * @param  object $kanban
     * @access public
     * @return int|bool
     */
    public function createDefaultRegion(object $kanban): int|bool
    {
        $region = new stdclass();
        $region->name        = $this->lang->kanbanregion->default;
        $region->kanban      = $kanban->id;
        $region->space       = $kanban->space;
        $region->createdBy   = $this->app->user->account;
        $region->createdDate = helper::now();

        return $this->createRegion($kanban, $region);
    }

    /**
     * 复制看板区域。
     * Copy kanban regions.
     *
     * @param  object $kanban
     * @param  int    $copyKanbanID
     * @param  string $from kanban|execution
     * @param  string $param
     * @access public
     * @return void
     */
    public function copyRegions(object $kanban, int $copyKanbanID, string $from = 'kanban', string $param = 'withArchived')
    {
        if(empty($kanban) or empty($copyKanbanID)) return;

        $regions = $this->getRegionPairs($copyKanbanID, 0, $from);
        $order   = 1;
        foreach($regions as $copyID => $copyName)
        {
            $region = new stdclass();
            $region->name        = $copyName;
            $region->kanban      = $kanban->id;
            $region->space       = $kanban->space;
            $region->createdBy   = $this->app->user->account;
            $region->createdDate = helper::now();
            $region->order       = $order;

            $this->createRegion($kanban, $region, $copyID, $from, $param);
            $order ++;
        }
    }

    /**
     * 创建区域。
     * Create a new region.
     *
     * @param  object $kanban
     * @param  object $fromRegion
     * @param  int    $copyRegionID
     * @param  string $from         kanban|execution
     * @param  string $param
     * @access public
     * @return int
     */
    public function createRegion(object $kanban, object $fromRegion = null, int $copyRegionID = 0, string $from = 'kanban', string $param = '')
    {
        $account = $this->app->user->account;
        $order   = 1;
        $region  = $fromRegion;

        if(empty($region))
        {
            $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANREGION)
                ->where('kanban')->eq($kanban->id)
                ->fetch('maxOrder');

            $order  = $maxOrder ? $maxOrder + 1 : 1;
            $region = new stdclass();
            $region->kanban      = $kanban->id;
            $region->space       = $from == 'kanban' ? $kanban->space : 0;
            $region->name        = $this->post->name;
            $region->createdBy   = $account;
            $region->createdDate = helper::now();
        }

        $region->order = isset($region->order) ? $region->order : $order;
        $this->dao->insert(TABLE_KANBANREGION)->data($region)
            ->batchCheck($this->config->kanban->require->createregion, 'notempty')
            ->check('name', 'unique', "kanban = {$kanban->id} AND deleted = '0' AND space='$region->space'")
            ->autoCheck()
            ->exec();

        $regionID = $this->dao->lastInsertID();
        if(dao::isError()) return false;

        $this->loadModel('action')->create('kanbanRegion', $regionID, 'Created');

        if($copyRegionID)
        {
            $this->copyRegion($kanban, $regionID, $copyRegionID, $from, $param);
        }
        elseif($from == 'kanban')
        {
            $groupID = $this->createGroup((int)$kanban->id, $regionID);
            if(dao::isError()) return false;

            $this->createDefaultLane($regionID, $groupID);
            if(dao::isError()) return false;

            $this->createDefaultColumns($regionID, $groupID);
            if(dao::isError()) return false;
        }

        return $regionID;
    }

    /**
     * 复制区域。
     * Copy a region.
     *
     * @param  object $kanban
     * @param  int    $regionID
     * @param  int    $copyRegionID
     * @param  string $from
     * @param  string $param
     * @access public
     * @return void
     */
    public function copyRegion(object $kanban, int $regionID, int $copyRegionID, string $from = 'kanban', string $param = '')
    {
        /* Gets the groups, lanes and columns of the replication region. */
        $copyGroups      = $this->getGroupGroupByRegions((array)$copyRegionID);
        $copyLaneGroup   = $this->getLaneGroupByRegions((array)$copyRegionID);
        $copyColumnGroup = $this->getColumnGroupByRegions((array)$copyRegionID, 'id_asc', $param);

        /* Create groups, lanes, and columns. */
        if(empty($copyGroups)) return $regionID;

        foreach($copyGroups[$copyRegionID] as $copyGroupID => $copyGroup)
        {
            $newGroupID = $this->createGroup((int)$kanban->id, $regionID);
            if(dao::isError()) return false;

            $copyLanes     = isset($copyLaneGroup[$copyGroupID]) ? $copyLaneGroup[$copyGroupID] : array();
            $copyColumns   = isset($copyColumnGroup[$copyGroupID]) ? $copyColumnGroup[$copyGroupID] : array();

            $this->copyColumns($copyColumns, $regionID, $newGroupID, $from);
            $lanePairs = $this->copyLanes($kanban, $copyLanes, $regionID, $newGroupID);
            if(dao::isError()) return false;

            if($param == 'updateTaskCell')
            {
                foreach($lanePairs as $oldLaneID => $newLaneID)
                {
                    $cards = $this->dao->select('id,cards')->from(TABLE_KANBANCELL)->where('lane')->eq($oldLaneID)->andWhere('type')->eq('task')->fetchPairs();
                    $cards = implode(',', $cards);
                    $cards = preg_replace('/[,]+/', ',',$cards);
                    $cards = trim($cards, ',');

                    $group      = $this->dao->select('`group`')->from(TABLE_KANBANLANE)->where('id')->eq($newLaneID)->fetch();
                    $waitColumn = $this->dao->select('id')->from(TABLE_KANBANCOLUMN)->where('type')->eq('wait')->andWhere('`group`')->eq($group->group)->fetch();

                    if(!empty($waitColumn)) $this->addKanbanCell($kanban->id, $newLaneID, $waitColumn->id, 'task', $cards);
                }
            }
        }
    }

    /**
     * 复制区域中的泳道。
     * Copy lanes.
     *
     * @param  object       $kanban
     * @param  array        $copyLanes
     * @param  int          $regionID
     * @param  int          $newGroupID
     * @access public
     * @return array|false
     */
    public function copyLanes(object $kanban, array $copyLanes, int $regionID, int $newGroupID): array|false
    {
        $lanePairs = array();
        foreach($copyLanes as $copyLane)
        {
            if(is_array($copyLane)) $copyLane = (object)$copyLane;

            $laneID = $copyLane->id;
            $copyLane->name = $copyLane->title;

            unset($copyLane->id);
            unset($copyLane->actionList);
            unset($copyLane->title);

            $copyLane->region         = $regionID;
            $copyLane->group          = $newGroupID;
            $copyLane->lastEditedTime = helper::now();
            $lanePairs[$laneID] = $this->createLane((int)$kanban->id, $regionID, $copyLane, 'copy');
            if(dao::isError()) return false;
        }

        return $lanePairs;
    }

    /**
     * 复制区域中的列。
     * Copy columns.
     *
     * @param  array  $copyColumns
     * @param  int    $regionID
     * @param  int    $newGroupID
     * @param  string $from
     * @access public
     * @return void
     */
    public function copyColumns(array $copyColumns, int $regionID, int $newGroupID, string $from = 'kanban')
    {
        $parentColumns = array();
        foreach($copyColumns as $copyColumn)
        {
            if(is_array($copyColumn)) $copyColumn = (object)$copyColumn;

            $copyColumnID = $copyColumn->id;
            $copyColumn->name = $copyColumn->title;

            unset($copyColumn->id);
            unset($copyColumn->title);
            unset($copyColumn->actionList);
            unset($copyColumn->asParent);
            unset($copyColumn->parentName);

            $copyColumn->region = $regionID;
            $copyColumn->group  = $newGroupID;

            if($copyColumn->parent > 0 and isset($parentColumns[$copyColumn->parent]))
            {
                $copyColumn->parent = $parentColumns[$copyColumn->parent];
            }

            $parentColumnID = $this->createColumn($regionID, $copyColumn, $from, 'copy');

            if($copyColumn->parent < 0) $parentColumns[$copyColumnID] = $parentColumnID;
            if(dao::isError()) return false;
        }
    }


    /**
     * 创建默认泳道。
     * Create default lane.
     *
     * @param  int    $regionID
     * @param  int    $groupID
     * @access public
     * @return int
     */
    public function createDefaultLane(int $regionID, int $groupID)
    {
        $lane = new stdclass();
        $lane->name           = $this->lang->kanbanlane->default;
        $lane->group          = $groupID;
        $lane->region         = $regionID;
        $lane->type           = 'common';
        $lane->lastEditedTime = helper::now();
        $lane->color          = '#7ec5ff';
        $lane->order          = 1;
        $lane->groupby        = '';
        $lane->extra          = '';

        $this->dao->insert(TABLE_KANBANLANE)->data($lane)->exec();
        $laneID = $this->dao->lastInsertId();

        return $laneID;
    }

    /**
     * 创建默认看板列。
     * Create default kanban columns.
     *
     * @param  int    $regionID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function createDefaultColumns(int $regionID, int $groupID)
    {
        $order = 1;
        foreach($this->lang->kanban->defaultColumn as $columnName)
        {
            $column = new stdclass();
            $column->region = $regionID;
            $column->group  = $groupID;
            $column->name   = $columnName;
            $column->order  = $order;
            $column->limit  = -1;
            $column->color  = '#333';
            $column->type   = '';

            $this->createColumn($regionID, $column, 'kanban', 'copy');
            $order ++;
        }

        return !dao::isError();
    }

    /**
     * 创建看板列。
     * Create a column.
     *
     * @param  int       $regionID
     * @param  object    $column
     * @param  string    $from kanban|execution
     * @param  string    $mode new|copy
     * @access public
     * @return int|false
     */
    public function createColumn(int $regionID, object $column = null, string $from = 'kanban', string $mode = 'new'): int|false
    {
        if($mode == 'new')
        {
            if(!$column->limit && empty($column->noLimit)) dao::$errors['limit'][] = sprintf($this->lang->error->notempty, $this->lang->kanban->WIP);
            if(!preg_match("/^-?\d+$/", (string)$column->limit) or (!isset($column->noLimit) and $column->limit <= 0)) dao::$errors['limit'] = $this->lang->kanban->error->mustBeInt;
            if(dao::isError()) return false;
        }

        $limit = $column->limit;
        if(isset($column->parent) and $column->parent > 0)
        {
            /* Create a child column. */
            $parentColumn = $this->getColumnByID((int)$column->parent);
            if($parentColumn->limit != -1)
            {
                /* The WIP of the child column is infinite or greater than the WIP of the parent column. */
                $sumChildLimit = $this->dao->select('SUM(`limit`) AS sumChildLimit')->from(TABLE_KANBANCOLUMN)->where('parent')->eq($column->parent)->andWhere('deleted')->eq(0)->fetch('sumChildLimit');
                if($limit == -1 or (((int)$limit + (int)$sumChildLimit) > $parentColumn->limit)) dao::$errors['limit'][] = $this->lang->kanban->error->parentLimitNote;
                if(dao::isError()) return false;
            }
        }

        if($mode == 'new' && $column->order)
        {
            $this->dao->update(TABLE_KANBANCOLUMN)
                ->set('`order` = `order` + 1')
                ->where('`group`')->eq($column->group)
                ->andWhere('`order`')->ge($column->order)
                ->exec();
        }

        $this->dao->insert(TABLE_KANBANCOLUMN)->data($column, 'noLimit,position,copyItems')
            ->batchCheck($this->config->kanban->require->createcolumn, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $columnID = $this->dao->lastInsertID();
        $this->loadModel('action')->create('kanbanColumn', $columnID, 'Created');
        if($from == 'kanban') $this->dao->update(TABLE_KANBANCOLUMN)->set('type')->eq("column{$columnID}")->where('id')->eq($columnID)->exec();

        /* Add kanban cell. */
        $lanes    = $this->dao->select('id,type')->from(TABLE_KANBANLANE)->where('`group`')->eq($column->group)->fetchPairs();
        $kanbanID = $this->dao->select('kanban')->from(TABLE_KANBANREGION)->where('id')->eq($regionID)->fetch('kanban');
        foreach($lanes as $laneID => $laneType) $this->addKanbanCell((int)$kanbanID, $laneID, $columnID, $laneType);

        return $columnID;
    }

    /**
     * 拆分看板列。
     * Split column.
     *
     * @param  int    $columnID
     * @param  array  $columns
     * @access public
     * @return void
     */
    public function splitColumn(int $columnID, array $columns)
    {
        $this->loadModel('action');
        $column        = $this->getColumnByID($columnID);
        $maxOrder      = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANCOLUMN)->where('`group`')->eq($column->group)->fetch('maxOrder');
        $order         = $maxOrder ? $maxOrder + 1 : 1;
        $sumChildLimit = 0;

        $childrenColumn = array();
        foreach($columns as $childColumn)
        {
            $childColumn->parent = $column->id;
            $childColumn->region = $column->region;
            $childColumn->group  = $column->group;
            $childColumn->limit  = $childColumn->noLimit == -1 ? -1 : $childColumn->limit;
            $childColumn->order  = $order;

            $sumChildLimit += $childColumn->limit == -1 ? 0 : $childColumn->limit;
            if(!$this->checkChildColumn($column, $childColumn, $sumChildLimit)) return false;

            $order ++;
            $childrenColumn[] = $childColumn;
        }

        foreach($childrenColumn as $i => $childColumn)
        {
            $this->dao->insert(TABLE_KANBANCOLUMN)->data($childColumn, 'noLimit')
                ->autoCheck()
                ->batchCheck($this->config->kanban->splitcolumn->requiredFields, 'notempty')
                ->exec();

            if(dao::isError()) return false;

            $childColumnID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_KANBANCOLUMN)->set('type')->eq("column{$childColumnID}")->where('id')->eq($childColumnID)->exec();
            $this->action->create('kanbanColumn', $childColumnID, 'created');

            $this->kanbanTao->addChildColumnCell($columnID, $childColumnID, $i);
        }

        $this->dao->update(TABLE_KANBANCOLUMN)->set('parent')->eq(-1)->where('id')->eq($columnID)->exec();
    }

    /**
     * 检查看板列信息是否合法。
     * Check the column information is legal.
     *
     * @param  object $column
     * @param  object $childColumn
     * @param  int    $sumChildLimit
     * @access public
     * @return bool
     */
    public function checkChildColumn(object $column, object $childColumn, int $sumChildLimit): bool
    {
        if(empty($childColumn->name))
        {
            dao::$errors['name'] = sprintf($this->lang->error->notempty, $this->lang->kanbancolumn->name);
            return false;
        }

        if(!preg_match("/^-?\d+$/", (string)$childColumn->limit) or (!$childColumn->noLimit and $childColumn->limit <= 0))
        {
            dao::$errors['limit'] = $this->lang->kanban->error->mustBeInt;
            return false;
        }

        if($column->limit != -1 and ($childColumn->limit == -1 or ($column->limit < $sumChildLimit)))
        {
            dao::$errors['limit'] = $this->lang->kanban->error->parentLimitNote;
            return false;
        }

        return true;
    }

    /**
     * 创建看板卡片。
     * Create a kanban card.
     *
     * @param  int      $columnID
     * @param  object   $card
     * @access public
     * @return bool|int
     */
    public function createCard(int $columnID, object $card): bool|int
    {
        if($card->estimate < 0)
        {
            dao::$errors['estimate'] = $this->lang->kanbancard->error->recordMinus;
            return false;
        }

        if($card->end && $card->begin > $card->end)
        {
            dao::$errors['end'] = $this->lang->kanbancard->error->endSmall;
            return false;
        }

        $card = $this->loadModel('file')->processImgURL($card, $this->config->kanban->editor->createcard['id'], $this->post->uid);

        $this->dao->insert(TABLE_KANBANCARD)->data($card)->autoCheck()
            ->checkIF($card->estimate != '', 'estimate', 'float')
            ->batchCheck($this->config->kanban->createcard->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        $cardID = $this->dao->lastInsertID();
        $this->file->saveUpload('kanbancard', $cardID);
        $this->file->updateObjectID($this->post->uid, $cardID, 'kanbancard');
        $this->addKanbanCell((int)$card->kanban, (int)$this->post->lane, $columnID, 'common', (string)$cardID);

        return $cardID;
    }

    /**
     * 转入其它看板的卡片。
     * Import card.
     *
     * @param  int $kanbanID
     * @param  int $regionID
     * @param  int $groupID
     * @param  int $columnID
     * @access public
     * @return array|false
     */
    public function importCard(int $kanbanID, int $regionID, int $groupID, int $columnID): array|false
    {
        $importIDList = $this->post->cards;
        $targetLaneID = $this->post->targetLane;

        if(!$importIDList || !$targetLaneID) return false;

        $updateData = new stdclass();
        $updateData->kanban = $kanbanID;
        $updateData->region = $regionID;
        $updateData->group  = $groupID;
        $this->dao->update(TABLE_KANBANCARD)->data($updateData)->where('id')->in($importIDList)->exec();

        $kanban         = $this->getByID($kanbanID);
        $oldCardsKanban = array();
        $kanbanUsers    = trim($kanban->owner) . ',' . trim($kanban->team);
        $users          = $this->loadModel('user')->getPairs('noclosed|nodeleted', '', 0, $kanbanUsers);

        $cardList = $this->dao->select('*')->from(TABLE_KANBANCARD)->where('id')->in($importIDList)->fetchAll('id');
        foreach($cardList as $cardID => $card)
        {
            $oldCardsKanban[$cardID] = $card->kanban;
            if(empty($card->assignedTo)) continue;
            $this->kanbanTao->updateCardAssignedTo($cardID, $card->assignedTo, $users);
        }

        if(!dao::isError())
        {
            $this->removeKanbanCell('common', $importIDList, $oldCardsKanban);

            $cards = implode(',', $importIDList);
            $this->addKanbanCell($kanbanID, (int)$targetLaneID, $columnID, 'common', $cards);

            return $importIDList;
        }

        return false;
    }

    /**
     * 转入其它对象作为看板卡片
     * Import object.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @param  string $objectType
     * @access public
     * @return array|false
     */
    public function importObject(int $kanbanID, int $regionID, int $groupID, int $columnID, string $objectType): array|false
    {
        $objectIDList = $this->post->{$objectType . 's'};
        $targetLaneID = $this->post->targetLane;

        $objectCards = array();
        $now         = helper::now();
        foreach($objectIDList as $objectID)
        {
            $cardData = new stdclass();
            $cardData->kanban      = $kanbanID;
            $cardData->region      = $regionID;
            $cardData->group       = $groupID;
            $cardData->fromID      = $objectID;
            $cardData->fromType    = $objectType;
            $cardData->createdBy   = $this->app->user->account;
            $cardData->createdDate = $now;
            $this->dao->insert(TABLE_KANBANCARD)->data($cardData)->exec();

            $cardID = $this->dao->lastInsertID();
            $objectCards[$cardID] = $objectID;
        }

        if(!dao::isError())
        {
            $cards = implode(',', array_keys($objectCards));
            $this->addKanbanCell($kanbanID, (int)$targetLaneID, $columnID, 'common', $cards);

            return $objectCards;
        }

        return false;
    }

    /**
     * 批量创建卡片。
     * Batch create kanban cards.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @param  array  $cards
     * @access public
     * @return void
     */
    public function batchCreateCard(int $kanbanID, int $regionID, int $groupID, int $columnID, array $cards)
    {
        $now = helper::now();
        foreach($cards as $i => $card)
        {
            if($card->estimate < 0)
            {
                dao::$errors["estimate[$i]"] = $this->lang->kanbancard->error->recordMinus;
                return false;
            }
            if($card->end && $card->begin > $card->end)
            {
                dao::$errors["end[$i]"] = $this->lang->kanbancard->error->endSmall;
                return false;
            }
        }

        foreach($cards as $card)
        {
            $card->kanban       = $kanbanID;
            $card->region       = $regionID;
            $card->group        = $groupID;
            $card->createdBy    = $this->app->user->account;
            $card->createdDate  = $now;
            $card->assignedDate = $now;
            $card->color        = '#fff';

            $this->dao->insert(TABLE_KANBANCARD)->data($card, 'lane')->autoCheck()
                ->checkIF($card->estimate != '', 'estimate', 'float')
                ->batchCheck($this->config->kanban->createcard->requiredFields, 'notempty')
                ->exec();

            if(!dao::isError())
            {
                $cardID = $this->dao->lastInsertID();
                $this->addKanbanCell($kanbanID, $card->lane, $columnID, 'common', (string)$cardID);
                $this->loadModel('action')->create('kanbancard', $cardID, 'created');
            }
        }
    }

    /**
     * 获取看板数据。
     * Get kanban by id.
     *
     * @param  int    $kanbanID
     * @access public
     * @return object
     */
    public function getByID(int $kanbanID): object
    {
        $kanban = $this->dao->findByID($kanbanID)->from(TABLE_KANBAN)->fetch();
        $kanban = $this->loadModel('file')->replaceImgURL($kanban, 'desc');

        return $kanban;
    }

    /**
     * 获取看板键值对。
     * Get kanban pairs.
     *
     * @access public
     * @return array
     */
    public function getPairs()
    {
        $idList = $this->getCanViewObjects();
        return $this->dao->select('id,name')->from(TABLE_KANBAN)
            ->where('id')->in($idList)
            ->andWhere('deleted')->eq('0')
            ->fetchPairs();
    }

    /**
     * 获取看板所有数据。
     * Get kanban data.
     *
     * @param  int    $kanbanID
     * @param  mixed  $regionIDList
     * @access public
     * @return array
     */
    public function getKanbanData(int $kanbanID, mixed $regionIDList = ''): array
    {
        $regions = $this->getRegionPairs($kanbanID);

        if(empty($regionIDList))
        {
            $regionIDList = array_keys($regions);
        }
        elseif(!is_array($regionIDList))
        {
            $regionIDList = array($regionIDList);
        }

        $groupGroup  = $this->getGroupGroupByRegions($regionIDList);
        $laneGroup   = $this->getLaneGroupByRegions($regionIDList);
        $columnGroup = $this->getColumnGroupByRegions($regionIDList);
        $cardGroup   = $this->getCardGroupByKanban($kanbanID);

        $kanbanList = array();
        foreach($regionIDList as $regionID)
        {
            $regionData = array();

            $heading = new stdclass();
            $heading->title   = zget($regions, $regionID, '');
            $heading->actions = $this->getRegionActions($kanbanID, $regionID);

            $regionData['key']               = "region{$regionID}";
            $regionData['id']                = $regionID;
            $regionData['heading']           = $heading;
            $regionData['toggleFromHeading'] = true;

            $groups = zget($groupGroup, $regionID, array());
            $kanbanList[] = $this->kanbanTao->buildRegionData($regionData, $groups, $laneGroup, $columnGroup, $cardGroup);
        }

        return $kanbanList;
    }

    /**
     * 获取看板区域上的操作按钮。
     * Get region actions.
     *
     * @param  int        $kanbanID
     * @param  int|string $regionID
     * @access public
     * @return array
     */
    public function getRegionActions(int $kanbanID, int|string $regionID): array
    {
        $action  = array();
        $actions = array();

        $action['type']  = 'dropdown';
        $action['icon']  = 'ellipsis-v';
        $action['caret'] = false;
        $action['items'] = array();

        if(common::hasPriv('kanban', 'createRegion')) $action['items'][] = array('text' => $this->lang->kanban->createRegion, 'url' => helper::createLink('kanban', 'createRegion', "kanbanID=$kanbanID"), 'data-toggle' => 'modal', 'icon' => 'plus');
        if(common::hasPriv('kanban', 'editRegion'))   $action['items'][] = array('text' => $this->lang->kanban->editRegion,   'url' => helper::createLink('kanban', 'editRegion', "regionID=$regionID"), 'data-toggle' => 'modal', 'icon' => 'edit');
        if(common::hasPriv('kanban', 'sortRegion'))   $action['items'][] = array('text' => $this->lang->kanban->sortRegion,   'url' => 'javascript:;', 'icon' => 'move', 'data-on' => 'click', 'data-call' => 'sortItems', 'data-params' => 'event', 'data-type' => 'region', 'data-id' => $regionID);
        if(common::hasPriv('kanban', 'createLane'))   $action['items'][] = array('text' => $this->lang->kanban->createLane,   'url' => helper::createLink('kanban', 'createLane', "kanbanID=$kanbanID&regionID=$regionID"), 'data-toggle' => 'modal', 'icon' => 'plus');
        if(common::hasPriv('kanban', 'deleteRegion')) $action['items'][] = array('text' => $this->lang->kanban->deleteRegion, 'url' => helper::createLink('kanban', 'deleteRegion', "regionID=$regionID"), 'data-confirm' => $this->lang->kanbanregion->confirmDelete, 'icon' => 'trash', 'innerClass' => 'ajax-submit');

        $action['items'][] = array('type' => 'divider');

        if(commonModel::hasPriv('kanban', 'viewArchivedCard'))   $action['items'][] = array('text' => $this->lang->kanban->viewArchivedCard,   'url' => "javascript:loadMore(\"Card\", $regionID)", 'icon' => 'card-archive');
        if(commonModel::hasPriv('kanban', 'viewArchivedColumn')) $action['items'][] = array('text' => $this->lang->kanban->viewArchivedColumn, 'url' => "javascript:loadMore(\"Column\", $regionID)", 'icon' => 'col-archive');

        $actions[] = $action;

        return $actions;
    }

    /**
     * 获取专业研发看板区域上的操作按钮。
     * Get RD region actions.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @access public
     * @return array
     */
    public function getRDRegionActions(int $kanbanID, int $regionID): array
    {
        $action  = array();
        $actions = array();

        $action['type']  = 'dropdown';
        $action['icon']  = 'ellipsis-v';
        $action['caret'] = false;
        $action['items'] = array();

        if(common::hasPriv('kanban', 'createRegion')) $action['items'][] = array('text' => $this->lang->kanban->createRegion, 'url' => helper::createLink('kanban', 'createRegion', "kanbanID=$kanbanID"), 'data-toggle' => 'modal', 'icon' => 'plus');
        if(common::hasPriv('kanban', 'editRegion'))   $action['items'][] = array('text' => $this->lang->kanban->editRegion,   'url' => helper::createLink('kanban', 'editRegion', "regionID=$regionID"), 'data-toggle' => 'modal', 'icon' => 'edit');
        if(common::hasPriv('kanban', 'createLane'))   $action['items'][] = array('text' => $this->lang->kanban->createLane,   'url' => helper::createLink('kanban', 'createLane', "kanbanID=$kanbanID&regionID=$regionID"), 'data-toggle' => 'modal', 'icon' => 'plus');

        $actions[] = $action;
        return $actions;
    }

    /**
     * 获取计划看板。
     * Get plan kanban.
     *
     * @param  object $product
     * @param  string $branchID
     * @param  array  $planGroup
     * @access public
     * @return array
     */
    public function getPlanKanban(object $product, string $branchID = '0', array $planGroup = array()): array
    {
        $this->loadModel('productplan');

        $lanes = $columns = $kanbanData = $planList = $lanes = $columns = $columnCards = array();
        $colorIndex = 0;
        $laneOrder  = 1;

        $cardActions = array('view', 'createExecution', 'linkStory', 'linkBug', 'edit', 'start', 'finish', 'close', 'activate', 'delete');
        $branches    = $this->kanbanTao->getBranchesForPlanKanban($product, $branchID);
        foreach($branches as $id => $name)
        {
            if($product->type != 'normal') $plans = isset($planGroup[$product->id][$id]) ? array_filter($planGroup[$product->id][$id]) : array();
            if($product->type == 'normal') $plans = $planGroup;

            foreach($plans as $plan)
            {
                if(empty($plan) or $plan->parent == -1) continue;
                $plan->isParent = false;

                $item = array('id' => $plan->id, 'name' => $plan->id, 'title' => htmlspecialchars_decode($plan->title), 'status' => $plan->status);
                $item['statusLabel'] = zget($this->lang->productplan->statusList, $plan->status);
                $item['delay']       = helper::today() > $plan->end ? true : false;
                $item['desc']        = strip_tags(htmlspecialchars_decode($plan->desc));
                $item['dateLine']    = date('m-d', strtotime($plan->begin)) . ' ' . $this->lang->productplan->to . ' ' . date('m-d', strtotime($plan->end));
                $item['actionList']  = array();
                foreach($cardActions as $action)
                {
                    if($this->productplan->isClickable($plan, $action)) $item['actionList'][] = $action;
                }
                $planList[$id][$plan->status][] = $item;

                if(!isset($columnCards[$plan->status])) $columnCards[$plan->status] = 0;
                $columnCards[$plan->status] ++;
            }

            $lanes[] = array('id' => $id, 'name' => $id, 'title' => $name, 'color' => $this->config->productplan->laneColorList[$colorIndex], 'order' => $laneOrder);
            $laneOrder ++;
            $colorIndex ++;
            if($colorIndex == count($this->config->productplan->laneColorList)) $colorIndex = 0;
        }

        foreach($this->lang->kanban->defaultColumn as $columnType => $columnName) $columns[] = array('id' => $columnType, 'name' => $columnType, 'title' => $columnName);
        foreach($columns as $key => $column) $columns[$key]['cards'] = !empty($columnCards[$column['name']]) ? $columnCards[$column['name']] : 0;

        $groupData['key']  = 'planKanban';
        $groupData['data'] = array('lanes' => $lanes, 'cols' => $columns, 'items' => $planList);
        $kanbanData[] = array('items' => array($groupData), 'key' => 'planKanban', 'heading' => array('title' => $this->lang->productplan->all . ' ' . count($planList)));
        return $kanbanData;
    }

    /**
     * 获取专业研发看板。
     * Get a RD kanban data.
     *
     * @param  int    $executionID
     * @param  string $browseType all|story|task|bug
     * @param  string $orderBy
     * @param  int    $regionID
     * @param  string $groupBy
     * @param  string $searchValue
     * @access public
     * @return array
     */
    public function getRDKanban(int $executionID, string $browseType = 'all', string $orderBy = 'id_desc', int $regionID = 0, string $groupBy = 'default', string $searchValue = '')
    {
        $kanbanList = array();
        $execution  = $this->loadModel('execution')->getByID($executionID);

        if($groupBy != 'default' and $groupBy != '') return $this->kanbanTao->getRDKanbanByGroup($execution, $browseType, $orderBy, $regionID, $groupBy, $searchValue);

        $regions      = $this->getRegionPairs($executionID, $regionID, 'execution');
        $regionIDList = $regionID == 0 ? array_keys($regions) : array(0 => $regionID);
        $groupGroup   = $this->getGroupGroupByRegions($regionIDList);
        $laneGroup    = $this->getLaneGroupByRegions($regionIDList, $browseType);

        foreach($laneGroup as $lanes)
        {
            foreach($lanes as $lane)
            {
                $lane['execution'] = $executionID;
                if(in_array($execution->attribute, array('request', 'design', 'review')) and $lane['type'] == 'bug') continue 2;
                if(in_array($execution->attribute, array('request', 'review')) and $lane['type'] == 'story') continue 2;
                $this->refreshCards((array)$lane);
                $lane['defaultCardType'] = $lane['type'];
            }
        }

        $columnGroup = $this->getRDColumnGroupByRegions($regionIDList, array_keys($laneGroup));
        $cardGroup   = $this->getCardGroupByExecution($executionID, $browseType, $orderBy, $searchValue);

        foreach($regions as $regionID => $regionName)
        {
            $regionData = array();

            $heading = new stdclass();
            $heading->title   = $regionName;
            $heading->actions = $this->getRDRegionActions($executionID, $regionID);

            $regionData['key']               = "region{$regionID}";
            $regionData['id']                = $regionID;
            $regionData['heading']           = $heading;
            $regionData['toggleFromHeading'] = true;

            $groups = zget($groupGroup, $regionID, array());

            $regionData = $this->kanbanTao->buildRDRegionData($regionData, $groups, $laneGroup, $columnGroup, $cardGroup, $searchValue);

            if(!empty($regionData['items'])) $kanbanList[] = $regionData;
        }

        return $kanbanList;
    }

    /**
     * 获取看板区域。
     * Get region by id.
     *
     * @param  int    $regionID
     * @access public
     * @return object
     */
    public function getRegionByID(int $regionID)
    {
        return $this->dao->findByID($regionID)->from(TABLE_KANBANREGION)->fetch();
    }

    /**
     * 获取看板区域的键值对。
     * Get ordered region pairs.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  string $from kanban|execution
     * @access public
     * @return array
     */
    public function getRegionPairs(int $kanbanID, int $regionID = 0, string $from = 'kanban'): array
    {
        return $this->dao->select('id,name')->from(TABLE_KANBANREGION)
            ->where('kanban')->eq($kanbanID)
            ->andWhere('deleted')->eq('0')
            ->beginIF($regionID)->andWhere('id')->eq($regionID)->fi()
            ->beginIF($from == 'execution')->andWhere('space')->eq(0)->fi()
            ->beginIF($from == 'kanban')->andWhere('space')->ne(0)->fi()
            ->orderBy('order_asc')
            ->fetchPairs();
    }

    /**
     * 根据区域获取看板ID。
     * Get kanban id by region id.
     *
     * @param  int    $regionID
     * @access public
     * @return int
     */
    public function getKanbanIDByRegion(int $regionID): int
    {
        return (int)$this->dao->select('kanban')->from(TABLE_KANBANREGION)->where('id')->eq($regionID)->fetch('kanban');
    }

    /**
     * 分组获取看板组。
     * Get kanban group by regions.
     *
     * @param  array $regions
     * @access public
     * @return array
     */
    public function getGroupGroupByRegions(array $regions): array
    {
        return $this->dao->select('*')->from(TABLE_KANBANGROUP)
            ->where('region')->in($regions)
            ->orderBy('order')
            ->fetchGroup('region', 'id');
    }

    /**
     * 获取区域中的泳道分组。
     * Get lane group by regions.
     *
     * @param  array  $regions
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getLaneGroupByRegions(array $regions, string $browseType = 'all'): array
    {
        $lanes = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regions)
            ->beginIf($browseType != 'all')->andWhere('type')->eq($browseType)->fi()
            ->orderBy('order')
            ->fetchAll();

        $actions = array('sortLane', 'deleteLane', 'editLaneName', 'editLaneColor');
        $laneGroup = array();
        foreach($lanes as $lane)
        {
            $item = array();
            $item['id']     = $lane->id;
            $item['type']   = $lane->type;
            $item['name']   = $lane->id;
            $item['region'] = $lane->region;
            $item['title']  = htmlspecialchars_decode($lane->name);
            $item['color']  = $lane->color;
            $item['order']  = $lane->order;

            foreach($actions as $action)
            {
                if($this->isClickable($lane, $action)) $item['actionList'][] = $action;
            }

            $laneGroup[$lane->group][] = $item;
        }

        return $laneGroup;
    }

    /**
     * 根据分组ID获取泳道键值对。
     * Get kanban lane pairs by group id.
     *
     * @param  int    $groupID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getLanePairsByGroup(int $groupID, string $orderBy = '`order`_asc'): array
    {
        return $this->dao->select('id,name')->from(TABLE_KANBANLANE)
            ->where('deleted')->eq(0)
            ->andWhere('`group`')->eq($groupID)
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * 根据分组ID获取看板列键值对。
     * Get kanban column pairs by group id.
     *
     * @param  int    $groupID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getColumnPairsByGroup(int $groupID, string $orderBy = '`order`_asc'): array
    {
        return $this->dao->select('id,name')->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq(0)
            ->andWhere('`group`')->eq($groupID)
            ->orderBy($orderBy)
            ->fetchPairs();
    }

    /**
     * Get column group by regions.
     *
     * @param  array  $regions
     * @param  string $order order|id_asc
     * @param  string $param
     * @access public
     * @return array
     */
    public function getColumnGroupByRegions(array $regions, string $order = '`order`', string $param = ''): array
    {
        $columns = $this->dao->select("*")->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regions)
            ->beginIF(strpos(",$param,", ',withArchived,') === false)->andWhere('archived')->eq('0')->fi()
            ->orderBy($order)
            ->fetchAll();

        $actions = array('createColumn', 'setColumn', 'setWIP', 'sortColumn', 'archiveColumn', 'restoreColumn', 'deleteColumn', 'createCard', 'batchCreateCard', 'splitColumn', 'sortColumn');

        /* Group by parent. */
        $columnGroup = array();
        foreach($columns as $column)
        {
            $item = array();
            $item['title']  = htmlspecialchars_decode($column->name);
            $item['name']   = $column->id;
            $item['id']     = $column->id;
            $item['type']   = $column->type;
            $item['color']  = $column->color;
            $item['limit']  = $column->limit;
            $item['region'] = $column->region;
            $item['group']  = $column->group;
            $item['parent'] = $column->parent;
            $item['color']  = $column->color;
            $item['order']  = $column->order;
            if($column->parent > 0) $item['parentName'] = $column->parent;

            /* Judge column action priv. */
            foreach($actions as $action)
            {
                if($this->isClickable($column, $action)) $item['actionList'][] = $action;
            }

            $columnGroup[$column->group][] = $item;
        }

        return $columnGroup;
    }

    /**
     * 获取看板下的卡片。
     * Get card group by kanban id.
     *
     * @param  int    $kanbanID
     * @access public
     * @return array
     */
    public function getCardGroupByKanban(int $kanbanID): array
    {
        /* Get card data.*/
        $cards = $this->dao->select('*')->from(TABLE_KANBANCARD)
            ->where('deleted')->eq(0)
            ->andWhere('kanban')->eq($kanbanID)
            ->andWhere('archived')->eq(0)
            ->andWhere('fromID')->eq(0)
            ->fetchAll('id');

        foreach($this->config->kanban->fromType as $fromType) $cards = $this->getImportedCards($kanbanID, $cards, $fromType);

        $cellList = $this->dao->select('*')->from(TABLE_KANBANCELL)
            ->where('kanban')->eq($kanbanID)
            ->andWhere('type')->eq('common')
            ->fetchAll();

        $actions     = array('editCard', 'archiveCard', 'deleteCard', 'moveCard', 'setCardColor', 'viewCard', 'sortCard', 'viewExecution', 'viewPlan', 'viewRelease', 'viewBuild', 'viewTicket', 'activateCard', 'finishCard');
        $cardGroup   = array();
        $avatarPairs = $this->loadModel('user')->getAvatarPairs();
        $users       = $this->loadModel('user')->getPairs('noletter');
        foreach($cellList as $cell)
        {
            $cardIdList = array_filter(explode(',', $cell->cards));
            if(empty($cardIdList)) continue;

            $order = 0;
            foreach($cardIdList as $cardID)
            {
                if(!isset($cards[$cardID])) continue;

                $card = zget($cards, $cardID);
                $item = $this->kanbanTao->initCardItem($card, $cell, $order, $avatarPairs, $users);

                $order ++;

                foreach($actions as $action)
                {
                    if(in_array($action, array('viewExecution', 'viewPlan', 'viewRelease', 'viewBuild', 'viewTicket')))
                    {
                        if($card->fromType == 'execution')
                        {
                            if($card->execType == 'kanban' and common::hasPriv('execution', 'kanban')) $item['actionList'][] = $action;
                            if($card->execType != 'kanban' and common::hasPriv('execution', 'view'))   $item['actionList'][] = $action;
                        }
                        else
                        {
                            if(common::hasPriv($fromType, 'view')) $item['actionList'][] = $action;
                        }
                        continue;
                    }
                    if(common::hasPriv('kanban', $action)) $item['actionList'][] = $action;
                }

                $cardGroup[$card->group][$cell->lane][$cell->column][] = $item;
            }
        }

        return $cardGroup;
    }

    /**
     * 获取已经导入的卡片。
     * Get imported cards.
     *
     * @param  int    $kanbanID
     * @param  array  $cards
     * @param  array  $fromType
     * @param  int    $archived
     * @param  int    $regionID
     * @access public
     * @return array
     */
    public function getImportedCards(int $kanbanID, array $cards, string $fromType, int $archived = 0, int $regionID = 0): array
    {
        /* Get imported cards based on imported object type. */
        $objectCards = $this->dao->select('*')->from(TABLE_KANBANCARD)
            ->where('deleted')->eq(0)
            ->andWhere('kanban')->eq($kanbanID)
            ->andWhere('archived')->eq($archived)
            ->andWhere('fromType')->eq($fromType)
            ->beginIF($regionID)->andWhere('region')->eq($regionID)->fi()
            ->fetchGroup('fromID', 'id');

        if(!empty($objectCards))
        {
            /* Get imported objects. */
            $table   = $this->config->objectTables[$fromType];
            $objects = $this->dao->select('*')->from($table)
                ->where('id')->in(array_keys($objectCards))
                ->fetchAll('id');

            $creators = array();
            if($fromType == 'productplan' or $fromType == 'release')
            {
                $creators = $this->dao->select('objectID, actor')->from(TABLE_ACTION)
                    ->where('objectID')->in(array_keys($objectCards))
                    ->andWhere('objectType')->eq($fromType)
                    ->andWhere('action')->eq('opened')
                    ->fetchPairs();
            }

            /* Data for constructing the card. */
            foreach($objectCards as $objectID => $cardsInfo)
            {
                foreach($cardsInfo as $cardID => $objectCard)
                {
                    $object    = $objects[$objectID];
                    $fieldType = $fromType . 'Field';

                    foreach($this->config->kanban->$fieldType as $field) $objectCard->$field = $object->$field;

                    $objectCard = $this->kanbanTao->buildObjectCard($objectCard, $object, $fromType, $creators);
                    $cards[$cardID] = $objectCard;
                }
            }
        }
        return $cards;
    }

    /**
     * 获取专业研发看板的列。
     * Get RD column group by regions.
     *
     * @param  array  $regions
     * @param  array  $groupIDList
     * @access public
     * @return array
     */
    public function getRDColumnGroupByRegions(array $regions, array $groupIDList = array())
    {
        $columnGroup = $this->dao->select("*")->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regions)
            ->beginIF(!empty($groupIDList))->andWhere('`group`')->in($groupIDList)->fi()
            ->orderBy('id_asc')
            ->fetchGroup('group');

        $actions = array('setColumn', 'setWIP', 'deleteColumn');

        /* Group by parent. */
        $columnData = array();
        foreach($columnGroup as $group => $columns)
        {
            foreach($columns as $column)
            {
                $item = array();
                $item['title']  = htmlspecialchars_decode($column->name);
                $item['name']   = $column->id;
                $item['id']     = $column->id;
                $item['type']   = $column->type;
                $item['color']  = $column->color;
                $item['limit']  = $column->limit;
                $item['region'] = $column->region;
                $item['group']  = $column->group;
                $item['parent'] = $column->parent;
                $item['color']  = $column->color;
                if($column->parent > 0) $item['parentName'] = $column->parent;

                /* Judge column action priv. */
                foreach($actions as $action)
                {
                    if($this->isClickable($column, $action)) $item['actionList'][] = $action;
                }

                $columnData[$group][] = $item;
            }
        }

        return $columnData;
    }

    /**
     * 获取执行中的看板卡片分组。
     * Get card group by execution id.
     *
     * @param  int    $kanbanID
     * @param  string $browseType all|task|bug|story
     * @param  string $orderBy
     * @param  string $searchValue
     *
     * @access public
     * @return array
     */
    public function getCardGroupByExecution(int $executionID, string $browseType = 'all', string $orderBy = 'id_asc', string $searchValue = ''): array
    {
        $cards = $this->dao->select('t1.*, t2.type as columnType, t2.group')
            ->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column=t2.id')
            ->where('t1.kanban')->eq($executionID)
            ->beginIF($browseType != 'all')->andWhere('t1.type')->eq($browseType)->fi()
            ->orderby($orderBy)
            ->fetchgroup('lane', 'column');

        /* Get group objects. */
        if($browseType == 'all' or $browseType == 'story') $objectGroup['story'] = $this->loadModel('story')->getExecutionStories($executionID, 0, 't1.`order`_desc', 'allStory');
        if($browseType == 'all' or $browseType == 'bug')   $objectGroup['bug']   = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($browseType == 'all' or $browseType == 'task')  $objectGroup['task']  = $this->loadModel('execution')->getKanbanTasks($executionID, "id");

        $cardGroup = array();

        $avatarPairs = $this->loadModel('user')->getAvatarPairs();
        $users       = $this->loadModel('user')->getPairs('noletter');
        foreach($cards as $laneID => $cells)
        {
            foreach($cells as $cell)
            {
                $order = 0;
                $cardIdList = array_filter(explode(',', $cell->cards));
                foreach($cardIdList as $cardID)
                {
                    $cardData = array();
                    $objects  = zget($objectGroup, $cell->type, array());
                    $object   = zget($objects, $cardID, array());

                    if(empty($object)) continue;

                    $cardData = $this->kanbanTao->initCardItem($object, $cell, $order, $avatarPairs, $users);
                    $cardData['acl'] = 'open';
                    $order ++;

                    if($searchValue != '' and strpos($cardData['title'], $searchValue) === false) continue;
                    $cardGroup[$cell->group][$laneID][$cell->column][] = $cardData;
                }
            }
        }

        return $cardGroup;
    }

    /**
     * 获取执行看板的数据。
     * Get Kanban by execution id.
     *
     * @param  int    $executionID
     * @param  string $browseType  all|story|bug|task
     * @param  string $groupBy     default|pri|category|module|source|assignedTo|type|story|severity
     * @param  string $searchValue
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getExecutionKanban(int $executionID, string $browseType = 'all', string $groupBy = 'default', string $searchValue = '', string $orderBy = 'id_asc'): array
    {
        if($groupBy != 'default') return $this->getKanban4Group($executionID, $browseType, $groupBy, $searchValue, $orderBy);

        $lanes = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($browseType != 'all')->andWhere('type')->eq($browseType)->fi()
            ->orderBy('order_asc')
            ->fetchAll('id');

        if(empty($lanes)) return array();

        foreach($lanes as $lane) $this->refreshCards((array)$lane);

        $columns = $this->dao->select('t1.cards, t1.lane, t2.id, t2.type, t2.name, t2.color, t2.limit, t2.parent')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column = t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.lane')->in(array_keys($lanes))
            ->orderBy('id_asc')
            ->fetchGroup('lane', 'id');

        /* Get group objects. */
        if($browseType == 'all' or $browseType == 'story') $objectGroup['story'] = $this->loadModel('story')->getExecutionStories($executionID, 0, 't1.`order`_desc', 'allStory');
        if($browseType == 'all' or $browseType == 'bug')   $objectGroup['bug']   = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($browseType == 'all' or $browseType == 'task')  $objectGroup['task']  = $this->loadModel('execution')->getKanbanTasks($executionID, "id");

        /* Get objects cards menus. */
        $menus = array();
        $menus['story'] = $browseType == 'all' || $browseType == 'story' ? $this->getKanbanCardMenu($executionID, $objectGroup['story'], 'story') : array();
        $menus['bug']   = $browseType == 'all' || $browseType == 'bug'   ? $this->getKanbanCardMenu($executionID, $objectGroup['bug'], 'bug')     : array();
        $menus['task']  = $browseType == 'all' || $browseType == 'task'  ? $this->getKanbanCardMenu($executionID, $objectGroup['task'], 'task')   : array();

        /* Build kanban group data. */
        $kanbanGroup = array();
        foreach($lanes as $lane)
        {
            list($laneData, $columnData, $cardsData) = $this->buildExecutionGroup($lane, $columns, $objectGroup, $searchValue, $menus);

            if($searchValue != '' && empty($cardsData)) continue;
            $kanbanGroup[$lane->type]['id']   = $lane->id;
            $kanbanGroup[$lane->type]['key']  = 'group' . $lane->id;
            $kanbanGroup[$lane->type]['data'] = array();
            $kanbanGroup[$lane->type]['data']['lanes'] = array($laneData);
            $kanbanGroup[$lane->type]['data']['cols']  = $columnData;
            $kanbanGroup[$lane->type]['data']['items'] = $cardsData;
        }

        return array_values($kanbanGroup);
    }

    /**
     * 构建迭代看板的泳道组数据。
     * Build the laneGroup data for the execution Kanban.
     *
     * @param  object $lane
     * @param  array  $columns
     * @param  array  $objectGroup
     * @param  string $searchValue
     * @param  array  $menus        array(story => array, bug => array, task => array)
     * @access public
     * @return array
     */
    public function buildExecutionGroup(object $lane, array $columns, array $objectGroup, string $searchValue = '', array $menus = array()): array
    {
        $laneData    = array();
        $columnsData = array();
        $cardsData   = array();

        $laneData['id']     = $lane->id;
        $laneData['type']   = $lane->type;
        $laneData['name']   = $lane->id;
        $laneData['region'] = $lane->region;
        $laneData['title']  = $lane->name;
        $laneData['color']  = $lane->color;

        foreach($columns[$lane->id] as $columnID => $column)
        {
            $cardIdList = array_unique(array_filter(explode(',', $column->cards)));

            $columnsData[$column->id]['id']         = $columnID;
            $columnsData[$column->id]['type']       = $column->type;
            $columnsData[$column->id]['name']       = $columnID;
            $columnsData[$column->id]['title']      = $column->name;
            $columnsData[$column->id]['color']      = $column->color;
            $columnsData[$column->id]['limit']      = $column->limit;
            $columnsData[$column->id]['region']     = $lane->region;
            $columnsData[$column->id]['laneName']   = $column->lane;
            $columnsData[$column->id]['group']      = $lane->type;
            $columnsData[$column->id]['cards']      = 0;
            $columnsData[$column->id]['actionList'] = array('setColumn', 'setWIP');

            if($column->parent > 0) $columnsData[$column->id]['parentName'] = $column->parent;
            if($cardIdList)
            {
                $cardsData = $this->buildExecutionCards($cardsData, $column, $lane->type, $cardIdList, $objectGroup, $searchValue, $menus);
                $columnsData[$column->id]['cards'] = empty($cardsData[$column->lane][$column->id]) ? 0 : count($cardsData[$column->lane][$column->id]);
            }
        }

        foreach($columnsData as $columnData)
        {
            if(isset($columnData['parentName'])) $columnsData[$columnData['parentName']]['cards'] += $columnData['cards'];
        }

        return array($laneData, array_values($columnsData), $cardsData);
    }

    /**
     * 构建迭代看板的卡片数据。
     * Build the card data for the execution Kanban.
     *
     * @param  object $lane
     * @param  array  $laneData
     * @param  string $columnType
     * @param  array  $cardIdList
     * @param  array  $objectGroup
     * @param  string $searchValue
     * @param  array  $menus        array(story => array, bug => array, task => array)
     * @access public
     * @return array
     */
    public function buildExecutionCards(array $cardsData, object $column, string $laneType, array $cardIdList, array $objectGroup, string $searchValue = '', array $menus = array()): array
    {
        foreach($cardIdList as $cardID)
        {
            $cardData = array();
            $objects  = zget($objectGroup, $laneType, array());
            $object   = zget($objects, $cardID, array());

            if(empty($object)) continue;
            $cardData = $this->buildExecutionCard($object, $column, $laneType, $searchValue, $menus);
            if(empty($cardData)) continue;

            $cardsData[$column->lane][$column->id][] = $cardData;
        }

        return $cardsData;
    }

    /**
     * 构造执行看板卡片数据。
     * Build execution card.
     *
     * @param  object $object
     * @param  object $column
     * @param  string $laneType
     * @param  string $searchValue
     * @param  array  $menus        array(story => array, bug => array, task => array)
     * @access public
     * @return array
     */
    public function buildExecutionCard(object $object, object $column, string $laneType, string $searchValue = '', $menus = array()): array
    {
        if(empty($object)) return array();

        $cardData = array();
        $cardData['id']         = $object->id;
        $cardData['lane']       = $column->lane;
        $cardData['column']     = $column->id;
        $cardData['pri']        = zget($object, 'pri', 0);
        $cardData['group']      = $laneType;
        $cardData['status']     = zget($object, 'status', '');
        $cardData['estimate']   = zget($object, 'estimate', 0);
        $cardData['assignedTo'] = $object->assignedTo;
        $cardData['deadline']   = zget($object, 'deadline', '');
        $cardData['severity']   = zget($object, 'severity', 0);
        $cardData['actionList'] = zget(zget($menus, $laneType, array()), $object->id, array());

        $cardData['title'] = zget($object, 'title', '');
        if($laneType == 'task')
        {
            $cardData['title']      = $object->name;
            $cardData['status']     = $object->status;
            $cardData['left']       = $object->left;
            $cardData['estStarted'] = $object->estStarted;
            $cardData['mode']       = $object->mode;
            if($object->mode == 'multi') $cardData['teamMembers'] = $object->teamMembers;
        }

        if($searchValue != '' and !str_contains($cardData['title'], $searchValue)) return array();

        return $cardData;
    }

    /**
     * 获取执行看板分组视图的数据。
     * Get kanban for group view.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $groupBy
     * @param  string $searchValue
     * @param  string $orderBy
     *
     * @access public
     * @return array
     */
    public function getKanban4Group(int $executionID, string $browseType, string $groupBy, string $searchValue = '', string $orderBy = 'id_asc'): array
    {
        /* Get card  data. */
        $cardList = array();
        if($browseType == 'story') $cardList = $this->loadModel('story')->getExecutionStories($executionID, 0, 't1.`order`_desc', 'allStory');
        if($browseType == 'bug')   $cardList = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($browseType == 'task')  $cardList = $this->loadModel('execution')->getKanbanTasks($executionID);

        if($browseType == 'task' and $groupBy == 'assignedTo') $cardList = $this->appendTeamMember($cardList);

        /* Get objects cards menus. */
        $menus = array();
        if($browseType == 'story') $menus['story'] = $this->getKanbanCardMenu($executionID, $cardList, 'story');
        if($browseType == 'bug')   $menus['bug']   = $this->getKanbanCardMenu($executionID, $cardList, 'bug');
        if($browseType == 'task')  $menus['task']  = $this->getKanbanCardMenu($executionID, $cardList, 'task');

        if($groupBy == 'story' and $browseType == 'task' and !isset($this->lang->kanban->orderList[$orderBy])) $orderBy = 'id_asc';
        $lanes = $this->getLanes4Group($executionID, $browseType, $groupBy, $cardList, $orderBy);
        if(empty($lanes)) return array();

        $columns = $this->getCols4Group($executionID, $browseType);

        $cardGroup = array();
        foreach($columns as $column)
        {
            if(empty($column->cards)) continue;
            foreach($cardList as $card)
            {
                if(strpos($column->cards, ",$card->id,") !== false) $cardGroup[$column->columnType][$card->id] = $card;
            }
        }

        /* Build kanban group data. */
        $kanbanGroup = array();
        list($laneData, $columnData, $cardData) = $this->buildGroupKanban($lanes, $columns, $cardGroup, $searchValue, $groupBy, $browseType, $menus);

        $kanbanGroup['id']   = $executionID;
        $kanbanGroup['key']  = 'group' . $executionID;
        $kanbanGroup['data'] = array();
        $kanbanGroup['data']['lanes'] = $laneData;
        $kanbanGroup['data']['cols']  = array_values($columnData);
        $kanbanGroup['data']['items'] = $cardData;

        return array($kanbanGroup);
    }

    /**
     * 构建分组视图的看板泳道。
     * Build lanes for group kanban.
     *
     * @access public
     * @param  int    $executionID
     * @param  string $browseType
     * @param  string $groupBy
     * @param  array  $cardList
     * @param  string $orderBy
     *
     * @return array
     */
    public function getLanes4Group(int $executionID, string $browseType, string $groupBy, array $cardList, string $orderBy = 'id_asc'): array
    {
        $lanes       = array();
        $groupByList = array();
        foreach($cardList as $item)
        {
            if(!isset($groupByList[$item->$groupBy])) $groupByList[$item->$groupBy] = $item->$groupBy;

            if($groupBy == 'assignedTo' and !empty($item->teamMember))
            {
                foreach($item->teamMember as $account => $name)
                {
                    if(!isset($groupByList[$account])) $groupByList[$account] = $account;
                }
            }
        }

        $objectPairs = $this->getObjectPairs($groupBy, $groupByList, $browseType, $orderBy);
        if($groupBy == 'story') $objects = $this->dao->select('*')->from(TABLE_STORY)->where('deleted')->eq(0)->andWhere('id')->in($groupByList)->orderBy($orderBy)->fetchAll('id');

        $laneColor = 0;
        $order     = 1;
        foreach($objectPairs as $objectType => $objectName)
        {
            if(!isset($groupByList[$objectType]) and $objectType and !in_array($objectType, array('feature', 'design'))) continue;

            $lane = new stdclass();
            $lane->id         = $groupBy . $objectType;
            $lane->type       = $browseType;
            $lane->execution  = $executionID;
            $lane->name       = $objectName;
            $lane->order      = $order;
            $lane->color      = $this->config->kanban->laneColorList[$laneColor];
            $lane->pri        = (isset($objects) and isset($objects[$objectType]->pri)) ? $objects[$objectType]->pri : '';
            $lane->assignedTo = (isset($objects) and isset($objects[$objectType]->assignedTo)) ? $objects[$objectType]->assignedTo : '';

            $order     += 1;
            $laneColor += 1;
            if($laneColor == count($this->config->kanban->laneColorList)) $laneColor = 0;
            $lanes[$objectType] = $lane;
        }

        return $lanes;
    }

    /**
     * 获取分组视图的看板列数据。
     * Get columns for group kanban.
     *
     * @param  int    $executionID
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getCols4Group(int $executionID, string $browseType): array
    {
        $execution = $this->loadModel('execution')->getByID($executionID);

        return $this->dao->select('t1.*, GROUP_CONCAT(t1.cards) as cards, t2.`type` as columnType, t2.parent, t2.limit, t2.name as columnName, t2.color')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.`column` = t2.id')
            ->leftJoin(TABLE_KANBANLANE)->alias('t3')->on('t1.lane = t3.id')
            ->leftJoin(TABLE_KANBANREGION)->alias('t4')->on('t1.kanban = t4.kanban')
            ->where('t1.kanban')->eq($executionID)
            ->andWhere('t1.`type`')->eq($browseType)
            ->beginIF(isset($execution->type) and $execution->type == 'kanban')
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t4.deleted')->eq(0)
            ->fi()
            ->groupBy('columnType')
            ->orderBy('column_asc')
            ->fetchAll('columnType');
    }

    /**
     * 获取看板空间列表。
     * Get space list.
     *
     * @param  string $browseType private|cooperation|public|involved
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getSpaceList(string $browseType, object $pager = null): array
    {
        $account     = $this->app->user->account;
        $spaceIdList = $this->getCanViewObjects('kanbanspace', $browseType);
        $spaceList   = $this->dao->select('*')->from(TABLE_KANBANSPACE)
            ->where('deleted')->eq(0)
            ->beginIF(in_array($browseType, array('private', 'cooperation', 'public')))->andWhere('type')->eq($browseType)->fi()
            ->beginIF($browseType == 'private' and !$this->app->user->admin)->andWhere('owner')->eq($account)->fi()
            ->beginIF($this->cookie->showClosed == 0)->andWhere('status')->ne('closed')->fi()
            ->andWhere('id')->in($spaceIdList)
            ->orderBy('id_desc')
            ->page($pager)
            ->fetchAll('id');

        $kanbanIdList = $this->getCanViewObjects('kanban', $browseType);
        $kanbanGroup  = $this->getGroupBySpaceList(array_keys($spaceList), $kanbanIdList);
        foreach($spaceList as $spaceID => $space)
        {
            if(isset($kanbanGroup[$spaceID])) $space->kanbans = $kanbanGroup[$spaceID];
        }

        return $spaceList;
    }

    /**
     * 获取看板空间键值对。
     * Get space pairs.
     *
     * @param  string $browseType private|cooperation|public|involved
     * @access public
     * @return array
     */
    public function getSpacePairs(string $browseType = 'private'): array
    {
        $spaceIdList = $this->getCanViewObjects('kanbanspace', $browseType);

        return $this->dao->select('id,name')->from(TABLE_KANBANSPACE)
            ->where('deleted')->eq(0)
            ->andWhere('id')->in($spaceIdList)
            ->beginIF(in_array($browseType, array('private', 'cooperation', 'public')))->andWhere('type')->eq($browseType)->fi()
            ->beginIF($this->cookie->showClosed == 0 and $browseType != 'showClosed')->andWhere('status')->ne('closed')->fi()
            ->orderBy('id_desc')
            ->fetchPairs('id');
    }

    /**
     * 获取看板键值对。
     * Get Kanban pairs.
     *
     * @access public
     * @return array
     */
    public function getKanbanPairs(): array
    {
        $kanbanIdList = $this->getCanViewObjects('kanban');

        return $this->dao->select('id,name')->from(TABLE_KANBAN)
            ->where('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($kanbanIdList)->fi()
            ->orderBy('id_desc')
            ->fetchPairs('id');
    }

    /**
     * 获取有权查看的对象。
     * Get can view objects.
     *
     * @param  string $objectType kanbanspace|kanban
     * @param  string $param      all|noclosed|private|cooperation|public|involved
     * @access public
     * @return array
     */
    public function getCanViewObjects(string $objectType = 'kanban', string $param = 'all'): array
    {
        $table   = $this->config->objectTables[$objectType];
        $objects = $this->dao->select('*')->from($table)
            ->where('deleted')->eq(0)
            ->beginIF(strpos($param, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->fetchAll('id');

        $spaceList = $objectType == 'kanban' ? $this->dao->select('id,owner,type')->from(TABLE_KANBANSPACE)->fetchAll('id') : array();

        if($param && $this->app->user->admin && strpos('private,involved', $param) === false) return array_keys($objects);

        $account = $this->app->user->account;
        foreach($objects as $objectID => $object)
        {
            if($objectType == 'kanbanspace' && $object->type == 'public' && $param != 'involved') continue;

            $remove = true;

            if($object->owner == $account) $remove = false;
            if(strpos(",{$object->team},", ",$account,") !== false) $remove = false;
            if(strpos(",{$object->whitelist},", ",$account,") !== false) $remove = false;

            if($objectType == 'kanban')
            {
                $spaceOwner = isset($spaceList[$object->space]->owner) ? $spaceList[$object->space]->owner : '';
                $spaceType  = isset($spaceList[$object->space]->type) ? $spaceList[$object->space]->type : '';
                if(strpos(",$spaceOwner,", ",$account,") !== false) $remove = false;
                if($spaceType == 'public' and $param != 'involved') $remove = false;
            }

            if($remove) unset($objects[$objectID]);
        }

        return array_keys($objects);
    }

    /**
     * 创建看板空间。
     * Create a space.
     *
     * @param  object $space
     * @access public
     * @return int|false
     */
    public function createSpace(object $space): int|false
    {
        $account = $this->app->user->account;

        if($space->type == 'private') $space->owner = $account;
        if(strpos(",{$space->team},", ",$account,") === false)      $space->team .= ",$account";
        if(strpos(",{$space->team},", ",$space->owner,") === false) $space->team .= ",$space->owner";

        $this->dao->insert(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->batchCheck($this->config->kanban->createspace->requiredFields, 'notempty')
            ->exec();

        if(dao::isError()) return false;

        $spaceID = $this->dao->lastInsertID();
        $this->dao->update(TABLE_KANBANSPACE)->set('`order`')->eq($spaceID)->where('id')->eq($spaceID)->exec();
        $this->loadModel('file')->saveUpload('kanbanspace', $spaceID);
        $this->file->updateObjectID($this->post->uid, $spaceID, 'kanbanspace');
        $this->loadModel('action')->create('kanbanSpace', $spaceID, 'created');

        return $spaceID;
    }

    /**
     * 编辑看板空间。
     * Update a space.
     *
     * @param  object $space
     * @param  int    $spaceID
     * @access public
     * @return bool
     */
    public function updateSpace(object $space, int $spaceID): bool
    {
        $oldSpace = $this->getSpaceById($spaceID);

        if($space->type == 'cooperation' or $space->type == 'public') $space->whitelist = '';

        $this->dao->update(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->batchCheck($this->config->kanban->editspace->requiredFields, 'notempty')
            ->where('id')->eq($spaceID)
            ->exec();

        if($oldSpace->type == 'private' and (in_array($space->type, array('cooperation', 'public'))))
        {
            $this->dao->update(TABLE_KANBAN)->set('team = whitelist')->set('whitelist')->eq('')->where('space')->eq($spaceID)->andWhere('deleted')->eq('0')->exec();
        }

        if(dao::isError()) return false;

        $this->loadModel('file')->saveUpload('kanbanspace', $spaceID);
        $this->file->updateObjectID($this->post->uid, $spaceID, 'kanbanspace');
        $changes = common::createChanges($oldSpace, $space);

        if($changes)
        {
            $actionID = $this->loadModel('action')->create('kanbanSpace', $spaceID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 关闭看板空间。
     * Close a space.
     *
     * @param  int    $spaceID
     * @param  object $space
     * @access public
     * @return bool
     */
    function closeSpace(int $spaceID, object $space): bool
    {
        $oldSpace = $this->getSpaceById($spaceID);

        $this->dao->update(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->where('id')->eq($spaceID)
            ->exec();

        if(dao::isError()) return false;
        $changes = common::createChanges($oldSpace, $space);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('kanbanSpace', $spaceID, 'closed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 激活看板空间。
     * Activate a space.
     *
     * @param  int    $spaceID
     * @param  object $space
     * @access public
     * @return bool
     */
    function activateSpace(int $spaceID, object $space): bool
    {
        $oldSpace = $this->getSpaceById($spaceID);

        $this->dao->update(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->where('id')->eq($spaceID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldSpace, $space);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('kanbanSpace', $spaceID, 'activated', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 获取看板泳道键值对。
     * Get lane pairs by region id.
     *
     * @param  array|int $regionID
     * @param  string    $type all|story|task|bug|common
     * @access public
     * @return array
     */
    public function getLanePairsByRegion(int $regionID, string $type = 'all'): array
    {
        return $this->dao->select('id, name')->from(TABLE_KANBANLANE)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regionID)
            ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
            ->fetchPairs();
    }

    /**
     * 获取看板泳道分组。
     * Get lane by region id.
     *
     * @param  array|int $regionID
     * @param  string    $type     all|story|task|bug|common
     * @access public
     * @return array
     */
    public function getLaneGroupByRegion(array|int $regionID, string $type = 'all'): array
    {
        return $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regionID)
            ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
            ->fetchGroup('region');
    }

    /**
     * 创建看板泳道。
     * Create a lane.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  object $lane
     * @param  string $mode    new|copy
     * @access public
     * @return int|bool
     */
    public function createLane(int $kanbanID, int $regionID, object $lane = null, string $mode = 'new'): int|bool
    {
        if($mode == 'new')
        {
            $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANLANE)
                ->where('region')->eq($regionID)
                ->fetch('maxOrder');

            $lane->order     = $maxOrder ? $maxOrder + 1 : 1;
            $lane->type      = isset($_POST['laneType']) ? $_POST['laneType'] : 'common';
            $lane->execution = isset($_POST['laneType']) ? $kanbanID : 0;

            if($lane->mode == 'sameAsOther')
            {
                if($lane->otherLane) $lane->group = $this->dao->select('`group`')->from(TABLE_KANBANLANE)->where('id')->eq($lane->otherLane)->fetch('group');
            }
            elseif($lane->mode == 'independent')
            {
                $lane->group = $this->createGroup($kanbanID, $regionID);
                if($lane->type == 'common') $this->createDefaultColumns($regionID, $lane->group);
            }
        }

        $this->dao->insert(TABLE_KANBANLANE)->data($lane, $skip = 'mode,otherLane')
            ->batchCheck($this->config->kanban->require->createlane, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $laneID = $this->dao->lastInsertID();
        if($lane->type != 'common' and $lane->mode == 'independent') $this->createRDColumn($regionID, $lane->group, $laneID, $lane->type, $kanbanID);

        if($mode == 'sameAsOther' or ($lane->type == 'common' and $mode == 'independent'))
        {
            $columnIDList = $this->dao->select('id')->from(TABLE_KANBANCOLUMN)->where('deleted')->eq(0)->andWhere('archived')->eq(0)->andWhere('`group`')->eq($lane->group)->fetchPairs();
            foreach($columnIDList as $columnID)
            {
                $this->addKanbanCell($kanbanID, $laneID, $columnID, $lane->type);

                if(dao::isError()) return false;
            }
        }

        $this->loadModel('action')->create('kanbanLane', $laneID, 'created');
        return $laneID;
    }

    /*
     * 创建看板。
     * Create a kanban.
     *
     * @param  object $kanban
     * @access public
     * @return int|false
     */
    public function create(object $kanban): int|false
    {
        $account = $this->app->user->account;

        if($this->post->import == 'on') $kanban->object = implode(',', $this->post->importObjectList);

        if(strpos(",{$kanban->team},", ",$account,") === false)       $kanban->team .= ",$account";
        if(strpos(",{$kanban->team},", ",$kanban->owner,") === false) $kanban->team .= ",$kanban->owner";

        if(!empty($kanban->space))
        {
            $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBAN)
                ->where('space')->eq($kanban->space)
                ->fetch('maxOrder');
            $kanban->order = $maxOrder ? $maxOrder + 1 : 1;

            $space = $this->getSpaceById($kanban->space);
            if($space->type == 'private') $kanban->owner = $account;
        }

        $this->kanbanTao->createKanban($kanban);

        if(dao::isError()) return false;

        $kanbanID = $this->dao->lastInsertID();
        $kanban   = $this->getByID($kanbanID);

        $this->loadModel('action')->create('kanban', $kanbanID, 'created');
        $this->loadModel('file')->saveUpload('kanban', $kanbanID);
        $this->file->updateObjectID($this->post->uid, $kanbanID, 'kanban');

        $this->post->copyRegion ? $this->copyRegions($kanban, $this->post->copyKanbanID): $this->createDefaultRegion($kanban);

        if(!empty($kanban->team) or !empty($kanban->whitelist))
        {
            $type = !empty($kanban->team) ? 'team' : 'whitelist';
            $kanbanMembers = empty($kanban->{$type}) ? array() : explode(',', $kanban->{$type});
            $this->addSpaceMembers($kanban->space, $type, $kanbanMembers);
        }

        return $kanbanID;
    }

    /**
     * 编辑看板。
     * Update a kanban.
     *
     * @param  int    $kanbanID
     * @param  object $kanban
     * @access public
     * @return bool
     */
    public function update(int $kanbanID, object $kanban): bool
    {
        $oldKanban = $this->getByID($kanbanID);

        if(strpos(",{$kanban->team},", ",$kanban->owner,") === false) $kanban->team .= ",$kanban->owner";

        $this->dao->update(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->batchCheck($this->config->kanban->edit->requiredFields, 'notempty')
            ->where('id')->eq($kanbanID)
            ->exec();

        if(dao::isError()) return false;

        $this->loadModel('file')->saveUpload('kanban', $kanbanID);
        $this->file->updateObjectID($this->post->uid, $kanbanID, 'kanban');

        if(!empty($kanban->team) or !empty($kanban->whitelist))
        {
            $type = !empty($kanban->team) ? 'team' : 'whitelist';
            $kanbanMembers = empty($kanban->{$type}) ? array() : explode(',', $kanban->{$type});
            $this->addSpaceMembers($kanban->space, $type, $kanbanMembers);
        }

        $changes  = common::createChanges($oldKanban, $kanban);
        $actionID = $this->loadModel('action')->create('kanban', $kanbanID, 'edited');
        $this->action->logHistory($actionID, $changes);

        return true;
    }

    /**
     * 设置看板。
     * Setting kanban.
     *
     * @param  int    $kanbanID
     * @param  object $kanban
     * @access public
     * @return bool
     */
    public function setting(int $kanbanID, object $kanban): bool
    {
        $oldKanban = $this->getByID($kanbanID);

        if($this->post->import == 'off')      $kanban->object = '';
        if($this->post->heightType == 'auto') $kanban->displayCards = 0;
        if(empty($kanban->displayCards))      $kanban->displayCards = 0;

        if($this->post->import == 'on') $kanban->object = $this->post->importObjectList ? implode(',', $this->post->importObjectList) : '';
        if(isset($_POST['heightType']) and $this->post->heightType == 'custom' and !$this->checkDisplayCards($kanban->displayCards)) return false;

        $this->dao->update(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->batchCheck($this->config->kanban->edit->requiredFields, 'notempty')
            ->checkIF(!$kanban->fluidBoard, 'colWidth', 'ge', $this->config->minColWidth)
            ->batchCheckIF($kanban->fluidBoard, 'minColWidth', 'ge', $this->config->minColWidth)
            ->checkIF($kanban->minColWidth >= $this->config->minColWidth and $kanban->fluidBoard, 'maxColWidth', 'gt', $kanban->minColWidth)
            ->where('id')->eq($kanbanID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldKanban, $kanban);

        if($changes)
        {
            $actionID = $this->loadModel('action')->create('kanban', $kanbanID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 激活看板。
     * Activate a kanban.
     *
     * @param  int    $kanbanID
     * @param  object $kanban
     * @access public
     * @return bool
     */
    function activate(int $kanbanID, object $kanban): bool
    {
        $oldKanban = $this->getByID($kanbanID);

        $this->dao->update(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->where('id')->eq($kanbanID)
            ->exec();

        if(dao::isError()) return false;

        $changes  = common::createChanges($oldKanban, $kanban);
        $actionID = $this->loadModel('action')->create('kanban', $kanbanID, 'activated', $this->post->comment);
        $this->action->logHistory($actionID, $changes);

        return true;
    }

    /**
     * 关闭看板。
     * Close a kanban.
     *
     * @param  int    $kanbanID
     * @param  object $kanban
     * @access public
     * @return bool
     */
    function close(int $kanbanID, object $kanban): bool
    {
        $oldKanban = $this->getByID($kanbanID);

        $this->dao->update(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->where('id')->eq($kanbanID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldKanban, $kanban);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('kanban', $kanbanID, 'closed', $this->post->comment);
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 执行看板新增列和泳道。
     * Add execution Kanban lanes and columns.
     *
     * @param  int    $executionID
     * @param  string $type all|story|bug|task
     * @access public
     * @return void
     */
    public function createExecutionLane(int $executionID, string $type = 'all')
    {
        foreach($this->config->kanban->default as $type => $lane)
        {
            $lane->type      = $type;
            $lane->execution = $executionID;
            $lane->region    = 0;
            $lane->group     = 0;
            $lane->groupby   = '';
            $lane->extra     = '';
            $this->dao->insert(TABLE_KANBANLANE)->data($lane)->exec();

            $laneID = $this->dao->lastInsertId();
            $this->createExecutionColumns($laneID, $type, $executionID);
        }
    }

    /**
     * 创建执行看板列。
     * Create execution columns.
     *
     * @param  int|array $laneID
     * @param  string    $type story|bug|task
     * @param  int       $executionID
     * @access public
     * @return void
     */
    public function createExecutionColumns(int|array $laneID, string $type, int $executionID)
    {
        $devColumnID = $testColumnID = $resolvingColumnID = 0;

        $columns = array();
        if($type == 'story') $columns = $this->lang->kanban->storyColumn;
        if($type == 'bug')   $columns = $this->lang->kanban->bugColumn;
        if($type == 'task')  $columns = $this->lang->kanban->taskColumn;
        if(empty($columns)) return;

        foreach($columns as $colType => $name)
        {
            $data = new stdclass();
            $data->name   = $name;
            $data->color  = '#333';
            $data->type   = $colType;
            $data->region = 0;

            if(str_contains(',developing,developed,',   ",{$colType},")) $data->parent = $devColumnID;
            if(str_contains(',testing,tested,',         ",{$colType},")) $data->parent = $testColumnID;
            if(str_contains(',fixing,fixed,',           ",{$colType},")) $data->parent = $resolvingColumnID;
            if(str_contains(',resolving,develop,test,', ",{$colType},")) $data->parent = -1;

            $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();

            $colID = $this->dao->lastInsertId();
            if($colType == 'develop')   $devColumnID       = $colID;
            if($colType == 'test')      $testColumnID      = $colID;
            if($colType == 'resolving') $resolvingColumnID = $colID;

            if(is_array($laneID))
            {
                foreach($laneID as $id) $this->addKanbanCell($executionID, $id, $colID, $type);
            }
            else
            {
                $this->addKanbanCell($executionID, $laneID, $colID, $type);
            }
        }
    }

    /**
     * 新增看板Cell。
     * Add kanban cell for new lane.
     *
     * @param  int    $kanbanID
     * @param  int    $laneID
     * @param  int    $colID
     * @param  string $type story|task|bug|card
     * @param  string $cardID
     * @access public
     * @return void
     */
    public function addKanbanCell(int $kanbanID, int $laneID, int $colID, string $type, string $cardID = '')
    {
        $cell = $this->dao->select('id, cards')->from(TABLE_KANBANCELL)
            ->where('kanban')->eq($kanbanID)
            ->andWhere('lane')->eq($laneID)
            ->andWhere('`column`')->eq($colID)
            ->andWhere('type')->eq($type)
            ->fetch();

        if(empty($cell))
        {
            $cell = new stdclass();
            $cell->kanban = $kanbanID;
            $cell->lane   = $laneID;
            $cell->column = $colID;
            $cell->type   = $type;
            $cell->cards  = $cardID ? ",$cardID," : '';

            $this->dao->insert(TABLE_KANBANCELL)->data($cell)->exec();
        }
        else
        {
            $cell->cards = $cell->cards ? ",$cardID" . $cell->cards : ",$cardID,";
            $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($cell->cards)->where('id')->eq($cell->id)->exec();
        }
    }

    /**
     * 向看板空间添加成员。
     * Add space members.
     *
     * @param  int    $spaceID
     * @param  string $type team|whitelist
     * @param  array  $kanbanMembers
     * @access public
     * @return void
     */
    public function addSpaceMembers(int $spaceID, string $type, array $kanbanMembers = array())
    {
        $space = $this->getSpaceById($spaceID);
        if(empty($space)) return;

        $spaceMembers = empty($space->{$type}) ? array() : explode(',', $space->{$type});
        $members      = $space->{$type};
        $addMembers   = array_diff($kanbanMembers, $spaceMembers);

        if(!empty($addMembers))
        {
            $addMembers = implode(',', $addMembers);
            $members   .= ',' . trim($addMembers, ',');
            $this->dao->update(TABLE_KANBANSPACE)->set($type)->eq($members)->where('id')->eq($spaceID)->exec();
        }
    }

    /**
     * 从看板格子中移除卡片。
     * Remove kanban cell.
     *
     * @param  string    $type
     * @param  int|array $removeCardID
     * @param  array     $kanbanList
     * @access public
     * @return void
     */
    public function removeKanbanCell(string $type, int|array $removeCardID, array $kanbanList)
    {
        $removeIDList = is_array($removeCardID) ? $removeCardID : array($removeCardID);
        foreach($removeIDList as $cardID)
        {
            if(empty($cardID)) continue;

            $this->dbh->query("UPDATE " . TABLE_KANBANCELL. " SET `cards` = REPLACE(cards, ',$cardID,', ',') WHERE `type` = '$type' AND `kanban` = {$kanbanList[$cardID]}");
        }

        $this->dao->update(TABLE_KANBANCELL)
            ->set('cards')->eq('')
            ->where('cards')->eq(',')
            ->andWhere('type')->eq($type)
            ->andWhere('kanban')->in($kanbanList)
            ->exec();
    }

    /**
     * 创建默认的专业研发看板。
     * Create a default RD kanban.
     *
     * @param  object $execution
     * @access public
     * @return void
     */
    public function createRDKanban(object $execution)
    {
        $regionID =  $this->createRDRegion($execution);
        if(dao::isError()) return false;

        $this->createGroup((int)$execution->id, $regionID);
        if(dao::isError()) return false;

        $this->createRDLane((int)$execution->id, $regionID);
        if(dao::isError()) return false;
    }

    /**
     * 创建默认的专业研发看板区域。
     * Create a default RD region.
     *
     * @param  object $execution
     *
     * @access public
     * @return int|bool
     */
    public function createRDRegion(object $execution)
    {
        $region = new stdclass();
        $region->name        = $this->lang->kanbanregion->default;
        $region->kanban      = $execution->id;
        $region->createdBy   = $this->app->user->account;
        $region->createdDate = helper::today();
        $region->order       = 1;

        $this->dao->insert(TABLE_KANBANREGION)->data($region)
            ->check('name', 'unique', "kanban={$execution->id} AND deleted='0' AND space = '0'")
            ->autoCheck()
            ->exec();

        if(dao::isError()) return false;
        return $this->dao->lastInsertId();
    }

    /**
     * 创建默认的专业研发看板泳道。
     * Create default RD lanes.
     *
     * @param  int    $executionID
     * @param  int    $regionID
     *
     * @access public
     * @return void
     */
    public function createRDLane(int $executionID, int $regionID)
    {
        $laneIndex = 0;
        foreach($this->lang->kanban->laneTypeList as $type => $name)
        {
            $groupID = $this->createGroup($executionID, $regionID);
            if(dao::isError()) return false;

            $lane = new stdclass();
            $lane->execution = $executionID;
            $lane->type      = $type;
            $lane->region    = $regionID;
            $lane->group     = $groupID;
            $lane->name      = $name;
            $lane->color     = $this->config->kanban->laneColorList[$laneIndex];
            $lane->order     = ++ $laneIndex * 5;

            $this->dao->insert(TABLE_KANBANLANE)->data($lane)->autoCheck()->exec();
            if(dao::isError()) return false;

            $this->createRDColumn($regionID, $groupID, $this->dao->lastInsertId(), $type, $executionID);
        }
    }

    /**
     * 创建默认值的专业研发看板列。
     * Create default RD columns.
     *
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $laneID
     * @param  string $laneType
     * @param  int    $executionID
     *
     * @access public
     * @return bool
     */
    public function createRDColumn(int $regionID, int $groupID, int $laneID, string $laneType, int $executionID)
    {
        $devColumnID = $testColumnID = $resolvingColumnID = 0;
        if($laneType == 'story') $columnList = $this->lang->kanban->storyColumn;
        if($laneType == 'bug')   $columnList = $this->lang->kanban->bugColumn;
        if($laneType == 'task')  $columnList = $this->lang->kanban->taskColumn;

        foreach($columnList as $type => $name)
        {
            $data = new stdclass();
            $data->name   = $name;
            $data->color  = '#333';
            $data->type   = $type;
            $data->group  = $groupID;
            $data->region = $regionID;

            if(strpos(',developing,developed,', $type) !== false) $data->parent = $devColumnID;
            if(strpos(',testing,tested,', $type) !== false) $data->parent = $testColumnID;
            if(strpos(',fixing,fixed,', $type) !== false) $data->parent = $resolvingColumnID;
            if(strpos(',develop,test,resolving,', $type) !== false) $data->parent = -1;

            $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();
            if(dao::isError()) return false;

            if($type == 'develop') $devColumnID  = $this->dao->lastInsertId();
            if($type == 'test')    $testColumnID = $this->dao->lastInsertId();
            if($type == 'resolving') $resolvingColumnID = $this->dao->lastInsertId();

            $this->addKanbanCell($executionID, $laneID, $this->dao->lastInsertId(), $laneType);
        }
    }

    /**
     * 编辑区域。
     * Update a region.
     *
     * @param  int    $regionID
     * @access public
     * @return bool
     */
    public function updateRegion(int $regionID): bool
    {
        $region = new stdclass();
        $region->lastEditedBy   = $this->app->user->account;
        $region->lastEditedDate = helper::now();
        $region->name           = trim($this->post->name);

        $oldRegion = $this->getRegionById($regionID);

        $this->dao->update(TABLE_KANBANREGION)->data($region)
            ->autoCheck()
            ->batchcheck($this->config->kanban->editregion->requiredFields, 'notempty')
            ->where('id')->eq($regionID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldRegion, $region);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('kanbanregion', $regionID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 编辑看板泳道。
     * Update kanban lane.
     *
     * @param  int    $executionID
     * @param  string $laneType
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function updateLane(int $executionID, string $laneType, int $cardID = 0)
    {
        $execution = $this->loadModel('execution')->getByID($executionID);
        if($execution->type == 'kanban')
        {
            $lanes = $this->dao->select('t2.*')->from(TABLE_KANBANREGION)->alias('t1')
                ->leftJoin(TABLE_KANBANLANE)->alias('t2')->on('t1.id=t2.region')
                ->leftJoin(TABLE_KANBANCELL)->alias('t3')->on('t2.id=t3.lane')
                ->where('t1.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->andWhere('t1.kanban')->eq($executionID)
                ->andWhere('t2.execution')->eq($executionID)
                ->andWhere('t2.type')->eq($laneType)
                ->beginIF(!empty($cardID))->andWhere('t3.cards')->like("%,$cardID,%")->fi()
                ->orderBy('t1.`order` asc, t2.`order` asc')
                ->fetchAll('id');

            if(count($lanes) > 1) $lanes = array_slice($lanes, 0, 1);
        }
        else
        {
            $lanes = $this->dao->select('*')->from(TABLE_KANBANLANE)
                ->where('execution')->eq($executionID)
                ->andWhere('type')->eq($laneType)
                ->fetchAll('id');
        }

        foreach($lanes as $lane) $this->refreshCards((array)$lane);
    }

    /**
     * 更新看板泳道上的卡片。
     * Refresh column cards.
     *
     * @param  array  $lane
     * @access public
     * @return void
     */
    public function refreshCards(array $lane)
    {
        $laneID        = zget($lane, 'id');
        $laneType      = zget($lane, 'type');
        $executionID   = zget($lane, 'execution');
        $otherCardList = '';
        $otherLanes    = $this->dao->select('t2.id, t2.cards')->from(TABLE_KANBANLANE)->alias('t1')
            ->leftJoin(TABLE_KANBANCELL)->alias('t2')->on('t1.id=t2.lane')
            ->where('t1.id')->ne($laneID)
            ->andWhere('t1.execution')->eq($executionID)
            ->andWhere('t2.`type`')->eq($laneType)
            ->fetchPairs();

        foreach($otherLanes as $cardIDList)
        {
            $cardIDList = trim($cardIDList, ',');
            if(!empty($cardIDList)) $otherCardList .= ',' . $cardIDList;
        }

        $cardPairs = $this->dao->select('t2.type, t1.cards')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.`column` = t2.id')
            ->where('t1.kanban')->eq($executionID)
            ->andWhere('t1.lane')->eq($laneID)
            ->fetchPairs();

        if(empty($cardPairs)) return;
        $sourceCards = $cardPairs;

        if($laneType == 'story') $cardPairs = $this->kanbanTao->refreshStoryCards($cardPairs, $executionID, $otherCardList);
        if($laneType == 'bug')   $cardPairs = $this->kanbanTao->refreshBugCards($cardPairs, $executionID, $otherCardList);
        if($laneType == 'task')  $cardPairs = $this->kanbanTao->refreshTaskCards($cardPairs, $executionID, $otherCardList);

        $colPairs = $this->dao->select('t2.type, t2.id')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.`column` = t2.id')
            ->where('t1.kanban')->eq($executionID)
            ->andWhere('t1.lane')->eq($laneID)
            ->fetchPairs();

        $updated = false;
        foreach($cardPairs as $colType => $cards)
        {
            if(!isset($colPairs[$colType])) continue;
            if($sourceCards[$colType] == $cards) continue;

            $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($cards)->where('lane')->eq($laneID)->andWhere('`column`')->eq($colPairs[$colType])->exec();
            if(!$updated) $updated = true;
        }

        if($updated) $this->dao->update(TABLE_KANBANLANE)->set('lastEditedTime')->eq(helper::now())->where('id')->eq($laneID)->exec();
    }

    /**
     * 编辑看板列。
     * Update column.
     *
     * @param  int    $columnID
     * @param  object $column
     * @access public
     * @return array|false
     */
    public function updateColumn(int $columnID, object $column): array|false
    {
        $oldColumn = $this->getColumnById($columnID);
        $this->dao->update(TABLE_KANBANCOLUMN)->data($column)
            ->autoCheck()
            ->batchcheck($this->config->kanban->setColumn->requiredFields, 'notempty')
            ->where('id')->eq($columnID)
            ->exec();

        if(dao::isError()) return false;

        $changes = common::createChanges($oldColumn, $column);
        return $changes;
    }

    /**
     * 激活卡片。
     * Activate a card.
     *
     * @param  int    $cardID
     * @access public
     * @return bool
     */
    public function activateCard(int $cardID): bool
    {
        if($this->post->progress >= 100 or $this->post->progress < 0)
        {
            dao::$errors[] = $this->lang->kanbancard->error->progressIllegal;
            return false;
        }

        $oldCard = $this->getCardByID($cardID);
        $this->dao->update(TABLE_KANBANCARD)->set('progress')->eq($this->post->progress ? $this->post->progress : 0)->set('status')->eq('doing')->where('id')->eq($cardID)->exec();
        $card = $this->getCardByID($cardID);

        $changes = common::createChanges($oldCard, $card);
        $actionID = $this->loadModel('action')->create('kanbanCard', $cardID, 'activated');
        $this->action->logHistory($actionID, $changes);

        return true;
    }

    /**
     * 编辑看板卡片。
     * Update a card.
     *
     * @param  int    $cardID
     * @param  object $card
     * @access public
     * @return bool
     */
    public function updateCard(int $cardID, object $card): bool
    {
        if($card->estimate < 0)
        {
            dao::$errors['estimate'] = $this->lang->kanbancard->error->recordMinus;
            return false;
        }

        if($card->end && ($card->begin > $card->end))
        {
            dao::$errors['end'] = $this->lang->kanbancard->error->endSmall;
            return false;
        }

        if($card->progress > 100 or $card->progress < 0)
        {
            dao::$errors['progress'] = $this->lang->kanbancard->error->progressIllegal;
            return false;
        }

        $oldCard = $this->getCardByID($cardID);

        if(!empty($this->post->assignedTo) and $oldCard->assignedTo != $this->post->assignedTo) $card->assignedDate = helper::now();
        $card->status = $card->progress == 100 ? 'done' : 'doing';

        $card = $this->loadModel('file')->processImgURL($card, $this->config->kanban->editor->editcard['id'], $this->post->uid);

        $this->dao->update(TABLE_KANBANCARD)->data($card)
            ->autoCheck()
            ->checkIF($card->estimate != '', 'estimate', 'float')
            ->batchcheck($this->config->kanban->editcard->requiredFields, 'notempty')
            ->where('id')->eq($cardID)
            ->exec();

        if(dao::isError()) return false;

        $this->file->saveUpload('kanbancard', $cardID);
        $this->file->updateObjectID($this->post->uid, $cardID, 'kanbancard');

        $changes = common::createChanges($oldCard, $card);

        if($changes)
        {
            $actionID = $this->loadModel('action')->create('kanbanCard', $cardID, 'edited');
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 设置看板在制品限制。
     * Set WIP limit.
     *
     * @param  int    $columnID
     * @param  object $WIP
     * @access public
     * @return bool
     */
    public function setWIP(int $columnID, object $WIP): bool
    {
        $oldColumn = $this->getColumnById($columnID);
        if(!preg_match("/^-?\d+$/", $WIP->limit) or (!$WIP->noLimit and $WIP->limit <= 0))
        {
            dao::$errors['limit'] = $this->lang->kanban->error->mustBeInt;
            return false;
        }

        /* Check column limit. */
        $sumChildLimit = 0;
        if($oldColumn->parent == -1 and $WIP->limit != -1)
        {
            $childColumns = $this->dao->select('id,`limit`')->from(TABLE_KANBANCOLUMN)->where('parent')->eq($columnID)->andWhere('deleted')->eq(0)->fetchAll();
            foreach($childColumns as $childColumn) $sumChildLimit += $childColumn->limit;

            if($sumChildLimit > $WIP->limit)
            {
                dao::$errors['limit'] = $this->lang->kanban->error->parentLimitNote;
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

                $sumChildLimit = (int)$siblingLimit + (int)$WIP->limit;

                if($WIP->limit == -1 or $siblingLimit == -1 or $sumChildLimit > $parentColumn->limit)
                {
                    dao::$errors['limit'] = $this->lang->kanban->error->childLimitNote;
                }
            }
        }

        if(dao::isError()) return false;

        $this->dao->update(TABLE_KANBANCOLUMN)->data($WIP, 'noLimit')
            ->autoCheck()
            ->checkIF($WIP->limit != -1, 'limit', 'gt', 0)
            ->batchcheck($this->config->kanban->setwip->requiredFields, 'notempty')
            ->where('id')->eq($columnID)
            ->exec();

        return !dao::isError();
    }

    /**
     * 设置看板列。
     * Set lane info.
     *
     * @param  int    $laneID
     * @param  object $lane
     * @access public
     * @return bool
     */
    public function setLane(int $laneID, object $lane): bool
    {
        $this->dao->update(TABLE_KANBANLANE)->data($lane)
            ->autoCheck()
            ->batchcheck($this->config->kanban->setlane->requiredFields, 'notempty')
            ->where('id')->eq($laneID)
            ->exec();

        return !dao::isError();
    }

    /**
     * 获取看板右上角的工具栏。
     * Get kanban pageToolBar.
     *
     * @param  object $kanban
     * @access public
     * @return string
     */
    public function getPageToolBar(object $kanban)
    {
        $actions  = '';
        $actions .= "<a href='javascript:$(\"#kanbanList\").fullscreen();' id='fullScreenBtn' class='toolbar-item ghost btn btn-default'><i class='icon icon-fullscreen'></i> {$this->lang->kanban->fullScreen}</a>";

        $printKanbanBtn = (common::hasPriv('kanban', 'edit') or ($kanban->status == 'active' and common::hasPriv('kanban', 'close')) or common::hasPriv('kanban', 'delete') or ($kanban->status == 'closed' and common::hasPriv('kanban', 'activate')));

        if($printKanbanBtn)
        {
            $actions .= "<a class='toolbar-item ghost btn btn-default setting' type='button' data-toggle='dropdown' data-target='#kanbanActionMenu'>" . '<i class="icon icon-edit"></i> ' . $this->lang->edit . '</a>';
            $actions .= "<menu id='kanbanActionMenu' class='dropdown-menu text-left'>";

            $columnActions = '';
            $actions .= $columnActions;

            $commonActions = '';

            if($columnActions and $commonActions)
            {
                $actions .= "<li class='divider'></li>";
            }
            $actions .= $commonActions;

            $kanbanActions = '';
            if(common::hasPriv('kanban', 'edit')) $kanbanActions .= '<li class="menu-item item">' . html::a(helper::createLink('kanban', 'edit', "kanbanID=$kanban->id", '', true), '<i class="icon icon-edit"></i>' . $this->lang->kanban->edit, '', "class='listitem item-inner menu-item-inner state' data-toggle='modal'") . '</li>';
            if(common::hasPriv('kanban', 'close') and $kanban->status == 'active') $kanbanActions .= '<li class="menu-item item">' . html::a(helper::createLink('kanban', 'close', "kanbanID=$kanban->id", '', true), '<i class="icon icon-off"></i>' . $this->lang->kanban->close, '', "class='listitem item-inner menu-item-inner state' data-toggle='modal'") . '</li>';
            if(common::hasPriv('kanban', 'activate') and $kanban->status == 'closed') $kanbanActions .= '<li class="menu-item item">' . html::a(helper::createLink('kanban', 'activate', "kanbanID=$kanban->id", '', true), '<i class="icon icon-magic"></i>' . $this->lang->kanban->activate, '', "class='listitem item-inner menu-item-inner state' data-toggle='modal'") . '</li>';
            if(common::hasPriv('kanban', 'delete')) $kanbanActions .= '<li class="menu-item item">' . html::a(helper::createLink('kanban', 'delete', "kanbanID=$kanban->id"), '<i class="icon icon-trash"></i>' . $this->lang->kanban->delete, 'hiddenwin', "class='listitem item-inner menu-item-inner state ajax-submit' data-confirm={$this->lang->kanban->confirmDeleteKanban}") . '</li>';

            if($commonActions and $kanbanActions)
            {
                $actions .= "<li class='divider'></li>";
            }
            $actions .= $kanbanActions;

            $actions .= "</menu>";
        }

        if(common::hasPriv('kanban', 'setting'))
        {
            $actions .= html::a(helper::createLink('kanban', 'setting', "kanbanID=$kanban->id", '', true), '<i class="icon icon-cog-outline"></i> ' . $this->lang->kanban->setting, '', "class='toolbar-item ghost btn btn-default' data-toggle='modal'");
        }

        return $actions;
    }

    /**
     * 排序看板分组。
     * Sort kanban group.
     *
     * @param  int    $region
     * @param  array  $groups
     * @access public
     * @return bool
     */
    public function sortGroup(int $region, array $groups): bool
    {
        $groupList = $this->getGroupList($region);

        $order = 1;
        foreach($groups as $groupID)
        {
            if(!$groupID) continue;
            if(!isset($groupList[$groupID])) continue;

            $this->dao->update(TABLE_KANBANGROUP)->set('`order`')->eq($order)->where('id')->eq($groupID)->exec();

            $order++;
        }

        return !dao::isError();
    }

    /**
     * 移动看板卡片。
     * Move a card.
     *
     * @param  int    $cardID
     * @param  int    $fromColID
     * @param  int    $toColID
     * @param  int    $fromLaneID
     * @param  int    $toLaneID
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function moveCard(int $cardID, int $fromColID, int $toColID, int $fromLaneID, int $toLaneID, int $kanbanID = 0)
    {
        $groupBy = ($this->session->executionGroupBy and ($this->app->tab == 'execution' or $this->config->vision == 'lite')) ? $this->session->executionGroupBy : '';

        $fromCell = $this->dao->select('id,cards,lane')->from(TABLE_KANBANCELL)
            ->where('`column`')->eq($fromColID)
            ->beginIF(!$groupBy or $groupBy == 'default')->andWhere('lane')->eq($fromLaneID)->fi()
            ->beginIF($groupBy and $groupBy != 'default')
            ->andWhere('type')->eq($this->session->executionLaneType)
            ->andWhere('cards')->like("%,$cardID,%")
            ->fi()
            ->fetch();

        if($groupBy and $groupBy != 'default') $fromLaneID = $toLaneID = $fromCell->lane;
        $fromCells[$fromCell->id] = $fromCell;

        /* Remove all cells with cardID in fromCell. */
        $fromLane   = $this->getLaneById($fromLaneID);
        $fromCells += $this->dao->select('t1.id as id,t1.cards,t1.lane')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANLANE)->alias('t2')->on('t1.lane=t2.id')
            ->where('t1.type')->eq($fromLane->type)
            ->andWhere('t1.id')->ne($fromCell->id)
            ->andWhere('t2.region')->eq($fromLane->region)
            ->andWhere('t1.cards')->like("%,$cardID,%")
            ->fetchAll('id');

        foreach($fromCells as $fromCell)
        {
            $fromCellCards = explode(',', $fromCell->cards);
            $fromCellCards = array_unique($fromCellCards);
            $fromCellCards = array_filter($fromCellCards);
            $fromCellCards = implode(',', $fromCellCards);
            $fromCardList  = str_replace(",$cardID,", ',', ",$fromCellCards,");
            if($fromCardList == ',') $fromCardList = '';
            $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($fromCardList)->where('id')->eq($fromCell->id)->exec();
        }

        /* Add cardID to toCell. */
        $toCell      = $this->dao->select('*')->from(TABLE_KANBANCELL)->where('lane')->eq($toLaneID)->andWhere('`column`')->eq($toColID)->fetch();
        $toCellCards = $this->dao->select('cards')->from(TABLE_KANBANCELL)->where('lane')->eq($toLaneID)->andWhere('`column`')->eq($toColID)->fetch('cards');
        $kanbanID    = $kanbanID == 0 ? $toCell->kanban : $kanbanID;
        if(!$toCell) $this->addKanbanCell($kanbanID, $toLaneID, $toColID, 'common');

        $toCardList = rtrim($toCellCards, ',') . ",$cardID,";
        $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($toCardList)->where('`column`')->eq($toColID)->andWhere('lane')->eq($toLaneID)->exec();
    }

    /**
     * 更新卡片的颜色。
     * Update a card's color.
     *
     * @param  int    $cardID
     * @param  string $color
     * @access public
     * @return void
     */
    public function updateCardColor(int $cardID, string $color)
    {
        $this->dao->update(TABLE_KANBANCARD)->set('`color`')->eq('#' . $color)->where('id')->eq($cardID)->exec();
    }

    /**
     * 归档看板列。
     * Archive a column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function archiveColumn(int $columnID)
    {
        $this->dao->update(TABLE_KANBANCOLUMN)
            ->set('archived')->eq(1)
            ->where('id')->eq($columnID)
            ->exec();

        if(!dao::isError()) $this->loadModel('action')->create('kanbancolumn', $columnID, 'archived');
    }

    /**
     * 还原看板列。
     * Restore a column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function restoreColumn(int $columnID)
    {
        $column = $this->getColumnByID($columnID);

        if($column->parent)
        {
            $parent = $this->getColumnByID($column->parent);

            /* If the parent column is normal now, put its card into child column. */
            if($parent->parent != -1)
            {
                $parentCells = $this->dao->select('*')->from(TABLE_KANBANCELL)
                    ->where('`column`')->eq($column->parent)
                    ->andWhere('type')->eq('common')
                    ->fetchAll('id');

                foreach($parentCells as $cell)
                {
                    $cards = $this->dao->select('cards')->from(TABLE_KANBANCELL)
                        ->where('lane')->eq($cell->lane)
                        ->andWhere('`column`')->eq($columnID)
                        ->andWhere('type')->eq('common')
                        ->fetch('cards');

                    $cards = $cards ? $cards . ltrim($cell->cards, ',') : $cell->cards;

                    $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($cards)
                        ->where('lane')->eq($cell->lane)
                        ->andWhere('`column`')->eq($columnID)
                        ->andWhere('type')->eq('common')
                        ->exec();

                    $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq('')
                        ->where('lane')->eq($cell->lane)
                        ->andWhere('`column`')->eq($cell->column)
                        ->andWhere('type')->eq('common')
                        ->exec();
                }

                $this->dao->update(TABLE_KANBANCOLUMN)->set('parent')->eq(-1)->where('id')->eq($column->parent)->exec();
            }
        }

        $this->dao->update(TABLE_KANBANCOLUMN)
            ->set('archived')->eq(0)
            ->where('id')->eq($columnID)
            ->exec();

        if(!dao::isError()) $this->loadModel('action')->create('kanbancolumn', $columnID, 'restore');
    }

    /**
     * 归档看板卡片。
     * Archive a card.
     *
     * @param  int    $cardID
     * @access public
     * @return bool
     */
    public function archiveCard(int $cardID): bool
    {
        $oldCard = $this->getCardByID($cardID);

        $this->dao->update(TABLE_KANBANCARD)
            ->set('archived')->eq(1)
            ->set('archivedBy')->eq($this->app->user->account)
            ->set('archivedDate')->eq(helper::now())
            ->where('id')->eq($cardID)
            ->exec();

        $card = $this->getCardByID($cardID);

        if(dao::isError()) return false;
        $changes = common::createChanges($oldCard, $card);

        if($changes)
        {
            $actionID = $this->loadModel('action')->create('kanbancard', $cardID, 'archived');
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 还原看板卡片。
     * Restore a card.
     *
     * @param  int    $cardID
     * @access public
     * @return bool
     */
    public function restoreCard(int $cardID): bool
    {
        $oldCard = $this->getCardByID($cardID);

        $this->dao->update(TABLE_KANBANCARD)
            ->set('archived')->eq(0)
            ->set('archivedBy')->eq('')
            ->set('archivedDate')->eq(null)
            ->where('id')->eq($cardID)
            ->exec();

        if(dao::isError()) return false;

        $card    = $this->getCardByID($cardID);
        $changes = common::createChanges($oldCard, $card);
        if($changes)
        {
            $actionID = $this->loadModel('action')->create('kanbancard', $cardID, 'restore');
            $this->action->logHistory($actionID, $changes);
        }

        return true;
    }

    /**
     * 删除子列时，将子列的卡片移动到第一个子列，或者移动到父列。
     * Process cards when delete a column.
     *
     * @param  object $column
     * @access public
     * @return void
     */
    public function processCards(object $column)
    {
        $firstColumnID = $this->dao->select('id')->from(TABLE_KANBANCOLUMN)
            ->where('parent')->eq($column->parent)
            ->andWhere('id')->ne($column->id)
            ->andWhere('deleted')->eq('0')
            ->andWhere('archived')->eq('0')
            ->orderBy('`order` asc')
            ->fetch('id');

        $extendID = $firstColumnID;
        if(!$firstColumnID)
        {
            $extendID = $column->parent;
            $this->dao->update(TABLE_KANBANCOLUMN)->set('parent')->eq(0)->where('id')->eq($column->parent)->exec();
        }

        $cells = $this->dao->select('*')->from(TABLE_KANBANCELL)
            ->where('`column`')->eq($column->id)
            ->andWhere('type')->eq('common')
            ->fetchAll('id');

        foreach($cells as $cell)
        {
            $extendCards = $this->dao->select('cards')->from(TABLE_KANBANCELL)
                ->where('lane')->eq($cell->lane)
                ->andWhere('`column`')->eq($extendID)
                ->andWhere('type')->eq('common')
                ->fetch('cards');

            $extendCards = $extendCards ? $extendCards . ltrim($cell->cards, ',') : $cell->cards;

            $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($extendCards)
                ->where('lane')->eq($cell->lane)
                ->andWhere('`column`')->eq($extendID)
                ->andWhere('type')->eq('common')
                ->exec();
        }
    }

    /**
     * 获取看板空间。
     * Get space by id.
     *
     * @param  int    $spaceID
     * @access public
     * @return object
     */
    public function getSpaceById(int $spaceID)
    {
        $space = $this->dao->findById($spaceID)->from(TABLE_KANBANSPACE)->fetch();
        if($space) $space = $this->loadModel('file')->replaceImgURL($space, 'desc');
        return $space;
    }

    /**
     * Get kanban group by space id list.
     *
     * @param  array $spaceIdList
     * @param  array $kanbanIdList
     * @access public
     * @return array
     */
    public function getGroupBySpaceList(array $spaceIdList, array $kanbanIdList = array()): array
    {
        $spaceList = $this->dao->select('*')->from(TABLE_KANBAN)
            ->where('deleted')->eq(0)
            ->andWhere('space')->in($spaceIdList)
            ->beginIF($kanbanIdList)->andWhere('id')->in($kanbanIdList)->fi()
            ->fetchGroup('space', 'id');

        $kanbanIDList = array();
        foreach($spaceList as $kanbanList) $kanbanIDList = array_merge_recursive($kanbanIDList, array_keys($kanbanList));
        $cardsCount = $this->dao->select('kanban, COUNT(*) as count')->from(TABLE_KANBANCARD)
            ->where('deleted')->eq(0)
            ->andWhere('kanban')->in($kanbanIDList)
            ->groupBy('kanban')
            ->fetchPairs('kanban');

        foreach($spaceList as $kanbanList)
        {
            foreach($kanbanList as $kanban)
            {
                $kanban->cardsCount = zget($cardsCount, $kanban->id, 0);
            }
        }

        return $spaceList;
    }

    /**
     * 获取看板组。
     * Get group list by region.
     *
     * @param  int    $regionID
     * @access public
     * @return array
     */
    public function getGroupList(int $regionID): array
    {
        return $this->dao->select('*')->from(TABLE_KANBANGROUP)
            ->where('region')->eq($regionID)
            ->orderBy('order')
            ->fetchAll('id');
    }

    /**
     * 获取看板列。
     * Get column by id.
     *
     * @param  int    $columnID
     * @access public
     * @return object
     */
    public function getColumnByID(int $columnID)
    {
        $column = $this->dao->select('t1.*, t2.type as laneType')->from(TABLE_KANBANCOLUMN)->alias('t1')
            ->leftJoin(TABLE_KANBANCELL)->alias('t2')->on('t1.id=t2.column')
            ->where('t1.id')->eq($columnID)
            ->fetch();

        if(empty($column)) return false;
        if($column->parent > 0) $column->parentName = $this->dao->findById($column->parent)->from(TABLE_KANBANCOLUMN)->fetch('name');

        return $column;
    }

    /**
     * 根据字段获取看板列。
     * Get columns by field.
     *
     * @param  string $field    parent|region|group
     * @param  int    $fieldID
     * @param  string $archived
     * @param  string $deleted
     * @access public
     * @return array
     */
    public function getColumnsByField(string $field = '', int $fieldID = 0, string $archived = '0', string $deleted = '0'): array
    {
        return $this->dao->select('*')->from(TABLE_KANBANCOLUMN)
            ->where('1 = 1')
            ->beginIF($field)->andWhere($field)->eq($fieldID)->fi()
            ->beginIF($archived != '')->andWhere('archived')->eq($archived)->fi()
            ->beginIF($deleted != '')->andWhere('deleted')->eq($deleted)->fi()
            ->orderBy('order')
            ->fetchAll('id');
    }

    /**
     * 获取泳道的看板列ID。
     * Get column ID by lane ID.
     *
     * @param  int    $laneID
     * @param  string $columnType
     * @access public
     * @return string|int
     */
    public function getColumnIDByLaneID(int $laneID, string $columnType)
    {
        return $this->dao->select('t1.column')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column = t2.id')
            ->where('t1.lane')->eq($laneID)
            ->andWhere('t2.type')->eq($columnType)
            ->fetch('column');
    }

    /**
     * 根据ID获取看板泳道。
     * Get lane by id.
     *
     * @param  int    $laneID
     * @access public
     * @return object
     */
    public function getLaneById(int $laneID)
    {
        return $this->dao->findById($laneID)->from(TABLE_KANBANLANE)->fetch();
    }

    /**
     * 获取看板卡片。
     * Get card by id.
     *
     * @param  int    $cardID
     * @access public
     * @return object|false
     */
    public function getCardByID(int $cardID): object|false
    {
        $card = $this->dao->select('*')->from(TABLE_KANBANCARD)->where('id')->eq($cardID)->fetch();
        if(!$card) return false;

        $card = $this->loadModel('file')->replaceImgURL($card, 'desc');

        return $card;
    }

    /**
     * 获取某个对象的看板卡片。
     * Get cards by object id.
     *
     * @param  string     $objectType kanban|region|group|lane|column
     * @param  int|string $objectID
     * @param  string     $archived
     * @param  string     $deleted
     * @access public
     * @return array
     */
    public function getCardsByObject(string $objectType = '', int|string $objectID = 0, string $archived = '0', string $deleted = '0')
    {
        return $this->dao->select('*')->from(TABLE_KANBANCARD)
            ->where('1 = 1')
            ->beginIF($objectType)->andWhere($objectType)->eq($objectID)->fi()
            ->beginIF($archived != '')->andWhere('archived')->eq($archived)->fi()
            ->beginIF($deleted != '')->andWhere('deleted')->eq($deleted)->fi()
            ->orderBy('order')
            ->fetchAll('id');
    }

    /**
     * 获取可转入的看板卡片。
     * Get cards to import.
     *
     * @param  int    $kanbanID
     * @param  int    $excludedID
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getCards2Import(int $kanbanID = 0, int $excludedID = 0, object $pager = null): array
    {
        $kanbanIdList = $this->getCanViewObjects();

        return $this->dao->select('*')->from(TABLE_KANBANCARD)
            ->where('deleted')->eq(0)
            ->andWhere('archived')->eq(0)
            ->andWhere('fromID')->eq(0)
            ->andWhere('kanban')->in($kanbanIdList)
            ->beginIF($kanbanID)->andWhere('kanban')->eq($kanbanID)->fi()
            ->beginIF($excludedID)->andWhere('kanban')->ne($excludedID)->fi()
            ->orderBy('order')
            ->page($pager)
            ->fetchAll('id');
    }

    /**
     * 获取看板卡片的操作按钮。
     * Get Kanban cards menus by execution id.
     *
     * @param  int    $executionID
     * @param  array  $objects
     * @param  string $objecType   story|bug|task
     * @access public
     * @return array
     */
    public function getKanbanCardMenu(int $executionID, array $objects, string $objecType): array
    {
        $this->app->loadLang('execution');

        $menus = array();
        switch ($objecType)
        {
            case 'story':
                $menus = $this->kanbanTao->getStoryCardMenu($executionID, $objects);
                break;
            case 'bug':
                $menus = $this->kanbanTao->getBugCardMenu($objects);
                break;
            case 'task':
                $menus = $this->kanbanTao->getTaskCardMenu($objects);
                break;
        }
        return $menus;
    }

    /**
     * 获取卡片的发送人和抄送人。
     * Get toList and ccList.
     *
     * @param  object    $card
     * @access public
     * @return bool|array
     */
    public function getToAndCcList(object $card): array|bool
    {
        /* Set toList and ccList. */
        $toList = $card->createdBy;
        $ccList = trim($card->assignedTo, ',');

        if(empty($toList))
        {
            if(empty($ccList)) return false;
            if(strpos($ccList, ',') === false)
            {
                $toList = $ccList;
                $ccList = '';
            }
            else
            {
                $commaPos = strpos($ccList, ',');
                $toList   = substr($ccList, 0, $commaPos);
                $ccList   = substr($ccList, $commaPos + 1);
            }
        }

        return array($toList, $ccList);
    }

    /**
     * 检查操作按钮是否可点。
     * Check if user can execute an action.
     *
     * @param  object $object
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable(object $object, string $action): bool
    {
        $action    = strtolower($action);
        $clickable = commonModel::hasPriv('kanban', $action);
        if(!$clickable) return false;

        switch($action)
        {
            case 'sortlane' :
            case 'deletelane' :
                if($object->deleted != '0') return false;

                $count = $this->dao->select('COUNT(id) AS count')->from(TABLE_KANBANLANE)
                    ->where('deleted')->eq('0')
                    ->andWhere('region')->eq($object->region)
                    ->beginIF($action == 'sortlane')->andWhere('`group`')->eq($object->group)->fi()
                    ->fetch('count');
                return $count > 1;
            case 'splitcolumn' :
                return $object->parent == 0;
            case 'restorecolumn' :
                if($object->parent > 0)
                {
                    $parent = $this->getColumnByID($object->parent);
                    if(!empty($parent) and $parent->deleted == '1' || $parent->archived == '1') return false;
                }
                return $object->archived == '1';
            case 'archivecolumn' :
                return $object->archived == '0';    // The column has been archived.
            case 'deletecolumn' :
                return $object->deleted == '0';
            case 'sortcolumn':
                return $object->deleted == '0';
            case 'finishcard':
                return $object->status != 'done';
            case 'activatecard':
                return $object->status == 'done';
            case 'deletecard':
                return $object->deleted == '0';
            case 'archivecard':
                return $object->archived == '0';
            case 'restorecard':
                return $object->archived == '1';
        }

        return true;
    }

    /**
     * 获取泳道的数量。
     * Get kanban lane count.
     *
     * @param  int    $kanbanID
     * @param  string $type
     * @access public
     * @return int
     */
    public function getLaneCount(int $kanbanID, string $type = 'common')
    {
        if($type == 'common' or $type == 'kanban')
        {
            return $this->dao->select('COUNT(t2.id) as count')->from(TABLE_KANBANREGION)->alias('t1')
                ->leftJoin(TABLE_KANBANLANE)->alias('t2')->on('t1.id=t2.region')
                ->where('t1.kanban')->eq($kanbanID)
                ->andWhere('t1.deleted')->eq(0)
                ->andWhere('t2.deleted')->eq(0)
                ->beginIF($type == 'common')->andWhere('t2.type')->eq('common')->fi()
                ->beginIF($type != 'common')->andWhere('t2.type')->ne('common')->fi()
                ->fetch('count');
        }
        else
        {
            return $this->dao->select('COUNT(id) as count')->from(TABLE_KANBANLANE)
                ->where('execution')->eq($kanbanID)
                ->andWhere('deleted')->eq(0)
                ->fetch('count');
        }
    }

    /**
     * 检查卡片数量是否合法。
     * Check display card count.
     *
     * @param  int    $count
     * @access public
     * @return bool
     */
    public function checkDisplayCards(int $count): bool
    {
        if(!preg_match("/^-?\d+$/", $count) or $count <= DEFAULT_CARDCOUNT or $count > MAX_CARDCOUNT) dao::$errors['displayCards'] = $this->lang->kanbanlane->error->mustBeInt;
        return !dao::isError();
    }

    /**
     * 获取看板动态刷新的回调函数数组。
     * Get kanban callback data.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @access public
     * @return void
     */
    public function getKanbanCallback(int $kanbanID, int $regionID)
    {
        $kanbanData = $this->getKanbanData($kanbanID, $regionID);
        $kanbanData = reset($kanbanData);
        return array('name' => 'updateKanbanRegion', 'params' => array('region' . $regionID, $kanbanData));
    }
}
