<?php
/**
 * The model file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
     * Create a kanban group.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @access public
     * @return int
     */
    public function createGroup($kanbanID, $regionID)
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
     * Create a default kanban region.
     *
     * @param  object $kanban
     * @access public
     * @return int
     */
    public function createDefaultRegion($kanban)
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
     * Copy kanban regions.
     *
     * @param  object $kanban
     * @param  int    $copyKanbanID
     * @param  string $from kanban|execution
     * @param  string $param
     * @access public
     * @return void
     */
    public function copyRegions($kanban, $copyKanbanID, $from = 'kanban', $param = 'withArchived')
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
     * Create a new region.
     *
     * @param  object $kanban
     * @param  object $region
     * @param  int    $copyRegionID
     * @param  string $from kanban|execution
     * @param  string $param
     * @access public
     * @return int
     */
    public function createRegion($kanban, $region = null, $copyRegionID = 0, $from = 'kanban', $param = '')
    {
        $account = $this->app->user->account;
        $order   = 1;

        if(!$region)
        {
            $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANREGION)
                ->where('kanban')->eq($kanban->id)
                ->fetch('maxOrder');

            $order  = $maxOrder ? $maxOrder + 1 : 1;
            $region = fixer::input('post')
                ->add('kanban', $kanban->id)
                ->add('createdBy', $account)
                ->add('createdDate', helper::now())
                ->trim('name')
                ->get();
            $region->space = $from == 'kanban' ? $kanban->space : 0;
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
            /* Gets the groups, lanes and columns of the replication region. */
            $copyGroups      = $this->getGroupGroupByRegions($copyRegionID);
            $copyLaneGroup   = $this->getLaneGroupByRegions($copyRegionID);
            $copyColumnGroup = $this->getColumnGroupByRegions($copyRegionID, 'id_asc', $param);

            /* Create groups, lanes, and columns. */
            if(empty($copyGroups)) return $regionID;

            foreach($copyGroups[$copyRegionID] as $copyGroupID => $copyGroup)
            {
                $newGroupID = $this->createGroup($kanban->id, $regionID);
                if(dao::isError()) return false;

                $copyLanes     = isset($copyLaneGroup[$copyGroupID]) ? $copyLaneGroup[$copyGroupID] : array();
                $copyColumns   = isset($copyColumnGroup[$copyGroupID]) ? $copyColumnGroup[$copyGroupID] : array();
                $parentColumns = array();
                $lanePairs     = array();
                foreach($copyLanes as $copyLane)
                {
                    $laneID = $copyLane->id;
                    unset($copyLane->id);
                    unset($copyLane->actions);
                    $copyLane->region         = $regionID;
                    $copyLane->group          = $newGroupID;
                    $copyLane->lastEditedTime = helper::now();
                    $lanePairs[$laneID] = $this->createLane($kanban->id, $regionID, $copyLane);
                    if(dao::isError()) return false;
                }

                foreach($copyColumns as $copyColumn)
                {
                    $copyColumnID = $copyColumn->id;
                    unset($copyColumn->id);
                    unset($copyColumn->actions);
                    unset($copyColumn->asParent);
                    unset($copyColumn->parentType);

                    $copyColumn->region = $regionID;
                    $copyColumn->group  = $newGroupID;

                    if($copyColumn->parent > 0 and isset($parentColumns[$copyColumn->parent]))
                    {
                        $copyColumn->parent = $parentColumns[$copyColumn->parent];
                    }

                    $parentColumnID = $this->createColumn($regionID, $copyColumn, 0, 0, $from);

                    if($copyColumn->parent < 0) $parentColumns[$copyColumnID] = $parentColumnID;
                    if(dao::isError()) return false;
                }

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
        elseif($from == 'kanban')
        {
            $groupID = $this->createGroup($kanban->id, $regionID);
            if(dao::isError()) return false;

            $this->createDefaultLane($kanban, $regionID, $groupID);
            if(dao::isError()) return false;

            $this->createDefaultColumns($kanban, $regionID, $groupID);
            if(dao::isError()) return false;
        }

        return $regionID;
    }

    /**
     * Create default lane.
     *
     * @param  object $kanban
     * @param  int    $regionID
     * @param  int    $groupID
     * @access public
     * @return int
     */
    public function createDefaultLane($kanban, $regionID, $groupID)
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
     * Create default kanban columns.
     *
     * @param  object $kanban
     * @param  int    $regionID
     * @param  int    $groupID
     * @access public
     * @return void
     */
    public function createDefaultColumns($kanban, $regionID, $groupID)
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

            $this->createColumn($regionID, $column);
            $order ++;
        }

        return !dao::isError();
    }

    /**
     * Create a column.
     *
     * @param  int    $regionID
     * @param  object $column
     * @param  int    $order
     * @param  int    $parent
     * @param  string $from kanban|execution
     * @access public
     * @return int
     */
    public function createColumn($regionID, $column = null, $order = 0, $parent = 0, $from = 'kanban')
    {
        if(empty($column))
        {
            $column = fixer::input('post')
                ->add('region', $regionID)
                ->setIF($parent > 0, 'parent', $parent)
                ->setIF($order, 'order', $order)
                ->setDefault('color', '#333')
                ->trim('name')
                ->remove('WIPCount,noLimit')
                ->get();

            if(!$order)
            {
                $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANCOLUMN)
                    ->where('`group`')->eq($column->group)
                    ->fetch('maxOrder');
                $column->order = $maxOrder ? $maxOrder + 1 : 1;
            }

            if(!$column->limit && empty($_POST['noLimit'])) dao::$errors['limit'][] = sprintf($this->lang->error->notempty, $this->lang->kanban->WIP);
            if(!preg_match("/^-?\d+$/", $column->limit) or (!isset($_POST['noLimit']) and $column->limit <= 0))
            {
                dao::$errors['limit'] = $this->lang->kanban->error->mustBeInt;
                return false;
            }
            if(dao::isError()) return false;
        }

        $column->limit = (int)$column->limit;

        $limit = $column->limit;
        if(isset($column->parent) and $column->parent > 0)
        {
            /* Create a child column. */
            $parentColumn = $this->getColumnByID($column->parent);
            if($parentColumn->limit != -1)
            {
                /* The WIP of the child column is infinite or greater than the WIP of the parent column. */
                $sumChildLimit = $this->dao->select('SUM(`limit`) AS sumChildLimit')->from(TABLE_KANBANCOLUMN)->where('parent')->eq($column->parent)->andWhere('deleted')->eq(0)->fetch('sumChildLimit');
                if($limit == -1 or (($limit + $sumChildLimit) > $parentColumn->limit))
                {
                    dao::$errors['limit'][] = $this->lang->kanban->error->parentLimitNote;
                    return false;
                }

                $childColumns = $this->getColumnsByObject('parent', $column->parent);
                foreach($childColumns as $childColumn)
                {
                    $limit += (int)$childColumn->limit;
                    if($limit > $parentColumn->limit and $parentColumn->limit != -1)
                    {
                        /* The total WIP of the child columns is greater than the WIP of the parent column. */
                        dao::$errors['limit'][] = $this->lang->kanban->error->childLimitNote;
                        return false;
                    }
                }
            }
        }

        if($order)
        {
            /* It means copy a column or insert a column before or after a column. */
            $this->dao->update(TABLE_KANBANCOLUMN)
                ->set('`order` = `order` + 1')
                ->where('`group`')->eq($column->group)
                ->andWhere('`order`')->ge($order)
                ->exec();
        }

        $this->dao->insert(TABLE_KANBANCOLUMN)->data($column, 'noLimit,position,copyItems')
            ->batchCheck($this->config->kanban->require->createcolumn, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $columnID = $this->dao->lastInsertID();

        if($from == 'kanban') $this->dao->update(TABLE_KANBANCOLUMN)->set('type')->eq("column{$columnID}")->where('id')->eq($columnID)->exec();

        /* Add kanban cell. */
        $lanes    = $this->dao->select('id,type')->from(TABLE_KANBANLANE)->where('`group`')->eq($column->group)->fetchPairs();
        $kanbanID = $this->dao->select('kanban')->from(TABLE_KANBANREGION)->where('id')->eq($regionID)->fetch('kanban');
        foreach($lanes as $laneID => $laneType) $this->addKanbanCell($kanbanID, $laneID, $columnID, $laneType);

        return $columnID;
    }

    /**
     * Split column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function splitColumn($columnID)
    {
        $this->loadModel('action');
        $data          = fixer::input('post')->get();
        $column        = $this->getColumnByID($columnID);
        $maxOrder      = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANCOLUMN)->where('`group`')->eq($column->group)->fetch('maxOrder');
        $order         = $maxOrder ? $maxOrder + 1 : 1;
        $sumChildLimit = 0;

        $childrenColumn = array();
        foreach($data->name as $i => $name)
        {
            $childColumn = new stdclass();
            $childColumn->parent = $column->id;
            $childColumn->region = $column->region;
            $childColumn->group  = $column->group;
            $childColumn->name   = $name;
            $childColumn->color  = $data->color[$i];
            $childColumn->limit  = isset($data->noLimit[$i]) ? -1 : $data->WIPCount[$i];
            $childColumn->order  = $order;

            if(empty($childColumn->name))
            {
                dao::$errors['name'] = sprintf($this->lang->error->notempty, $this->lang->kanbancolumn->name);
                return false;
            }

            if(!preg_match("/^-?\d+$/", $childColumn->limit) or (!isset($data->noLimit[$i]) and $childColumn->limit <= 0))
            {
                dao::$errors['limit'] = $this->lang->kanban->error->mustBeInt;
                return false;
            }

            $sumChildLimit += $childColumn->limit == -1 ? 0 : $childColumn->limit;
            if($column->limit != -1 and ($childColumn->limit == -1 or ($column->limit < $sumChildLimit)))
            {
                dao::$errors['limit'] = $this->lang->kanban->error->parentLimitNote;
                return false;
            }

            $order ++;
            $childrenColumn[$i] = $childColumn;
        }

        foreach($childrenColumn as $i => $childColumn)
        {
            $this->dao->insert(TABLE_KANBANCOLUMN)->data($childColumn)
                ->autoCheck()
                ->batchCheck($this->config->kanban->splitcolumn->requiredFields, 'notempty')
                ->exec();

            if(dao::isError()) return false;
            if(!dao::isError())
            {
                $childColumnID = $this->dao->lastInsertID();
                $this->dao->update(TABLE_KANBANCOLUMN)->set('type')->eq("column{$childColumnID}")->where('id')->eq($childColumnID)->exec();
                $this->action->create('kanbanColumn', $childColumnID, 'created');

                $cellList = $this->dao->select('*')->from(TABLE_KANBANCELL)->where('`column`')->eq($column->id)->fetchAll();
                foreach($cellList as $cell)
                {
                    $newCell = new stdclass();
                    $newCell->kanban = $cell->kanban;
                    $newCell->lane   = $cell->lane;
                    $newCell->column = $childColumnID;
                    $newCell->type   = 'common';
                    $newCell->cards  = $i == 1 ? $cell->cards : '';

                    $this->dao->insert(TABLE_KANBANCELL)->data($newCell)->exec();
                    $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq('')->where('id')->eq($cell->id)->exec();
                }
            }
        }

        $this->dao->update(TABLE_KANBANCOLUMN)->set('parent')->eq(-1)->where('id')->eq($columnID)->exec();
    }

    /**
     * Create a kanban card.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @access public
     * @return bool|int
     */
    public function createCard($kanbanID, $regionID, $groupID, $columnID)
    {
        $now  = helper::now();
        $card = fixer::input('post')
            ->add('kanban', $kanbanID)
            ->add('region', $regionID)
            ->add('group', $groupID)
            ->add('createdBy', $this->app->user->account)
            ->add('createdDate', $now)
            ->add('assignedDate', $now)
            ->add('color', '#fff')
            ->trim('name,estimate')
            ->setDefault('estimate,fromID', 0)
            ->setDefault('fromType', '')
            ->stripTags($this->config->kanban->editor->createcard['id'], $this->config->allowedTags)
            ->join('assignedTo', ',')
            ->setIF(is_numeric($this->post->estimate), 'estimate', (float)$this->post->estimate)
            ->remove('uid,lane')
            ->get();

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

        if(!$card->begin) unset($card->begin);
        if(!$card->end)   unset($card->end);

        $this->dao->insert(TABLE_KANBANCARD)->data($card)->autoCheck()
            ->checkIF($card->estimate != '', 'estimate', 'float')
            ->batchCheck($this->config->kanban->createcard->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $cardID = $this->dao->lastInsertID();
            $this->file->saveUpload('kanbancard', $cardID);
            $this->file->updateObjectID($this->post->uid, $cardID, 'kanbancard');
            $this->addKanbanCell($kanbanID, (int)$this->post->lane, $columnID, 'common', $cardID);

            return $cardID;
        }

        return false;
    }

    /**
     * Import card.
     *
     * @param  int $kanbanID
     * @param  int $regionID
     * @param  int $groupID
     * @param  int $columnID
     * @access public
     * @return bool|array
     */
    public function importCard($kanbanID, $regionID, $groupID, $columnID)
    {
        $data         = fixer::input('post')->get();
        $importIDList = $data->cards;
        $targetLaneID = $data->targetLane;
        $cardList     = $this->dao->select('*')->from(TABLE_KANBANCARD)->where('id')->in($importIDList)->fetchAll('id');

        $updateData = new stdClass();
        $updateData->kanban = $kanbanID;
        $updateData->region = $regionID;
        $updateData->group  = $groupID;
        $this->dao->update(TABLE_KANBANCARD)->data($updateData)->where('id')->in($importIDList)->exec();

        $kanban         = $this->getByID($kanbanID);
        $oldCardsKanban = array();
        $kanbanUsers    = trim($kanban->owner) . ',' . trim($kanban->team);
        $users          = $this->loadModel('user')->getPairs('noclosed|nodeleted', '', 0, $kanbanUsers);

        foreach($cardList as $cardID => $card)
        {
            $oldCardsKanban[$cardID] = $card->kanban;
            if(empty($card->assignedTo)) continue;
            $assignedToList = explode(',', $card->assignedTo);
            foreach($assignedToList as $index => $account)
            {
                if(!isset($users[$account])) unset($assignedToList[$index]);
            }

            $assignedTo = implode(',', $assignedToList);
            $assignedTo = trim($assignedTo, ',');

            if($card->assignedTo != $assignedTo)
            {
                $this->dao->update(TABLE_KANBANCARD)->set('assignedTo')->eq($assignedTo)->where('id')->eq($cardID)->exec();
            }
        }

        if(!dao::isError())
        {
            $this->removeKanbanCell('common', $importIDList, $oldCardsKanban);

            $cards = implode(',', $importIDList);
            $this->addKanbanCell($kanbanID, $targetLaneID, $columnID, 'common', $cards);

            return $importIDList;
        }

        return false;
    }

    /**
     * Import object.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @param  string $objectType
     * @access public
     * @return array
     */
    public function importObject($kanbanID, $regionID, $groupID, $columnID, $objectType)
    {
        $data = fixer::input('post')->get();
        $objectIDList = $data->{$objectType . 's'};
        $targetLaneID = $data->targetLane;

        $objectCards = array();
        $now         = helper::now();
        foreach($objectIDList as $objectID)
        {
            $cardData = new stdClass();
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
            $this->addKanbanCell($kanbanID, $targetLaneID, $columnID, 'common', $cards);

            return $objectCards;
        }

        return false;
    }

    /**
     * Batch create kanban cards.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $columnID
     * @access public
     * @return bool
     */
    public function batchCreateCard($kanbanID, $regionID, $groupID, $columnID)
    {
        foreach($_POST['name'] as $index => $value)
        {
            if($_POST['name'][$index] and isset($_POST['assignedTo'][$index])) $_POST['assignedTo'][$index] = implode(',', $_POST['assignedTo'][$index]);
            if(!isset($_POST['assignedTo'][$index])) $_POST['assignedTo'][$index] = '';

            $_POST['estimate'][$index] = trim($_POST['estimate'][$index]);
            if(empty($_POST['estimate'][$index])) $_POST['estimate'][$index] = 0;
        }
        $cards = fixer::input('post')->get();

        $now   = helper::now();
        $data  = array();
        $lanes = array();
        $lane  = '';
        $begin = '0000-00-00';
        $end   = '0000-00-00';

        foreach($cards->name as $i => $name)
        {
            $lane  = ($cards->lane[$i] == 'ditto')   ? $lane  : $cards->lane[$i];
            $begin = (isset($cards->beginDitto[$i])) ? $begin : $cards->begin[$i];
            $end   = (isset($cards->endDitto[$i]))   ? $end   : $cards->end[$i];

            if(empty($cards->name[$i])) continue;
            $data[$i]               = new stdclass();
            $data[$i]->name         = trim($cards->name[$i]);
            $data[$i]->begin        = $begin;
            $data[$i]->end          = $end;
            $data[$i]->assignedTo   = $cards->assignedTo[$i];
            $data[$i]->desc         = nl2br($cards->desc[$i]);
            $data[$i]->pri          = $cards->pri[$i];
            $data[$i]->kanban       = $kanbanID;
            $data[$i]->region       = $regionID;
            $data[$i]->group        = $groupID;
            $data[$i]->createdBy    = $this->app->user->account;
            $data[$i]->createdDate  = $now;
            $data[$i]->assignedDate = $now;
            $data[$i]->color        = '#fff';
            $data[$i]->estimate     = is_numeric($cards->estimate[$i]) ? (float)$cards->estimate[$i] : $cards->estimate[$i];

            $lanes[$i] = $lane;
        }

        foreach($data as $i => $card)
        {
            $this->dao->insert(TABLE_KANBANCARD)->data($card)->autoCheck()
                ->checkIF($card->estimate != '', 'estimate', 'float')
                ->batchCheck($this->config->kanban->createcard->requiredFields, 'notempty')
                ->exec();

            if($card->estimate < 0)
            {
                dao::$errors[] = $this->lang->kanbancard->error->recordMinus;
                return false;
            }
            if($card->end && $card->begin > $card->end)
            {
                dao::$errors[] = $this->lang->kanbancard->error->endSmall;
                return false;
            }

            if(!dao::isError())
            {
                $cardID = $this->dao->lastInsertID();
                $lane = $lanes[$i];
                $this->addKanbanCell($kanbanID, $lane, $columnID, 'common', $cardID);
                $this->loadModel('action')->create('kanbancard', $cardID, 'created');
            }
        }
    }

    /**
     * Get kanban by id.
     *
     * @param  int    $kanbanID
     * @access public
     * @return object
     */
    public function getByID($kanbanID)
    {
        $kanban = $this->dao->findByID($kanbanID)->from(TABLE_KANBAN)->fetch();
        $kanban = $this->loadModel('file')->replaceImgURL($kanban, 'desc');

        return $kanban;
    }

    /**
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
     * Get kanban data.
     *
     * @param  int    $kanbanID
     * @access public
     * @return array
     */
    public function getKanbanData($kanbanID, $regionIDList = '')
    {
        $kanbanData   = array();
        $actions      = array('sortGroup');
        $regions      = $this->getRegionPairs($kanbanID);
        $singleRegion = false;

        if(empty($regionIDList))
        {
            $regionIDList = array_keys($regions);
        }
        else if(!is_array($regionIDList))
        {
            $singleRegion = $regionIDList;
            $regionIDList = array($regionIDList);
        }

        $groupGroup  = $this->getGroupGroupByRegions($regionIDList);
        $laneGroup   = $this->getLaneGroupByRegions($regionIDList);
        $columnGroup = $this->getColumnGroupByRegions($regionIDList);
        $cardGroup   = $this->getCardGroupByKanban($kanbanID);

        foreach($regionIDList as $regionID)
        {
            $region = new stdclass();
            $region->id        = $regionID;
            $region->name      = $regions[$regionID];
            $region->laneCount = 0;

            $groups = zget($groupGroup, $regionID, array());
            foreach($groups as $group)
            {
                $lanes = zget($laneGroup, $group->id, array());
                if(!$lanes) continue;

                foreach($lanes as $lane) $lane->items = isset($cardGroup[$lane->id]) ? $cardGroup[$lane->id] : array();

                $group->columns = zget($columnGroup, $group->id, array());
                $group->lanes   = $lanes;
                $group->actions = array();

                foreach($actions as $action)
                {
                    if(commonModel::hasPriv('kanban', $action)) $group->actions[] = $action;
                }

                $region->groups[]   = $group;
                $region->laneCount += count($lanes);
            }

            $kanbanData[$regionID] = $region;
        }

        return $singleRegion ? $kanbanData[$singleRegion] : $kanbanData;
    }

    /**
     * Get plan kanban.
     *
     * @param  object $product
     * @param  int    $branchID
     * @param  array  $planGroup
     * @access public
     * @return array
     */
    public function getPlanKanban($product, $branchID = 0, $planGroup = '')
    {
        $this->loadModel('branch');
        $this->loadModel('productplan');

        $kanbanData  = new stdclass();
        $lanes       = array();
        $columns     = array();
        $branches    = array();
        $colorIndex  = 0;
        $laneOrder   = 1;
        $cardActions = array('view', 'createExecution', 'linkStory', 'linkBug', 'edit', 'start', 'finish', 'close', 'activate', 'delete');

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

        foreach($branches as $id => $name)
        {
            if($product->type != 'normal') $plans = isset($planGroup[$product->id][$id]) ? array_filter($planGroup[$product->id][$id]) : array();
            if($product->type == 'normal') $plans = $planGroup;
            $planList = array();

            foreach($plans as $planID => $plan)
            {
                if(empty($plan) or $plan->parent == -1) continue;
                if(!isset($planList[$plan->status])) $planList[$plan->status] = array();

                $plan->title   = htmlspecialchars_decode($plan->title);
                $plan->desc    = strip_tags(htmlspecialchars_decode($plan->desc));
                $plan->delay   = helper::today() > $plan->end ? true : false;
                $plan->actions = array();
                foreach($cardActions as $action)
                {
                    if($action == 'createExecution')
                    {
                        if(common::hasPriv('execution', 'create')) $plan->actions[] = $action;
                        continue;
                    }
                    if($this->productplan->isClickable($plan, $action)) $plan->actions[] = $action;
                }
                $planList[$plan->status][] = $plan;
            }

            $lane = new stdclass();
            $lane->id    = $id;
            $lane->type  = 'branch';
            $lane->name  = $name;
            $lane->color = $this->config->productplan->laneColorList[$colorIndex];
            $lane->order = $laneOrder;
            $lane->items = $planList;

            $lanes[] = $lane;
            $laneOrder ++;
            $colorIndex ++;
            if($colorIndex == count($this->config->productplan->laneColorList)) $colorIndex = 0;
        }

        foreach($this->lang->kanban->defaultColumn as $columnType => $columnName)
        {
            $column = new stdclass();
            $column->id   = $columnType;
            $column->type = $columnType;
            $column->name = $columnName;

            $columns[] = $column;
        }

        $kanbanData->id      = 'plans';
        $kanbanData->lanes   = $lanes;
        $kanbanData->columns = $columns;

        return $kanbanData;
    }

    /**
     * Get a RD kanban data.
     *
     * @param  int    $executionID
     * @param  string $browseType all|story|task|bug
     * @param  string $orderBy
     * @param  int    $regionID
     * @param  string $groupBy
     * @param  string $searchValue
     *
     * @access public
     * @return array
     */
    public function getRDKanban($executionID, $browseType = 'all', $orderBy = 'id_desc', $regionID = 0, $groupBy = 'default', $searchValue = '')
    {
        if($groupBy != 'default' and $groupBy != '') return $this->getKanban4Group($executionID, $browseType, $groupBy, $searchValue, $orderBy);

        $kanbanData   = array();
        $actions      = array('sortGroup');
        $regions      = $this->getRegionPairs($executionID, $regionID, 'execution');
        $regionIDList = $regionID == 0 ? array_keys($regions) : array(0 => $regionID);
        $groupGroup   = $this->getGroupGroupByRegions($regionIDList);
        $laneGroup    = $this->getLaneGroupByRegions($regionIDList, $browseType);
        $columnGroup  = $this->getRDColumnGroupByRegions($regionIDList, array_keys($laneGroup));
        $cardGroup    = $this->getCardGroupByExecution($executionID, $browseType, $orderBy, $searchValue);
        $execution    = $this->loadModel('execution')->getByID($executionID);

        foreach($regions as $regionID => $regionName)
        {
            $region = new stdclass();
            $region->id        = $regionID;
            $region->name      = $regionName;
            $region->laneCount = 0;

            $groups = zget($groupGroup, $regionID, array());
            foreach($groups as $group)
            {
                $lanes = zget($laneGroup, $group->id, array());
                if(!$lanes) continue;

                foreach($lanes as $key => $lane)
                {
                    if(in_array($execution->attribute, array('request', 'design', 'review')) and $lane->type == 'bug') continue 2;
                    if(in_array($execution->attribute, array('request', 'review')) and $lane->type == 'story') continue 2;
                    $this->refreshCards($lane);
                    $lane->items           = isset($cardGroup[$lane->id]) ? $cardGroup[$lane->id] : array();
                    $lane->defaultCardType = $lane->type;
                    if($searchValue != '' and count($lane->items) == 0) unset($lanes[$key]);
                }
                $lanes = array_values($lanes);

                if($searchValue != '' and empty($lanes)) continue;
                $group->columns = zget($columnGroup, $group->id, array());
                $group->lanes   = $lanes;
                $group->actions = array();

                foreach($actions as $action)
                {
                    if(commonModel::hasPriv('kanban', $action)) $group->actions[] = $action;
                }

                $region->groups[]   = $group;
                $region->laneCount += count($lanes);
            }

            $kanbanData[$regionID] = $region;
        }

        return $kanbanData;
    }

    /**
     * Get region by id.
     *
     * @param  int    $regionID
     * @access public
     * @return object
     */
    public function getRegionByID($regionID)
    {
        return $this->dao->findByID($regionID)->from(TABLE_KANBANREGION)->fetch();
    }

    /**
     * Get ordered region pairs.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  string $from kanban|execution
     * @access public
     * @return array
     */
    public function getRegionPairs($kanbanID, $regionID = 0, $from = 'kanban')
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
     * Get kanban id by region id.
     *
     * @param  int $regionID
     * @access public
     * @return int
     */
    public function getKanbanIDByRegion($regionID)
    {
        return $this->dao->select('kanban')->from(TABLE_KANBANREGION)->where('id')->eq($regionID)->fetch('kanban');
    }

    /**
     * Get kanban group by regions.
     *
     * @param  array $regions
     * @access public
     * @return array
     */
    public function getGroupGroupByRegions($regions)
    {
        return $this->dao->select('*')->from(TABLE_KANBANGROUP)
            ->where('region')->in($regions)
            ->orderBy('order')
            ->fetchGroup('region', 'id');
    }

    /**
     * Get lane group by regions.
     *
     * @param  array  $regions
     * @param  string $browseType
     * @access public
     * @return array
     */
    public function getLaneGroupByRegions($regions, $browseType = 'all')
    {
        $laneGroup = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regions)
            ->beginIf($browseType != 'all')->andWhere('type')->eq($browseType)->fi()
            ->orderBy('order')
            ->fetchGroup('group');

        $actions = array('sortLane', 'deleteLane', 'editLaneName', 'editLaneColor');
        foreach($laneGroup as $lanes)
        {
            foreach($lanes as $lane)
            {
                $lane->actions = array();
                $lane->name    = htmlspecialchars_decode($lane->name);
                foreach($actions as $action)
                {
                    if($this->isClickable($lane, $action)) $lane->actions[] = $action;
                }
            }
        }

        return $laneGroup;
    }

    /**
     * Get kanban lane pairs by group id.
     *
     * @param  int    $groupID
     * @param  string $orderBy
     * @access public
     * @return array
     */
    public function getLanePairsByGroup($groupID, $orderBy = '`order`_asc')
    {
        return $this->dao->select('id,name')->from(TABLE_KANBANLANE)
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
    public function getColumnGroupByRegions($regions, $order = 'order', $param = '')
    {
        $columnGroup = $this->dao->select("*")->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regions)
            ->beginIF(strpos(",$param,", ',withArchived,') === false)->andWhere('archived')->eq('0')->fi()
            ->orderBy($order)
            ->fetchGroup('group');

        $actions = array('createColumn', 'setColumn', 'setWIP', 'archiveColumn', 'restoreColumn', 'deleteColumn', 'createCard', 'batchCreateCard', 'splitColumn', 'sortColumn');

        /* Group by parent. */
        $parentColumnGroup = array();
        foreach($columnGroup as $group => $columns)
        {
            foreach($columns as $column)
            {
                $column->name    = htmlspecialchars_decode($column->name);
                $column->actions = array();

                /* Judge column action priv. */
                foreach($actions as $action)
                {
                    if($this->isClickable($column, $action)) $column->actions[] = $action;
                }

                if($column->parent > 0) continue;

                $parentColumnGroup[$group][] = $column;
            }
        }

        $columnData = array();
        foreach($parentColumnGroup as $group => $parentColumns)
        {
            foreach($parentColumns as $parentColumn)
            {
                $columnData[$group][] = $parentColumn;
                foreach($columnGroup[$group] as $column)
                {
                    if($column->parent == $parentColumn->id)
                    {
                        $parentColumn->asParent = true;

                        $column->parentType = 'column' . $column->parent;

                        $columnData[$group][] = $column;
                    }
                }
            }
        }

        return $columnData;
    }

    /**
     * Get card group by kanban id.
     *
     * @param  int    $kanbanID
     * @access public
     * @return array
     */
    public function getCardGroupByKanban($kanbanID)
    {
        /* Get card data.*/
        $cards = $this->dao->select('*')->from(TABLE_KANBANCARD)
            ->where('deleted')->eq(0)
            ->andWhere('kanban')->eq($kanbanID)
            ->andWhere('archived')->eq(0)
            ->andWhere('fromID')->eq(0)
            ->fetchAll('id');

        foreach($this->config->kanban->fromType as $fromType)
        {
            $cards = $this->getImportedCards($kanbanID, $cards, $fromType);
        }

        $cellList = $this->dao->select('*')->from(TABLE_KANBANCELL)
            ->where('kanban')->eq($kanbanID)
            ->andWhere('type')->eq('common')
            ->fetchAll();

        $actions   = array('editCard', 'archiveCard', 'deleteCard', 'moveCard', 'setCardColor', 'viewCard', 'sortCard', 'viewExecution', 'viewPlan', 'viewRelease', 'viewBuild', 'viewTicket');
        $cardGroup = array();
        foreach($cellList as $cell)
        {
            $cardIdList = array_filter(explode(',', $cell->cards));

            if(empty($cardIdList)) continue;
            foreach($cardIdList as $cardID)
            {
                if(!isset($cards[$cardID])) continue;

                $card         = zget($cards, $cardID);
                $card->column = $cell->column;
                $card->lane   = $cell->lane;
                $card->name   = htmlspecialchars_decode($card->name);

                $card->actions = array();
                foreach($actions as $action)
                {
                    if(in_array($action, array('viewExecution', 'viewPlan', 'viewRelease', 'viewBuild', 'viewTicket')))
                    {
                        if($card->fromType == 'execution')
                        {
                            if($card->execType == 'kanban' and common::hasPriv('execution', 'kanban')) $card->actions[] = $action;
                            if($card->execType != 'kanban' and common::hasPriv('execution', 'view')) $card->actions[] = $action;
                        }
                        else
                        {
                            if(common::hasPriv($fromType, 'view')) $card->actions[] = $action;
                        }
                        continue;
                    }
                    if(common::hasPriv('kanban', $action)) $card->actions[] = $action;
                }

                $cardGroup[$cell->lane]['column' . $cell->column][] = $card;
            }
        }

        return $cardGroup;
    }

    /**
     * Get imported cards.
     *
     * @param  int    $kanbanID
     * @param  object $cards
     * @param  string $fromType
     * @param  int    $archived
     * @param  int    $regionID
     * @access public
     * @return array
     */
    public function getImportedCards($kanbanID, $cards, $fromType, $archived = 0, $regionID = 0)
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

            if($fromType == 'productplan' or $fromType == 'release')
            {
                $creators = $this->dao->select('objectID, actor')->from(TABLE_ACTION)
                    ->where('objectID')->in(array_keys($objectCards))
                    ->andWhere('objectType')->eq($fromType)
                    ->andWhere('action')->eq('opened')
                    ->fetchPairs();
            }
            elseif($fromType == 'execution')
            {
                $executionProgress = $this->loadModel('project')->computerProgress($objects);
            }

            /* Data for constructing the card. */
            foreach($objectCards as $objectID => $cardsInfo)
            {
                foreach($cardsInfo as $cardID => $objectCard)
                {
                    $object    = $objects[$objectID];
                    $fieldType = $fromType . 'Field';

                    foreach($this->config->kanban->$fieldType as $field) $objectCard->$field = $object->$field;

                    if($fromType == 'productplan' or $fromType == 'release')
                    {
                        $objectCard->createdBy = zget($creators, $object->id, '');
                    }

                    if($fromType =='execution')
                    {
                        if($object->status != 'done' and $object->status != 'closed' and $object->status != 'suspended')
                        {
                            $delay = helper::diffDate(helper::today(), $object->end);
                            if($delay > 0) $objectCard->delay = $delay;
                        }
                        $objectCard->execType = $object->type;
                        $objectCard->progress = isset($executionProgress[$objectID]->progress) ? $executionProgress[$objectID]->progress : 0;

                        $parentExecutions  = $this->dao->select('id,name')->from(TABLE_EXECUTION)->where('id')->in(trim($object->path, ','))->andWhere('type')->in('stage,kanban,sprint')->orderBy('grade')->fetchPairs();
                        $objectCard->title = implode('/', $parentExecutions);

                        $children             = $this->dao->select('count(1) as children')->from(TABLE_EXECUTION)->where('parent')->eq($object->id)->andWhere('type')->in('stage,kanban,sprint')->andWhere('deleted')->eq(0)->fetch('children');
                        $objectCard->children = !empty($children) ? $children : 0;
                    }

                    $objectCard->desc         = strip_tags(htmlspecialchars_decode($object->desc));
                    $objectCard->objectStatus = $objectCard->status;
                    $objectCard->status       = $objectCard->progress == 100 ? 'done' : 'doing';
                    $cards[$cardID] = $objectCard;
                }
            }
        }
        return $cards;
    }

    /**
     * Get RD column group by regions.
     *
     * @param  array  $regions
     * @param  array  $groupIDList
     * @access public
     * @return array
     */
    public function getRDColumnGroupByRegions($regions, $groupIDList = array())
    {
        $columnGroup = $this->dao->select("*")->from(TABLE_KANBANCOLUMN)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regions)
            ->beginIF(!empty($groupIDList))->andWhere('`group`')->in($groupIDList)->fi()
            ->orderBy('id_asc')
            ->fetchGroup('group');

        $actions = array('setColumn', 'setWIP', 'deleteColumn');

        /* Group by parent. */
        $parentColumnGroup = array();
        foreach($columnGroup as $group => $columns)
        {
            foreach($columns as $column)
            {
                $column->actions = array();
                /* Judge column action priv. */
                foreach($actions as $action)
                {
                    if($this->isClickable($column, $action)) $column->actions[] = $action;
                }

                if($column->parent > 0) continue;

                $parentColumnGroup[$group][] = $column;
            }
        }

        $columnData = array();
        foreach($parentColumnGroup as $group => $parentColumns)
        {
            foreach($parentColumns as $parentColumn)
            {
                $columnData[$group][] = $parentColumn;
                foreach($columnGroup[$group] as $column)
                {
                    if($column->parent == $parentColumn->id)
                    {
                        $parentColumn->asParent = true;

                        if(strpos(',developing,developed,', $column->type) !== false) $column->parentType = 'develop';
                        if(strpos(',testing,tested,',       $column->type) !== false) $column->parentType = 'test';
                        if(strpos(',fixing,fixed,',         $column->type) !== false) $column->parentType = 'resolving';

                        $columnData[$group][] = $column;
                    }
                }
            }
        }

        return $columnData;
    }

    /**
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
    public function getCardGroupByExecution($executionID, $browseType = 'all', $orderBy = 'id_asc', $searchValue = '')
    {
        $cards = $this->dao->select('t1.*, t2.type as columnType')
            ->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column=t2.id')
            ->where('t1.kanban')->eq($executionID)
            ->beginIF($browseType != 'all')->andWhere('t1.type')->eq($browseType)->fi()
            ->orderby($orderBy)
            ->fetchgroup('lane', 'column');

        /* Get group objects. */
        if($browseType == 'all' or $browseType == 'story') $objectGroup['story'] = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, 't1.`order`_desc', 'allStory');
        if($browseType == 'all' or $browseType == 'bug')   $objectGroup['bug']   = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($browseType == 'all' or $browseType == 'task')  $objectGroup['task']  = $this->loadModel('execution')->getKanbanTasks($executionID, "id");

        $cardGroup = array();

        foreach($cards as $laneID => $cells)
        {
            foreach($cells as $columnID => $cell)
            {
                $cardIdList = array_filter(explode(',', $cell->cards));
                $cardOrder  = 1;
                foreach($cardIdList as $cardID)
                {
                    $cardData = array();
                    $objects  = zget($objectGroup, $cell->type, array());
                    $object   = zget($objects, $cardID, array());

                    if(empty($object)) continue;

                    $cardData['id']             = $object->id;
                    $cardData['order']          = $cardOrder++;
                    $cardData['pri']            = $object->pri ? $object->pri : '';
                    $cardData['estimate']       = $cell->type == 'bug' ? '' : $object->estimate;
                    $cardData['assignedTo']     = $object->assignedTo;
                    $cardData['deadline']       = $cell->type == 'story' ? '' : $object->deadline;
                    $cardData['severity']       = $cell->type == 'bug' ? $object->severity : '';
                    $cardData['acl']            = 'open';
                    $cardData['lane']           = $laneID;
                    $cardData['column']         = $cell->column;
                    $cardData['openedDate']     = $object->openedDate;
                    $cardData['closedDate']     = $object->closedDate;
                    $cardData['lastEditedDate'] = $object->lastEditedDate;
                    $cardData['status']         = $object->status;

                    if($cell->type == 'task')
                    {
                        if($searchValue != '' and strpos($object->name, $searchValue) === false) continue;
                        $cardData['name']       = $object->name;
                        $cardData['left']       = $object->left;
                        $cardData['estStarted'] = $object->estStarted;
                        $cardData['mode']       = $object->mode;
                    }
                    else
                    {
                        if($searchValue != '' and strpos($object->title, $searchValue) === false) continue;
                        $cardData['title'] = $object->title;
                    }
                    $cardGroup[$laneID][$cell->columnType][] = $cardData;
                }
            }
        }

        return $cardGroup;
    }

    /**
     * Get Kanban by execution id.
     *
     * @param  int    $executionID
     * @param  string $browseType all|story|bug|task
     * @param  string $groupBy
     * @param  string $searchValue
     * @access public
     * @return array
     */
    public function getExecutionKanban($executionID, $browseType = 'all', $groupBy = 'default', $searchValue = '', $orderBy = 'id_asc')
    {
        if($groupBy != 'default') return $this->getKanban4Group($executionID, $browseType, $groupBy, $searchValue, $orderBy);

        $lanes = $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('deleted')->eq(0)
            ->beginIF($browseType != 'all')->andWhere('type')->eq($browseType)->fi()
            ->orderBy('order_asc')
            ->fetchAll('id');

        if(empty($lanes)) return array();

        foreach($lanes as $lane) $this->refreshCards($lane);

        $columns = $this->dao->select('t1.cards, t1.lane, t2.id, t2.type, t2.name, t2.color, t2.limit, t2.parent')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column = t2.id')
            ->where('t2.deleted')->eq(0)
            ->andWhere('t1.lane')->in(array_keys($lanes))
            ->orderBy('id_asc')
            ->fetchGroup('lane', 'id');

        /* Get group objects. */
        if($browseType == 'all' or $browseType == 'story') $objectGroup['story'] = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, 't1.`order`_desc', 'allStory');
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

            $laneData['id']              = $laneID;
            $laneData['laneID']          = $laneID;
            $laneData['name']            = $lane->name;
            $laneData['color']           = $lane->color;
            $laneData['order']           = $lane->order;
            $laneData['defaultCardType'] = $lane->type;

            foreach($columns[$laneID] as $columnID => $column)
            {
                $columnData[$column->id]['id']         = $columnID;
                $columnData[$column->id]['type']       = $column->type;
                $columnData[$column->id]['name']       = $column->name;
                $columnData[$column->id]['color']      = $column->color;
                $columnData[$column->id]['limit']      = $column->limit;
                $columnData[$column->id]['laneType']   = $lane->type;
                $columnData[$column->id]['asParent']   = $column->parent == -1 ? true : false;
                $columnData[$column->id]['parent']     = $column->parent;

                if($column->parent > 0)
                {
                    if($column->type == 'developing' or $column->type == 'developed') $columnData[$column->id]['parentType'] = 'develop';
                    if($column->type == 'testing' or $column->type == 'tested') $columnData[$column->id]['parentType'] = 'test';
                    if($column->type == 'fixing' or $column->type == 'fixed') $columnData[$column->id]['parentType'] = 'resolving';
                }

                $cardOrder  = 1;
                $cardIdList = array_filter(explode(',', $column->cards));
                foreach($cardIdList as $cardID)
                {
                    $cardData = array();
                    $objects  = zget($objectGroup, $lane->type, array());
                    $object   = zget($objects, $cardID, array());

                    if(empty($object)) continue;

                    $cardData['id']         = $object->id;
                    $cardData['order']      = $cardOrder;
                    $cardData['pri']        = $object->pri ? $object->pri : '';
                    $cardData['estimate']   = $lane->type == 'bug' ? '' : $object->estimate;
                    $cardData['assignedTo'] = $object->assignedTo;
                    $cardData['deadline']   = $lane->type == 'story' ? '' : $object->deadline;
                    $cardData['severity']   = $lane->type == 'bug' ? $object->severity : '';

                    if($lane->type == 'task')
                    {
                        if($searchValue != '' and strpos($object->name, $searchValue) === false) continue;
                        $cardData['name']       = $object->name;
                        $cardData['status']     = $object->status;
                        $cardData['left']       = $object->left;
                        $cardData['estStarted'] = $object->estStarted;
                        $cardData['mode']       = $object->mode;
                        if($object->mode == 'multi') $cardData['teamMembers'] = $object->teamMembers;
                    }
                    else
                    {
                        if($searchValue != '' and strpos($object->title, $searchValue) === false) continue;
                        $cardData['title'] = $object->title;
                    }

                    if($lane->type == 'story') $cardData['menus'] = $storyCardMenu[$object->id];
                    if($lane->type == 'bug')   $cardData['menus'] = $bugCardMenu[$object->id];
                    if($lane->type == 'task')  $cardData['menus'] = $taskCardMenu[$object->id];

                    $laneData['cards'][$column->type][] = $cardData;
                    $cardOrder ++;
                }
                if($searchValue == '' and !isset($laneData['cards'][$column->type])) $laneData['cards'][$column->type] = array();
            }

            if($searchValue != '' and empty($laneData['cards'])) continue;
            $kanbanGroup[$lane->type]['id']              = $laneID;
            $kanbanGroup[$lane->type]['columns']         = array_values($columnData);
            $kanbanGroup[$lane->type]['lanes'][]         = $laneData;
            $kanbanGroup[$lane->type]['defaultCardType'] = $lane->type;
        }

        return $kanbanGroup;
    }

    /**
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
    public function getKanban4Group($executionID, $browseType, $groupBy, $searchValue = '', $orderBy = 'id_asc')
    {
        /* Get card  data. */
        $cardList = array();
        if($browseType == 'story') $cardList = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, 't1.`order`_desc', 'allStory');
        if($browseType == 'bug')   $cardList = $this->loadModel('bug')->getExecutionBugs($executionID);
        if($browseType == 'task')  $cardList = $this->loadModel('execution')->getKanbanTasks($executionID);

        $multiTasks = array();
        if($browseType == 'task' and $groupBy == 'assignedTo')
        {
            foreach($cardList as $id => $task)
            {
                if($task->mode == 'multi') $multiTasks[$id] = $task;
            }
        }

        $taskTeams = $this->dao->select('t1.account,t1.task,t2.realname')->from(TABLE_TASKTEAM)->alias('t1')
            ->leftJoin(TABLE_USER)->alias('t2')->on('t1.account = t2.account')
            ->where('t1.task')->in(array_keys($multiTasks))
            ->orderBy('t1.order')
            ->fetchGroup('task', 'account');
        foreach($multiTasks as $taskID => $task)
        {
            if(!isset($taskTeams[$taskID])) continue;

            $teamPairs = array();
            foreach($taskTeams[$taskID] as $account => $team) $teamPairs[$account] = $team->realname;
            $task->teamMember = $teamPairs;
        }

        /* Get objects cards menus. */
        if($browseType == 'story') $storyCardMenu = $this->getKanbanCardMenu($executionID, $cardList, 'story');
        if($browseType == 'bug')   $bugCardMenu   = $this->getKanbanCardMenu($executionID, $cardList, 'bug');
        if($browseType == 'task')  $taskCardMenu  = $this->getKanbanCardMenu($executionID, $cardList, 'task');

        if($groupBy == 'story' and $browseType == 'task' and !isset($this->lang->kanban->orderList[$orderBy])) $orderBy = 'id_asc';
        $lanes = $this->getLanes4Group($executionID, $browseType, $groupBy, $cardList, $orderBy);
        if(empty($lanes)) return array();

        $execution = $this->loadModel('execution')->getByID($executionID);
        $cards     = $this->dao->select('t1.*, t2.`type` as columnType, t2.limit, t2.name as columnName, t2.color')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.`column` = t2.id')
            ->leftJoin(TABLE_KANBANLANE)->alias('t3')->on('t1.lane = t3.id')
            ->leftJoin(TABLE_KANBANREGION)->alias('t4')->on('t1.kanban = t4.kanban')
            ->where('t1.kanban')->eq($executionID)
            ->andWhere('t1.`type`')->eq($browseType)
            ->beginIF(isset($execution->type) and $execution->type == 'kanban')
            ->andWhere('t3.deleted')->eq(0)
            ->andWhere('t4.deleted')->eq(0)
            ->fi()
            ->orderBy('column_asc')
            ->fetchAll();

        $columns = array();
        foreach($cards as $card)
        {
            if(!isset($columns[$card->columnType])) $columns[$card->columnType] = $card;

            $columns[$card->columnType]->cards .= ",$card->id,";
            $columns[$card->columnType]->cards  = ',' . trim($columns[$card->columnType]->cards, ',') . ',';
        }

        $cardGroup = array();
        $actions   = array('setColumn', 'setWIP');

        foreach($columns as $column)
        {
            if(empty($column->cards)) continue;
            foreach($cardList as $card)
            {
                if($card->assignedTo == '') $card->assignedTo = 0;
                if(strpos($column->cards, ",$card->id,") !== false) $cardGroup[$column->columnType][$card->id] = $card;
            }
        }

        /* Build kanban group data. */
        $kanbanGroup = array();
        foreach($lanes as $laneID => $lane)
        {
            $laneData   = array();
            $columnData = array();
            $cardCount  = 0;

            $laneData['id']              = $groupBy . $laneID;
            $laneData['laneID']          = $groupBy . $laneID;
            $laneData['name']            = (($groupBy == 'pri' or $groupBy == 'severity') and $laneID) ? $this->lang->$browseType->$groupBy . ':' . $lane->name : $lane->name;
            $laneData['color']           = $lane->color;
            $laneData['order']           = $lane->order;
            $laneData['type']            = $browseType;
            $laneData['defaultCardType'] = $browseType;
            if(empty($laneID) and !in_array($groupBy, array('module', 'story', 'pri', 'severity'))) $laneID = '';

            if($browseType == 'task' and $groupBy == 'story')
            {
                $columnData[0]['id']         = 0;
                $columnData[0]['type']       = 'story';
                $columnData[0]['name']       = $this->lang->SRCommon;
                $columnData[0]['color']      = '#333';
                $columnData[0]['limit']      = '-1';
                $columnData[0]['laneType']   = $browseType;
                $columnData[0]['asParent']   = false;
                $columnData[0]['parentType'] = '';
                $columnData[0]['actions']    = array();

                if(empty($searchValue) or strpos($lane->name, $searchValue) !== false)
                {
                    $cardData = array();
                    $cardData['id']         = $laneID;
                    $cardData['title']      = $lane->name;
                    $cardData['order']      = 1;
                    $cardData['pri']        = $lane->pri;
                    $cardData['estimate']   = '';
                    $cardData['assignedTo'] = $lane->assignedTo;
                    $cardData['deadline']   = '';
                    $cardData['severity']   = '';
                    $laneData['cards']['story'][] = $cardData;
                }
            }

            /* Construct kanban column data. */
            foreach($columns as $column)
            {
                $parentColumn = '';
                if(in_array($column->columnType, array('developing', 'developed'))) $parentColumn = 'develop';
                if(in_array($column->columnType, array('testing', 'tested')))       $parentColumn = 'test';
                if(in_array($column->columnType, array('fixing', 'fixed')))         $parentColumn = 'resolving';

                /* Judge column action priv. */
                $column->actions = array();
                foreach($actions as $action)
                {
                    if($this->isClickable($column, $action)) $column->actions[] = $action;
                }

                $columnData[$column->column]['id']         = $column->column;
                $columnData[$column->column]['type']       = $column->columnType;
                $columnData[$column->column]['name']       = $column->columnName;
                $columnData[$column->column]['color']      = $column->color;
                $columnData[$column->column]['limit']      = $column->limit;
                $columnData[$column->column]['laneType']   = $browseType;
                $columnData[$column->column]['asParent']   = in_array($column->columnType, array('develop', 'test', 'resolving')) ? true : false;
                $columnData[$column->column]['parentType'] = $parentColumn;
                $columnData[$column->column]['actions']    = $column->actions;

                $cardOrder = 1;
                $objects   = zget($cardGroup, $column->columnType, array());
                foreach($objects as $object)
                {
                    if(empty($object)) continue;

                    $cardData = array();
                    if(in_array($groupBy, array('module', 'story', 'pri', 'severity')) and $object->$groupBy != $laneID) continue;
                    if(in_array($groupBy, array('type', 'category', 'source')) and $object->$groupBy != $laneID) continue;
                    if($groupBy == 'assignedTo')
                    {
                        $laneID = (string)$laneID;
                        if(empty($object->$groupBy)) $object->$groupBy = '';
                        if(empty($object->teamMember) and (string)$object->$groupBy !== $laneID) continue;
                        if(!empty($object->teamMember) and !in_array($laneID, array_keys($object->teamMember), true)) continue;

                        if($object->$groupBy !== $laneID) $cardData['assignedTo'] = $laneID;
                    }

                    $cardData['id']         = $object->id;
                    $cardData['order']      = $cardOrder;
                    $cardData['pri']        = $object->pri ? $object->pri : '';
                    $cardData['estimate']   = $browseType == 'bug' ? '' : $object->estimate;
                    $cardData['assignedTo'] = empty($cardData['assignedTo']) ? $object->assignedTo : $cardData['assignedTo'];
                    $cardData['deadline']   = $browseType == 'story' ? '' : $object->deadline;
                    $cardData['severity']   = $browseType == 'bug' ? $object->severity : '';
                    $cardData['status']     = $object->status;

                    if($lane->type == 'story') $cardData['menus'] = $storyCardMenu[$object->id];
                    if($lane->type == 'bug')   $cardData['menus'] = $bugCardMenu[$object->id];
                    if($lane->type == 'task')  $cardData['menus'] = $taskCardMenu[$object->id];

                    if($browseType == 'task')
                    {
                        if($searchValue != '' and strpos($object->name, $searchValue) === false) continue;
                        $cardData['name']       = $object->name;
                        $cardData['status']     = $object->status;
                        $cardData['left']       = $object->left;
                        $cardData['estStarted'] = $object->estStarted;
                    }
                    else
                    {
                        if($searchValue != '' and strpos($object->title, $searchValue) === false) continue;
                        $cardData['title'] = $object->title;
                    }

                    $laneData['cards'][$column->columnType][] = $cardData;
                    $cardOrder ++;
                }
                $cardCount += $cardOrder - 1;
                if($searchValue == '' and !isset($laneData['cards'][$column->columnType])) $laneData['cards'][$column->columnType] = array();
            }

            if(($searchValue != '' and empty($laneData['cards'])) or ($laneData['id'] == 'story0' and $cardCount == 0 and count($lanes) > 1)) continue;
            $kanbanGroup[$groupBy]['id']              = $groupBy . $laneID;
            $kanbanGroup[$groupBy]['columns']         = array_values($columnData);
            $kanbanGroup[$groupBy]['lanes'][]         = $laneData;
            $kanbanGroup[$groupBy]['defaultCardType'] = $browseType;
        }

        return $kanbanGroup;
    }

    /**
     * Build lane data for group kanban.
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
    public function getLanes4Group($executionID, $browseType, $groupBy, $cardList, $orderBy = 'id_asc')
    {
        $lanes       = array();
        $groupByList = array();
        $objectPairs = array();
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

        if(in_array($groupBy, array('module', 'story', 'assignedTo')))
        {
            if($groupBy == 'module')
            {
                $objectPairs += $this->dao->select('id,name')->from(TABLE_MODULE)
                    ->where('type')->in('story,task,bug')
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('id')->in($groupByList)
                    ->fetchPairs();
            }
            elseif($groupBy == 'story')
            {
                $objectPairs += $this->dao->select('id,title')->from(TABLE_STORY)
                    ->where('deleted')->eq(0)
                    ->andWhere('id')->in($groupByList)
                    ->orderBy($orderBy)
                    ->fetchPairs();

                $objects = $this->dao->select('*')->from(TABLE_STORY)
                    ->where('deleted')->eq(0)
                    ->andWhere('id')->in($groupByList)
                    ->orderBy($orderBy)
                    ->fetchAll('id');
            }
            else
            {
                $objectPairs += $this->dao->select('account,realname')->from(TABLE_USER)
                    ->where('account')->in($groupByList)
                    ->fetchPairs();

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
     * Get space list.
     *
     * @param  string $browseType private|cooperation|public|involved
     * @param  object $pager
     * @access public
     * @return array
     */
    public function getSpaceList($browseType, $pager = null)
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
     * Get space pairs.
     *
     * @param  string $browseType private|cooperation|public|involved
     * @access public
     * @return array
     */
    public function getSpacePairs($browseType = 'private')
    {
        $account     = $this->app->user->account;
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
     * Get Kanban pairs.
     *
     * @access public
     * @return void
     */
    public function getKanbanPairs()
    {
        $kanbanIdList = $this->getCanViewObjects('kanban');

        return $this->dao->select('id,name')->from(TABLE_KANBAN)
            ->where('deleted')->eq(0)
            ->beginIF(!$this->app->user->admin)->andWhere('id')->in($kanbanIdList)->fi()
            ->orderBy('id_desc')
            ->fetchPairs('id');
    }

    /**
     * Get can view objects.
     *
     * @param  string $objectType kanbanspace|kanban
     * @param  string $param      all|noclosed|private|cooperation|public|involved
     * @access public
     * @return array
     */
    public function getCanViewObjects($objectType = 'kanban', $param = 'all')
    {
        $table   = $this->config->objectTables[$objectType];
        $objects = $this->dao->select('*')->from($table)
            ->where('deleted')->eq(0)
            ->beginIF(strpos($param, 'noclosed') !== false)->andWhere('status')->ne('closed')->fi()
            ->fetchAll('id');

        $spaceList = $objectType == 'kanban' ? $this->dao->select('id,owner,type')->from(TABLE_KANBANSPACE)->fetchAll('id') : array();

        if($param and $this->app->user->admin and strpos('private,involved', $param) === false) return array_keys($objects);

        $account = $this->app->user->account;
        foreach($objects as $objectID => $object)
        {
            if($objectType == 'kanbanspace' and $object->type == 'public' and $param != 'involved') continue;

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
     * Create a space.
     *
     * @access public
     * @return int
     */
    public function createSpace()
    {
        $account = $this->app->user->account;
        $space   = fixer::input('post')
            ->setDefault('createdBy', $account)
            ->setDefault('createdDate', helper::now())
            ->setdefault('team', '')
            ->setdefault('whitelist', '')
            ->join('whitelist', ',')
            ->join('team', ',')
            ->trim('name')
            ->stripTags($this->config->kanban->editor->createspace['id'], $this->config->allowedTags)
            ->remove('uid,contactListMenu')
            ->get();

        if($space->type == 'private') $space->owner = $account;

        if(strpos(",{$space->team},", ",$account,") === false) $space->team .= ",$account";
        if(strpos(",{$space->team},", ",$space->owner,") === false) $space->team .= ",$space->owner";

        $this->dao->insert(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->batchCheck($this->config->kanban->createspace->requiredFields, 'notempty')
            ->exec();

        if(!dao::isError())
        {
            $spaceID = $this->dao->lastInsertID();
            $this->dao->update(TABLE_KANBANSPACE)->set('`order`')->eq($spaceID)->where('id')->eq($spaceID)->exec();
            $this->loadModel('file')->saveUpload('kanbanspace', $spaceID);
            $this->file->updateObjectID($this->post->uid, $spaceID, 'kanbanspace');

            return $spaceID;
        }
    }

    /**
     * Update a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    public function updateSpace($spaceID, $type = '')
    {
        $spaceID  = (int)$spaceID;
        $oldSpace = $this->getSpaceById($spaceID);
        $space    = fixer::input('post')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('whitelist', '')
            ->setDefault('team', '')
            ->join('whitelist', ',')
            ->join('team', ',')
            ->trim('name')
            ->stripTags($this->config->kanban->editor->editspace['id'], $this->config->allowedTags)
            ->remove('uid,contactListMenu')
            ->get();

        if($type == 'cooperation' or $type == 'public') $space->whitelist = '';

        $this->dao->update(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->batchCheck($this->config->kanban->editspace->requiredFields, 'notempty')
            ->where('id')->eq($spaceID)
            ->exec();

        if($oldSpace->type == 'private' and ($type == 'cooperation' or $type == 'public'))
        {
            $kanbanList = $this->dao->select('id,team,whitelist')->from(TABLE_KANBAN)->where('space')->eq($spaceID)->andWhere('deleted')->eq('0')->fetchAll('id');
            foreach($kanbanList as $id => $kanbanData)
            {
                $this->dao->update(TABLE_KANBAN)->set('team')->eq($kanbanData->whitelist)->set('whitelist')->eq('')->where('id')->eq($id)->andWhere('deleted')->eq('0')->exec();
            }
        }

        if(!dao::isError())
        {
            $this->loadModel('file')->saveUpload('kanbanspace', $spaceID);
            $this->file->updateObjectID($this->post->uid, $spaceID, 'kanbanspace');
            return common::createChanges($oldSpace, $space);
        }
    }

    /**
     * Close a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    function closeSpace($spaceID)
    {
        $spaceID  = (int)$spaceID;
        $oldSpace = $this->getSpaceById($spaceID);
        $now      = helper::now();
        $account  = $this->app->user->account;
        $space    = fixer::input('post')
            ->setDefault('status', 'closed')
            ->setDefault('closedBy', $account)
            ->setDefault('closedDate', $now)
            ->setDefault('lastEditedBy', $account)
            ->setDefault('lastEditedDate', $now)
            ->setDefault('activatedBy', '')
            ->setDefault('activatedDate', '0000-00-00 00:00:00')
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->where('id')->eq($spaceID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldSpace, $space);
    }

    /**
     * Activate a space.
     *
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    function activateSpace($spaceID)
    {
        $spaceID  = (int)$spaceID;
        $oldSpace = $this->getSpaceById($spaceID);
        $now      = helper::now();
        $space    = fixer::input('post')
            ->setDefault('status', 'active')
            ->setDefault('activatedBy', $this->app->user->account)
            ->setDefault('activatedDate', $now)
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEDitedDate', $now)
            ->setDefault('closedBy', '')
            ->setDefault('closedDate', '0000-00-00 00:00:00')
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_KANBANSPACE)->data($space)
            ->autoCheck()
            ->where('id')->eq($spaceID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldSpace, $space);
    }

    /**
     * Get lane pairs by region id.
     *
     * @param  array|int $regionID
     * @param  string    $type all|story|task|bug|common
     * @access public
     * @return array
     */
    public function getLanePairsByRegion($regionID, $type = 'all')
    {
        return $this->dao->select('id, name')->from(TABLE_KANBANLANE)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regionID)
            ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
            ->fetchPairs();
    }

    /**
     * Get lane by region id.
     *
     * @param  array  $regionID
     * @param  string $type all|story|task|bug|common
     * @access public
     * @return array
     */
    public function getLaneGroupByRegion($regionID, $type = 'all')
    {
        return $this->dao->select('*')->from(TABLE_KANBANLANE)
            ->where('deleted')->eq('0')
            ->andWhere('region')->in($regionID)
            ->beginIF($type != 'all')->andWhere('type')->eq($type)->fi()
            ->fetchGroup('region');
    }

    /**
     * Create a lane.
     *
     * @param  int    $kanbanID
     * @param  int    $regionID
     * @param  object $lane
     * @access public
     * @return int
     */
    public function createLane($kanbanID, $regionID, $lane = null)
    {
        if(empty($lane))
        {
            $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBANLANE)
                ->where('region')->eq($regionID)
                ->fetch('maxOrder');
            $lane = fixer::input('post')
                ->add('region', $regionID)
                ->add('order', $maxOrder ? $maxOrder + 1 : 1)
                ->add('lastEditedTime', helper::now())
                ->setIF(isset($_POST['laneType']), 'execution', $kanbanID)
                ->trim('name')
                ->setDefault('color', '#7ec5ff')
                ->remove('laneType')
                ->get();

            $lane->type = isset($_POST['laneType']) ? $_POST['laneType'] : 'common';

            $mode = zget($lane, 'mode', '');
            if($mode == 'sameAsOther')
            {
                $otherLane = zget($lane, 'otherLane', 0);
                if($otherLane) $lane->group = $this->dao->select('`group`')->from(TABLE_KANBANLANE)->where('id')->eq($otherLane)->fetch('group');
            }
            elseif($mode == 'independent')
            {
                $lane->group = $this->createGroup($kanbanID, $regionID);
                if($lane->type == 'common')
                {
                    $kanban = $this->getByID($kanbanID);
                    $this->createDefaultColumns($kanban, $regionID, $lane->group);
                }
            }
        }

        $this->dao->insert(TABLE_KANBANLANE)->data($lane, $skip = 'mode,otherLane')
            ->batchCheck($this->config->kanban->require->createlane, 'notempty')
            ->autoCheck()
            ->exec();
        if(dao::isError()) return false;

        $laneID = $this->dao->lastInsertID();
        if($lane->type != 'common' and isset($mode) and $mode == 'independent') $this->createRDColumn($regionID, $lane->group, $laneID, $lane->type, $kanbanID);

        if(isset($mode) and ($mode == 'sameAsOther' or ($lane->type == 'common' and $mode == 'independent')))
        {
            $columnIDList = $this->dao->select('id')->from(TABLE_KANBANCOLUMN)->where('deleted')->eq(0)->andWhere('archived')->eq(0)->andWhere('`group`')->eq($lane->group)->fetchPairs();
            foreach($columnIDList as $columnID)
            {
                $this->addKanbanCell($kanbanID, $laneID, $columnID, $lane->type);

                if(dao::isError()) return false;
            }
        }
        return $laneID;
    }

    /*
     * Create a kanban.
     *
     * @access public
     * @return int
     */
    public function create()
    {
        $account = $this->app->user->account;
        $kanban  = fixer::input('post')
            ->setDefault('createdBy', $account)
            ->setDefault('createdDate', helper::now())
            ->setDefault('owner', '')
            ->setDefault('team', '')
            ->setDefault('whitelist', '')
            ->setDefault('displayCards', 0)
            ->setIF($this->post->import == 'off', 'object', '')
            ->join('whitelist', ',')
            ->join('team', ',')
            ->trim('name')
            ->stripTags($this->config->kanban->editor->create['id'], $this->config->allowedTags)
            ->remove('contactListMenu,type,import,importObjectList,uid,copyKanbanID,copyRegion')
            ->get();

        if($this->post->import == 'on') $kanban->object = implode(',', $this->post->importObjectList);

        if(strpos(",{$kanban->team},", ",$account,") === false) $kanban->team .= ",$account";
        if(strpos(",{$kanban->team},", ",$kanban->owner,") === false) $kanban->team .= ",$kanban->owner";

        if(!empty($kanban->space))
        {
            $maxOrder = $this->dao->select('MAX(`order`) AS maxOrder')->from(TABLE_KANBAN)
                ->where('space')->eq($kanban->space)
                ->fetch('maxOrder');
            $kanban->order = $maxOrder ? $maxOrder+ 1 : 1;

            $space = $this->getSpaceById($kanban->space);
            if($space->type == 'private') $kanban->owner = $account;
        }

        $this->dao->insert(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->batchCheck($this->config->kanban->create->requiredFields, 'notempty')
            ->checkIF(!$kanban->fluidBoard, 'colWidth', 'ge', $this->config->minColWidth)
            ->batchCheckIF($kanban->fluidBoard, 'minColWidth', 'ge', $this->config->minColWidth)
            ->checkIF($kanban->minColWidth >= $this->config->minColWidth and $kanban->fluidBoard, 'maxColWidth', 'gt', $kanban->minColWidth)
            ->check('name', 'unique', "space = {$kanban->space}")
            ->exec();

        if(!dao::isError())
        {
            $kanbanID = $this->dao->lastInsertID();
            $kanban   = $this->getByID($kanbanID);

            if($this->post->copyRegion)
            {
                $this->copyRegions($kanban, $this->post->copyKanbanID);
            }
            else
            {
                $this->createDefaultRegion($kanban);
            }

            $this->loadModel('file')->saveUpload('kanban', $kanbanID);
            $this->file->updateObjectID($this->post->uid, $kanbanID, 'kanban');

            if(isset($_POST['team']) or isset($_POST['whitelist']))
            {
                $type = isset($_POST['team']) ? 'team' : 'whitelist';
                $kanbanMembers = empty($kanban->{$type}) ? array() : explode(',', $kanban->{$type});
                $this->addSpaceMembers($kanban->space, $type, $kanbanMembers);
            }

            return $kanbanID;
        }
    }

    /**
     * Update a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return array
     */
    public function update($kanbanID)
    {
        $kanbanID  = (int)$kanbanID;
        $account   = $this->app->user->account;
        $oldKanban = $this->getByID($kanbanID);
        $kanban    = fixer::input('post')
            ->setDefault('lastEditedBy', $account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('whitelist', '')
            ->setDefault('team', '')
            ->join('whitelist', ',')
            ->join('team', ',')
            ->trim('name')
            ->stripTags($this->config->kanban->editor->edit['id'], $this->config->allowedTags)
            ->remove('uid,contactListMenu')
            ->get();

        $this->dao->update(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->batchCheck($this->config->kanban->edit->requiredFields, 'notempty')
            ->where('id')->eq($kanbanID)
            ->exec();

        if(!dao::isError())
        {
            $this->loadModel('file')->saveUpload('kanban', $kanbanID);
            $this->file->updateObjectID($this->post->uid, $kanbanID, 'kanban');

            if(isset($_POST['team']) or isset($_POST['whitelist']))
            {
                $type = isset($_POST['team']) ? 'team' : 'whitelist';
                $kanbanMembers = empty($kanban->{$type}) ? array() : explode(',', $kanban->{$type});
                $this->addSpaceMembers($kanban->space, $type, $kanbanMembers);
            }

            return common::createChanges($oldKanban, $kanban);
        }
    }

    /**
     * Setting kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return void
     */
    public function setting($kanbanID)
    {
        $kanbanID  = (int)$kanbanID;
        $account   = $this->app->user->account;
        $oldKanban = $this->getByID($kanbanID);
        $kanban    = fixer::input('post')
            ->setDefault('lastEditedBy', $account)
            ->setDefault('lastEditedDate', helper::now())
            ->setDefault('displayCards', 0)
            ->setIF($this->post->import == 'off', 'object', '')
            ->setIF($this->post->heightType == 'auto', 'displayCards', 0)
            ->remove('import,importObjectList,heightType')
            ->get();

        if($this->post->import == 'on') $kanban->object = implode(',', $this->post->importObjectList);
        if(isset($_POST['heightType']) and $this->post->heightType == 'custom' and !$this->checkDisplayCards($kanban->displayCards)) return;

        $this->dao->update(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->batchCheck($this->config->kanban->edit->requiredFields, 'notempty')
            ->checkIF(!$kanban->fluidBoard, 'colWidth', 'ge', $this->config->minColWidth)
            ->batchCheckIF($kanban->fluidBoard, 'minColWidth', 'ge', $this->config->minColWidth)
            ->checkIF($kanban->minColWidth >= $this->config->minColWidth and $kanban->fluidBoard, 'maxColWidth', 'gt', $kanban->minColWidth)
            ->where('id')->eq($kanbanID)
            ->exec();

        if(dao::isError()) return false;

        return common::createChanges($oldKanban, $kanban);
    }

    /**
     * Activate a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return array
     */
    function activate($kanbanID)
    {
        $kanbanID  = (int)$kanbanID;
        $oldKanban = $this->getByID($kanbanID);
        $now       = helper::now();
        $kanban    = fixer::input('post')
            ->setDefault('status', 'active')
            ->setDefault('activatedBy', $this->app->user->account)
            ->setDefault('activatedDate', $now)
            ->setDefault('closedBy', '')
            ->setDefault('closedDate', '0000-00-00 00:00:00')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->where('id')->eq($kanbanID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldKanban, $kanban);
    }

    /**
     * Close a kanban.
     *
     * @param  int    $kanbanID
     * @access public
     * @return array
     */
    function close($kanbanID)
    {
        $kanbanID  = (int)$kanbanID;
        $oldKanban = $this->getByID($kanbanID);
        $now       = helper::now();
        $kanban    = fixer::input('post')
            ->setDefault('status', 'closed')
            ->setDefault('closedBy', $this->app->user->account)
            ->setDefault('closedDate', $now)
            ->setDefault('activatedBy', '')
            ->setDefault('activatedDate', '0000-00-00 00:00:00')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', $now)
            ->remove('comment')
            ->get();

        $this->dao->update(TABLE_KANBAN)->data($kanban)
            ->autoCheck()
            ->where('id')->eq($kanbanID)
            ->exec();

        if(!dao::isError()) return common::createChanges($oldKanban, $kanban);
    }

    /**
     * Add execution Kanban lanes and columns.
     *
     * @param  int    $executionID
     * @param  string $type all|story|bug|task
     * @access public
     * @return void
     */
    public function createExecutionLane($executionID, $type = 'all')
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
     * Create execution columns.
     *
     * @param  int|array   $laneID
     * @param  string      $type story|bug|task
     * @param  int         $executionID
     * @param  string      $groupBy
     * @param  string      $groupValue
     * @access public
     * @return void
     */
    public function createExecutionColumns($laneID, $type, $executionID)
    {
        $devColumnID = $testColumnID = $resolvingColumnID = 0;
        if($type == 'story')
        {
            foreach($this->lang->kanban->storyColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->name   = $name;
                $data->color  = '#333';
                $data->type   = $colType;
                $data->region = 0;

                if(strpos(',developing,developed,', $colType) !== false) $data->parent = $devColumnID;
                if(strpos(',testing,tested,', $colType) !== false)       $data->parent = $testColumnID;
                if(strpos(',develop,test,', $colType) !== false)         $data->parent = -1;

                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();

                $colID = $this->dao->lastInsertId();
                if($colType == 'develop') $devColumnID  = $colID;
                if($colType == 'test')    $testColumnID = $colID;

                if(is_array($laneID))
                {
                    foreach($laneID as $id) $this->addKanbanCell($executionID, $id, $colID, 'story');
                }
                else
                {
                    $this->addKanbanCell($executionID, $laneID, $colID, 'story');
                }
            }
        }
        elseif($type == 'bug')
        {
            foreach($this->lang->kanban->bugColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->name   = $name;
                $data->color  = '#333';
                $data->type   = $colType;
                $data->region = 0;
                if(strpos(',fixing,fixed,', $colType) !== false)   $data->parent = $resolvingColumnID;
                if(strpos(',testing,tested,', $colType) !== false) $data->parent = $testColumnID;
                if(strpos(',resolving,test,', $colType) !== false) $data->parent = -1;

                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();

                $colID = $this->dao->lastInsertId();
                if($colType == 'resolving') $resolvingColumnID = $colID;
                if($colType == 'test')      $testColumnID      = $colID;

                if(is_array($laneID))
                {
                    foreach($laneID as $id) $this->addKanbanCell($executionID, $id, $colID, 'bug');
                }
                else
                {
                    $this->addKanbanCell($executionID, $laneID, $colID, 'bug');
                }
            }
        }
        elseif($type == 'task')
        {
            foreach($this->lang->kanban->taskColumn as $colType => $name)
            {
                $data = new stdClass();
                $data->name   = $name;
                $data->color  = '#333';
                $data->type   = $colType;
                $data->region = 0;
                if(strpos(',developing,developed,', $colType) !== false) $data->parent = $devColumnID;
                if($colType == 'develop') $data->parent = -1;

                $this->dao->insert(TABLE_KANBANCOLUMN)->data($data)->exec();

                $colID = $this->dao->lastInsertId();
                if($colType == 'develop') $devColumnID = $colID;

                if(is_array($laneID))
                {
                    foreach($laneID as $id) $this->addKanbanCell($executionID, $id, $colID, 'task');
                }
                else
                {
                    $this->addKanbanCell($executionID, $laneID, $colID, 'task');
                }
            }
        }
    }

    /**
     * Add kanban cell for new lane.
     *
     * @param  int    $kanbanID
     * @param  int    $laneID
     * @param  int    $colID
     * @param  string $type story|task|bug|card
     * @access public
     * @return void
     */
    public function addKanbanCell($kanbanID, $laneID, $colID, $type, $cardID = 0)
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
     * Add space members.
     *
     * @param  int    $spaceID
     * @param  array  $type team|whitelist
     * @param  array  $kanbanMembers
     * @access public
     * @return void
     */
    public function addSpaceMembers($spaceID, $type, $kanbanMembers = array())
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
     * Remove kanban cell.
     *
     * @param  string    $type
     * @param  int|array $removeCardID
     * @param  array     $kanbanList
     * @access public
     * @return void
     */
    public function removeKanbanCell($type, $removeCardID, $kanbanList)
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
     * Create a default RD kanban.
     *
     * @param  object $execution
     * @access public
     * @return void
     */
    public function createRDKanban($execution)
    {
        $regionID =  $this->createRDRegion($execution);
        if(dao::isError()) return false;

        $groupID = $this->createGroup($execution->id, $regionID);
        if(dao::isError()) return false;

        $this->createRDLane($execution->id, $regionID);
        if(dao::isError()) return false;
    }

    /**
     * Create a default RD region.
     *
     * @param  object $execution
     *
     * @access public
     * @return int|bool
     */
    public function createRDRegion($execution)
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
     * Create default RD lanes.
     *
     * @param  int    $executionID
     * @param  int    $regionID
     *
     * @access public
     * @return bool
     */
    public function createRDLane($executionID, $regionID)
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
     * Create default RD columns.
     *
     * @param  int    $regionID
     * @param  int    $groupID
     * @param  int    $laneID
     * @param  string $laneType
     *
     * @access public
     * @return bool
     */
    public function createRDColumn($regionID, $groupID, $laneID, $laneType, $executionID)
    {
        $devColumnID = $testColumnID = $resolvingColumnID = 0;
        if($laneType == 'story') $columnList = $this->lang->kanban->storyColumn;
        if($laneType == 'bug') $columnList = $this->lang->kanban->bugColumn;
        if($laneType == 'task') $columnList = $this->lang->kanban->taskColumn;

        foreach($columnList as $type => $name)
        {
            $data = new stdClass();
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
     * Update a region.
     *
     * @param  int    $regionID
     * @access public
     * @return array
     */
    public function updateRegion($regionID)
    {
        $region    = fixer::input('post')
            ->setDefault('lastEditedBy', $this->app->user->account)
            ->setDefault('lastEditedDate', helper::now())
            ->trim('name')
            ->get();
        $oldRegion = $this->getRegionById($regionID);

        $this->dao->update(TABLE_KANBANREGION)->data($region)
            ->autoCheck()
            ->batchcheck($this->config->kanban->editregion->requiredFields, 'notempty')
            ->where('id')->eq($regionID)
            ->exec();

        if(dao::isError()) return;

        $changes = common::createChanges($oldRegion, $region);
        return $changes;
    }

    /**
     * Update kanban lane.
     *
     * @param  int    $executionID
     * @param  string $laneType
     * @param  int    $cardID
     * @access public
     * @return void
     */
    public function updateLane($executionID, $laneType, $cardID = 0)
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

        foreach($lanes as $lane) $this->refreshCards($lane);
    }

    /**
     * Refresh column cards.
     *
     * @param  object $lane
     * @access public
     * @return void
     */
    public function refreshCards($lane)
    {
        $laneType      = $lane->type;
        $executionID   = $lane->execution;
        $otherCardList = '';
        $otherLanes    = $this->dao->select('t2.id, t2.cards')->from(TABLE_KANBANLANE)->alias('t1')
            ->leftJoin(TABLE_KANBANCELL)->alias('t2')->on('t1.id=t2.lane')
            ->where('t1.id')->ne($lane->id)
            ->andWhere('t1.execution')->eq($executionID)
            ->andWhere('t2.`type`')->eq($lane->type)
            ->fetchPairs();

        foreach($otherLanes as $cardIDList)
        {
            $cardIDList = trim($cardIDList, ',');
            if(!empty($cardIDList)) $otherCardList .= ',' . $cardIDList;
        }

        $cardPairs = $this->dao->select('t2.type, t1.cards')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.`column` = t2.id')
            ->where('t1.kanban')->eq($executionID)
            ->andWhere('t1.lane')->eq($lane->id)
            ->fetchPairs();

        if(empty($cardPairs)) return;
        $sourceCards = $cardPairs;

        if($laneType == 'story')
        {
            $stories = $this->loadModel('story')->getExecutionStories($executionID, 0, 0, 't1.`order`_desc', 'allStory', 0, 'story', $otherCardList);
            foreach($stories as $storyID => $story)
            {
                foreach($this->config->kanban->storyColumnStageList as $colType => $stage)
                {
                    if($story->stage != $stage and strpos($cardPairs[$colType], ",$storyID,") !== false)
                    {
                        $cardPairs[$colType] = str_replace(",$storyID,", ',', $cardPairs[$colType]);
                    }

                    if(strpos(',ready,backlog,develop,test,', $colType) !== false) continue;

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
        }
        elseif($laneType == 'bug')
        {
            $bugs = $this->loadModel('bug')->getExecutionBugs($executionID, 0, 'all', 0, '', 0, 'id_desc', $otherCardList);
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
        }
        elseif($laneType == 'task')
        {
            $tasks = $this->loadModel('execution')->getKanbanTasks($executionID, 'status_asc, id_desc', null, $otherCardList);
            foreach($tasks as $taskID => $task)
            {
                foreach($this->config->kanban->taskColumnStatusList as $colType => $status)
                {
                    if($colType == 'develop') continue;

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
        }

        $colPairs = $this->dao->select('t2.type, t2.id')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.`column` = t2.id')
            ->where('t1.kanban')->eq($executionID)
            ->andWhere('t1.lane')->eq($lane->id)
            ->fetchPairs();

        $updated = false;
        foreach($cardPairs as $colType => $cards)
        {
            if(!isset($colPairs[$colType])) continue;
            if($sourceCards[$colType] == $cards) continue;

            $this->dao->update(TABLE_KANBANCELL)->set('cards')->eq($cards)->where('lane')->eq($lane->id)->andWhere('`column`')->eq($colPairs[$colType])->exec();
            if(!$updated) $updated = true;
        }

        if($updated) $this->dao->update(TABLE_KANBANLANE)->set('lastEditedTime')->eq(helper::now())->where('id')->eq($lane->id)->exec();
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
        $data = fixer::input('post')->trim('name')->get();

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
     * Activate a card.
     *
     * @param  int    $cardID
     * @access public
     * @return array
     */
    public function activateCard($cardID)
    {
        if($this->post->progress >= 100 or $this->post->progress < 0)
        {
            dao::$errors[] = $this->lang->kanbancard->error->progressIllegal;
            return false;
        }
        $this->dao->update(TABLE_KANBANCARD)->set('progress')->eq($this->post->progress ? $this->post->progress : 0)->set('status')->eq('doing')->where('id')->eq($cardID)->exec();
    }

    /**
     * Update a card.
     *
     * @param  int    $cardID
     * @access public
     * @return array
     */
    public function updateCard($cardID)
    {
        if($this->post->estimate < 0)
        {
            dao::$errors[] = $this->lang->kanbancard->error->recordMinus;
            return false;
        }

        if($this->post->end && ($this->post->begin > $this->post->end))
        {
            dao::$errors[] = $this->lang->kanbancard->error->endSmall;
            return false;
        }

        if($this->post->progress > 100 or $this->post->progress < 0)
        {
            dao::$errors[] = $this->lang->kanbancard->error->progressIllegal;
            return false;
        }

        $cardID  = (int)$cardID;
        $oldCard = $this->getCardByID($cardID);

        $now  = helper::now();
        $card = fixer::input('post')
            ->add('lastEditedBy', $this->app->user->account)
            ->add('lastEditedDate', $now)
            ->trim('name')
            ->setIF(!empty($this->post->assignedTo) and $oldCard->assignedTo != $this->post->assignedTo, 'assignedDate', $now)
            ->setIF(!isset($_POST['estimate']), 'estimate', $oldCard->estimate)
            ->setIF(is_numeric($this->post->estimate), 'estimate', (float)$this->post->estimate)
            ->stripTags($this->config->kanban->editor->editcard['id'], $this->config->allowedTags)
            ->join('assignedTo', ',')
            ->remove('uid')
            ->get();

        $card->assignedTo = isset($card->assignedTo) ? trim($card->assignedTo, ',') : '';
        $card->status     = $this->post->progress == 100 ? 'done' : 'doing';

        $card = $this->loadModel('file')->processImgURL($card, $this->config->kanban->editor->editcard['id'], $this->post->uid);

        $this->dao->update(TABLE_KANBANCARD)->data($card)
            ->autoCheck()
            ->checkIF($card->estimate != '', 'estimate', 'float')
            ->batchcheck($this->config->kanban->editcard->requiredFields, 'notempty')
            ->where('id')->eq($cardID)
            ->exec();

        if(!dao::isError())
        {
            $this->file->saveUpload('kanbancard', $cardID);
            $this->file->updateObjectID($this->post->uid, $cardID, 'kanbancard');

            return common::createChanges($oldCard, $card);
        }
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
        $column    = fixer::input('post')->remove('WIPCount,noLimit')->get();
        if(!preg_match("/^-?\d+$/", $column->limit) or (!isset($_POST['noLimit']) and $column->limit <= 0))
        {
            dao::$errors['limit'] = $this->lang->kanban->error->mustBeInt;
            return false;
        }
        $column->limit = (int)$column->limit;

        /* Check column limit. */
        $sumChildLimit = 0;
        if($oldColumn->parent == -1 and $column->limit != -1)
        {
            $childColumns = $this->dao->select('id,`limit`')->from(TABLE_KANBANCOLUMN)->where('parent')->eq($columnID)->andWhere('deleted')->eq(0)->fetchAll();
            foreach($childColumns as $childColumn)
            {
                if($childColumn->limit == -1)
                {
                    dao::$errors['limit'] = $this->lang->kanban->error->childLimitEmpty;
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
        $lane = fixer::input('post')->trim('name')->get();

        $this->dao->update(TABLE_KANBANLANE)->data($lane)
            ->autoCheck()
            ->batchcheck($this->config->kanban->setlane->requiredFields, 'notempty')
            ->where('id')->eq($laneID)
            ->exec();

        return dao::isError();
    }

    /**
     * Set kanban headerActions.
     *
     * @param  object $kanban
     * @access public
     * @return void
     */
    public function setHeaderActions($kanban)
    {
        $btnColor = '';
        if($this->app->cookie->theme == 'blue') $btnColor = 'style="color:#000000"';

        $actions  = '';
        $actions .= "<div class='btn-group'>";
        $actions .= "<a href='javascript:fullScreen();' id='fullScreenBtn' $btnColor class='btn btn-link'><i class='icon icon-fullscreen'></i> {$this->lang->kanban->fullScreen}</a>";

        $CRKanban       = !(isset($this->config->CRKanban) and $this->config->CRKanban == '0' and $kanban->status == 'closed');
        $printKanbanBtn = (common::hasPriv('kanban', 'edit') or ($kanban->status == 'active' and common::hasPriv('kanban', 'close')) or common::hasPriv('kanban', 'delete') or ($kanban->status == 'closed' and common::hasPriv('kanban', 'activate')));

        if($printKanbanBtn)
        {
            $actions .= "<a data-toggle='dropdown' $btnColor class='btn btn-link dropdown-toggle setting' type='button'>" . '<i class="icon icon-edit"></i> ' . $this->lang->edit . '</a>';
            $actions .= "<ul id='kanbanActionMenu' class='dropdown-menu text-left'>";

            $columnActions = '';
            $actions .= $columnActions;

            $commonActions = '';
            $importWidth   = $this->app->getClientLang() == 'en' ? '700' : '550';

            if($columnActions and $commonActions)
            {
                $actions .= "<li class='divider'></li>";
            }
            $actions .= $commonActions;

            $kanbanActions = '';
            if(common::hasPriv('kanban', 'edit')) $kanbanActions .= '<li>' . html::a(helper::createLink('kanban', 'edit', "kanbanID=$kanban->id", '', true), '<i class="icon icon-edit"></i>' . $this->lang->kanban->edit, '', "class='iframe btn btn-link' data-width='75%'") . '</li>';
            if(common::hasPriv('kanban', 'close') and $kanban->status == 'active') $kanbanActions .= '<li>' . html::a(helper::createLink('kanban', 'close', "kanbanID=$kanban->id", '', true), '<i class="icon icon-off"></i>' . $this->lang->kanban->close, '', "class='iframe btn btn-link'") . '</li>';
            if(common::hasPriv('kanban', 'activate') and $kanban->status == 'closed') $kanbanActions .= '<li>' . html::a(helper::createLink('kanban', 'activate', "kanbanID=$kanban->id", '', true), '<i class="icon icon-magic"></i>' . $this->lang->kanban->activate, '', "class='iframe btn btn-link'") . '</li>';
            if(common::hasPriv('kanban', 'delete')) $kanbanActions .= '<li>' . html::a(helper::createLink('kanban', 'delete', "kanbanID=$kanban->id"), '<i class="icon icon-trash"></i>' . $this->lang->kanban->delete, 'hiddenwin', "class='btn btn-link'") . '</li>';

            if($commonActions and $kanbanActions)
            {
                $actions .= "<li class='divider'></li>";
            }
            $actions .= $kanbanActions;

            $actions .= "</ul>";
        }

        if(common::hasPriv('kanban', 'setting'))
        {
            $width    = common::checkNotCN() ? '65%' : '60%';
            $actions .= html::a(helper::createLink('kanban', 'setting', "kanbanID=$kanban->id", '', true), '<i class="icon icon-cog-outline"></i> ' . $this->lang->kanban->setting, '', "class='iframe btn btn-link' data-width='$width'");
        }

        $actions .= "</div>";

        $this->lang->headerActions = $actions;
    }

    /**
     * Set switcher menu.
     *
     * @param  object $kanban
     * @access public
     * @return void
     */
    public function setSwitcher($kanban)
    {
        $currentModule = $this->app->getModuleName();
        $currentMethod = $this->app->getMethodName();

        $kanbanLink = helper::createLink('kanban', 'ajaxGetKanbanMenu', "objectID=$kanban->id&module=$currentModule&method=$currentMethod");

        $switcher  = "<div class='btn-group header-btn' id='swapper'><button data-toggle='dropdown' type='button' class='btn' id='currentItem' title='{$kanban->name}'><span class='text'>{$kanban->name}</span> <span class='caret'></span></button><div id='dropMenu' class='dropdown-menu search-list' data-ride='searchList' data-url='$kanbanLink'>";
        $switcher .= '<div class="input-control search-box has-icon-left has-icon-right search-example"><input type="search" class="form-control search-input" /><label class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label><a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a></div>';
        $switcher .= "</div></div>";

        $this->lang->switcherMenu = $switcher;
    }

    /**
     * Sort kanban group.
     *
     * @param  int    $region
     * @param  array  $groups
     * @access public
     * @return bool
     */
    public function sortGroup($region, $groups)
    {
        $this->loadModel('action');

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
    public function moveCard($cardID, $fromColID, $toColID, $fromLaneID, $toLaneID, $kanbanID = 0)
    {
        $groupBy = ($this->session->execGroupBy and ($this->app->tab == 'execution' or $this->config->vision == 'lite')) ? $this->session->execGroupBy : '';

        $fromCell = $this->dao->select('id,cards,lane')->from(TABLE_KANBANCELL)
            ->where('`column`')->eq($fromColID)
            ->beginIF(!$groupBy or $groupBy == 'default')->andWhere('lane')->eq($fromLaneID)->fi()
            ->beginIF($groupBy and $groupBy != 'default')
            ->andWhere('type')->eq($this->session->execLaneType)
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
     * Update a card's color.
     *
     * @param  int    $cardID
     * @param  int    $color
     * @access public
     * @return void
     */
    public function updateCardColor($cardID, $color)
    {
        $this->dao->update(TABLE_KANBANCARD)->set('`color`')->eq('#' . $color)->where('id')->eq($cardID)->exec();
    }

    /**
     * Reset order of lane.
     *
     * @param  int    $executionID
     * @param  int    $type
     * @param  int    $groupBy
     * @access public
     * @return void
     */
    public function resetLaneOrder($executionID, $type, $groupBy)
    {
        $lanes = $this->dao->select('id,extra')->from(TABLE_KANBANLANE)
            ->where('execution')->eq($executionID)
            ->andWhere('type')->eq($type)
            ->andWhere('groupBy')->eq($groupBy)
            ->orderBy('extra_asc')
            ->fetchPairs();

        $laneOrder = 5;
        $noExtra   = 0;

        foreach($lanes as $laneID => $extra)
        {
            if(!$extra)
            {
                $noExtra = $laneID;
                continue;
            }

            $this->dao->update(TABLE_KANBANLANE)->set('order')->eq($laneOrder)->where('id')->eq($laneID)->exec();
            $laneOrder += 5;
        }

        if($noExtra) $this->dao->update(TABLE_KANBANLANE)->set('order')->eq($laneOrder)->where('id')->eq($noExtra)->exec();
    }

    /**
     * Archive a column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function archiveColumn($columnID)
    {
        $this->dao->update(TABLE_KANBANCOLUMN)
            ->set('archived')->eq(1)
            ->where('id')->eq($columnID)
            ->exec();
    }

    /**
     * Restore a column.
     *
     * @param  int    $columnID
     * @access public
     * @return void
     */
    public function restoreColumn($columnID)
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
    }

    /**
     * Archive a card.
     *
     * @param  int    $cardID
     * @access public
     * @return array
     */
    public function archiveCard($cardID)
    {
        $oldCard = $this->getCardByID($cardID);

        $this->dao->update(TABLE_KANBANCARD)
            ->set('archived')->eq(1)
            ->set('archivedBy')->eq($this->app->user->account)
            ->set('archivedDate')->eq(helper::now())
            ->where('id')->eq($cardID)
            ->exec();

        $card = $this->getCardByID($cardID);

        if(!dao::isError()) return common::createChanges($oldCard, $card);
    }

    /**
     * Restore a card.
     *
     * @param  int    $cardID
     * @access public
     * @return array
     */
    public function restoreCard($cardID)
    {
        $oldCard = $this->getCardByID($cardID);

        $this->dao->update(TABLE_KANBANCARD)
            ->set('archived')->eq(0)
            ->set('archivedBy')->eq('')
            ->set('archivedDate')->eq('')
            ->where('id')->eq($cardID)
            ->exec();

        $card = $this->getCardByID($cardID);

        if(!dao::isError()) return common::createChanges($oldCard, $card);
    }

    /**
     * Process cards when delete a column.
     *
     * @param  object $column
     * @access public
     * @return void
     */
    public function processCards($column)
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
     * Get space by id.
     *
     * @param  int    $spaceID
     * @access public
     * @return array
     */
    public function getSpaceById($spaceID)
    {
        $space = $this->dao->findById($spaceID)->from(TABLE_KANBANSPACE)->fetch();
        $space = $this->loadModel('file')->replaceImgURL($space, 'desc');
        return $space;
    }

    /**
     * Get kanban group by space id list.
     *
     * @param  array|string $spaceIdList
     * @param  array|string $kanbanIdList
     * @access public
     * @return array
     */
    public function getGroupBySpaceList($spaceIdList, $kanbanIdList = '')
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
     * Get group list by region.
     *
     * @param  int    $region
     * @access public
     * @return array
     */
    public function getGroupList($region)
    {
        return $this->dao->select('*')->from(TABLE_KANBANGROUP)
            ->where('region')->eq($region)
            ->orderBy('order')
            ->fetchAll('id');
    }

    /**
     * Get column by id.
     *
     * @param  int    $columnID
     * @access public
     * @return object
     */
    public function getColumnByID($columnID)
    {
        $column = $this->dao->select('t1.*, t2.type as laneType')->from(TABLE_KANBANCOLUMN)->alias('t1')
            ->leftjoin(TABLE_KANBANCELL)->alias('t2')->on('t1.id=t2.column')
            ->where('t1.id')->eq($columnID)
            ->fetch();

        if(empty($column)) return false;
        if($column->parent > 0) $column->parentName = $this->dao->findById($column->parent)->from(TABLE_KANBANCOLUMN)->fetch('name');

        return $column;
    }

    /**
     * Get columns by object id.
     *
     * @param  string $objectType parent|region|group
     * @param  int    $objectID
     * @param  string $archived
     * @param  string $deleted
     * @access public
     * @return array
     */
    public function getColumnsByObject($objectType = '', $objectID = 0, $archived = 0, $deleted = '0')
    {
        return $this->dao->select('*')->from(TABLE_KANBANCOLUMN)
            ->where('1=1')
            ->beginIF($objectType)->andWhere($objectType)->eq($objectID)->fi()
            ->beginIF($archived != '')->andWhere('archived')->eq($archived)->fi()
            ->beginIF($deleted != '')->andWhere('deleted')->eq($deleted)->fi()
            ->orderBy('order')
            ->fetchAll('id');
    }

    /**
     * Get column ID by lane ID.
     *
     * @param  int    $laneID
     * @param  string $columnType
     * @access public
     * @return int
     */
    public function getColumnIDByLaneID($laneID, $columnType)
    {
        return $this->dao->select('t1.column')->from(TABLE_KANBANCELL)->alias('t1')
            ->leftJoin(TABLE_KANBANCOLUMN)->alias('t2')->on('t1.column = t2.id')
            ->where('t1.lane')->eq($laneID)
            ->andWhere('t2.type')->eq($columnType)
            ->fetch('column');
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
     * Get object group list.
     *
     * @param  int    $executionID
     * @param  string $type
     * @param  string $groupBy
     * @access public
     * @return array
     */
    public function getObjectGroup($executionID, $type, $groupBy)
    {
        $table = zget($this->config->objectTables, $type);

        if($groupBy == 'story' or $type == 'story')
        {
            $selectField = $groupBy == 'story' ? "t1.$groupBy" : "t2.$groupBy";
            $groupList   = $this->dao->select($selectField)->from(TABLE_PROJECTSTORY)->alias('t1')
                ->leftJoin(TABLE_STORY)->alias('t2')->on('t1.story=t2.id')
                ->where('t1.project')->eq($executionID)
                ->andWhere('t2.deleted')->eq(0)
                ->orderBy($groupBy . '_desc')
                ->fetchPairs();

            if($type == 'task')
            {
                $unlinkedTask = $this->dao->select('id')->from(TABLE_TASK)
                    ->where('execution')->eq($executionID)
                    ->andWhere('parent')->ge(0)
                    ->andWhere('story')->eq(0)
                    ->andWhere('deleted')->eq(0)
                    ->fetch('id');
                if($unlinkedTask) $groupList[0] = 0;
            }
        }
        else
        {
            $groupList = $this->dao->select($groupBy)->from($table)
                ->where('execution')->eq($executionID)
                ->beginIF($type == 'task')->andWhere('parent')->ge(0)->fi()
                ->andWhere('deleted')->eq(0)
                ->orderBy($groupBy . '_desc')
                ->fetchPairs();
        }

        return $groupList;
    }

    /**
     * Get card by id.
     *
     * @param  int    $cardID
     * @access public
     * @return object
     */
    public function getCardByID($cardID)
    {
        $card = $this->dao->select('*')->from(TABLE_KANBANCARD)->where('id')->eq($cardID)->fetch();
        $card = $this->loadModel('file')->replaceImgURL($card, 'desc');

        return $card;
    }

    /**
     * Get cards by object id.
     *
     * @param  string $objectType kanban|region|group|lane|column
     * @param  int    $objectID
     * @param  string $archived
     * @param  string $deleted
     * @access public
     * @return array
     */
    public function getCardsByObject($objectType = '', $objectID = 0, $archived = '0', $deleted = '0')
    {
        return $this->dao->select('*')->from(TABLE_KANBANCARD)
            ->where('1=1')
            ->beginIF($objectType)->andWhere($objectType)->eq($objectID)->fi()
            ->beginIF($archived != '')->andWhere('archived')->eq($archived)->fi()
            ->beginIF($deleted != '')->andWhere('deleted')->eq($deleted)->fi()
            ->orderBy('order')
            ->fetchAll('id');
    }

    /**
     * Get cards to import.
     *
     * @param  int $kanbanID
     * @param  int $excludedID
     * @param  obj $pager
     * @access public
     * @return array
     */
    public function getCards2Import($kanbanID = 0, $excludedID = 0, $pager = null)
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
        $this->app->loadLang('execution');
        $methodName = $this->app->rawMethod;

        $menus = array();
        switch ($objecType)
        {
            case 'story':
                if(!isset($this->story)) $this->loadModel('story');

                $objects = $this->story->mergeReviewer($objects);
                foreach($objects as $story)
                {
                    $menu = array();

                    $toTaskPriv = strpos('draft,reviewing,closed', $story->status) !== false ? false : true;
                    if(common::hasPriv('story', 'edit') and $this->story->isClickable($story, 'edit'))         $menu[] = array('label' => $this->lang->story->edit, 'icon' => 'edit', 'url' => helper::createLink('story', 'edit', "storyID=$story->id", '', true), 'size' => '95%');
                    if(common::hasPriv('story', 'change') and $this->story->isClickable($story, 'change'))     $menu[] = array('label' => $this->lang->story->change, 'icon' => 'alter', 'url' => helper::createLink('story', 'change', "storyID=$story->id", '', true), 'size' => '95%');
                    if(common::hasPriv('story', 'review') and $this->story->isClickable($story, 'review'))     $menu[] = array('label' => $this->lang->story->review, 'icon' => 'search', 'url' => helper::createLink('story', 'review', "storyID=$story->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'create') and $toTaskPriv)                                      $menu[] = array('label' => $this->lang->execution->wbs, 'icon' => 'plus', 'url' => helper::createLink('task', 'create', "executionID=$executionID&storyID=$story->id&moduleID=$story->module", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'batchCreate') and $toTaskPriv)                                 $menu[] = array('label' => $this->lang->execution->batchWBS, 'icon' => 'pluses', 'url' => helper::createLink('task', 'batchCreate', "executionID=$executionID&storyID=$story->id&moduleID=0&taskID=0&iframe=true", '', true), 'size' => '95%');
                    if(common::hasPriv('story', 'activate') and $this->story->isClickable($story, 'activate')) $menu[] = array('label' => $this->lang->story->activate, 'icon' => 'magic', 'url' => helper::createLink('story', 'activate', "storyID=$story->id", '', true));
                    if(common::hasPriv('execution', 'unlinkStory'))                                            $menu[] = array('label' => $this->lang->execution->unlinkStory, 'icon' => 'unlink', 'url' => helper::createLink('execution', 'unlinkStory', "executionID=$executionID&storyID=$story->story&confirm=no&from=taskkanban", '', true));
                    if(common::hasPriv('story', 'delete'))                                                     $menu[] = array('label' => $this->lang->story->delete, 'icon' => 'trash', 'url' => helper::createLink('story', 'delete', "storyID=$story->id&confirm=no&from=taskkanban"));

                    $menus[$story->id] = $menu;
                }
                break;
            case 'bug':
                if(!isset($this->bug)) $this->loadModel('bug');

                foreach($objects as $bug)
                {
                    $menu = array();

                    if(common::hasPriv('bug', 'edit') and $this->bug->isClickable($bug, 'edit'))             $menu[] = array('label' => $this->lang->bug->edit, 'icon' => 'edit', 'url' => helper::createLink('bug', 'edit', "bugID=$bug->id", '', true), 'size' => '95%');
                    if(common::hasPriv('bug', 'confirmBug') and $this->bug->isClickable($bug, 'confirmBug')) $menu[] = array('label' => $this->lang->bug->confirmBug, 'icon' => 'ok', 'url' => helper::createLink('bug', 'confirmBug', "bugID=$bug->id&extra=&from=taskkanban", '', true));
                    if(common::hasPriv('bug', 'resolve') and $this->bug->isClickable($bug, 'resolve'))       $menu[] = array('label' => $this->lang->bug->resolve, 'icon' => 'checked', 'url' => helper::createLink('bug', 'resolve', "bugID=$bug->id&extra=&from=taskkanban", '', true));
                    if(common::hasPriv('bug', 'close') and $this->bug->isClickable($bug, 'close'))           $menu[] = array('label' => $this->lang->bug->close, 'icon' => 'off', 'url' => helper::createLink('bug', 'close', "bugID=$bug->id&extra=&from=taskkanban", '', true));
                    if(common::hasPriv('bug', 'create') and $this->bug->isClickable($bug, 'create'))         $menu[] = array('label' => $this->lang->bug->copy, 'icon' => 'copy', 'url' => helper::createLink('bug', 'create', "productID=$bug->product&branch=$bug->branch&extras=bugID=$bug->id", '', true), 'size' => '95%');
                    if(common::hasPriv('bug', 'activate') and $this->bug->isClickable($bug, 'activate'))     $menu[] = array('label' => $this->lang->bug->activate, 'icon' => 'magic', 'url' => helper::createLink('bug', 'activate', "bugID=$bug->id&extra=&from=taskkanban", '', true));
                    if(common::hasPriv('story', 'create') and $bug->status != 'closed')                      $menu[] = array('label' => $this->lang->bug->toStory, 'icon' => 'lightbulb', 'url' => helper::createLink('story', 'create', "product=$bug->product&branch=$bug->branch&module=0&story=0&execution=0&bugID=$bug->id", '', true), 'size' => '95%');
                    if(common::hasPriv('bug', 'delete'))                                                     $menu[] = array('label' => $this->lang->bug->delete, 'icon' => 'trash', 'url' => helper::createLink('bug', 'delete', "bugID=$bug->id&confirm=no&from=taskkanban"));

                    $menus[$bug->id] = $menu;
                }
                break;
            case 'task':
                if(!isset($this->task)) $this->loadModel('task');

                foreach($objects as $task)
                {
                    $menu = array();

                    if(common::hasPriv('task', 'edit') and $this->task->isClickable($task, 'edit'))                                $menu[] = array('label' => $this->lang->task->edit, 'icon' => 'edit', 'url' => helper::createLink('task', 'edit', "taskID=$task->id&comment=false&kanbanGroup=default&from=taskkanban", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'pause') and $this->task->isClickable($task, 'pause'))                              $menu[] = array('label' => $this->lang->task->pause, 'icon' => 'pause', 'url' => helper::createLink('task', 'pause', "taskID=$task->id&extra=from=taskkanban", '', true));
                    if(common::hasPriv('task', 'restart') and $this->task->isClickable($task, 'restart'))                          $menu[] = array('label' => $this->lang->task->restart, 'icon' => 'play', 'url' => helper::createLink('task', 'restart', "taskID=$task->id&from=taskkanban", '', true));
                    if(common::hasPriv('task', 'recordEstimate') and $this->task->isClickable($task, 'recordEstimate'))            $menu[] = array('label' => $this->lang->task->recordEstimate, 'icon' => 'time', 'url' => helper::createLink('task', 'recordEstimate', "taskID=$task->id&from=taskkanban", '', true));
                    if(common::hasPriv('task', 'activate') and $this->task->isClickable($task, 'activate'))                        $menu[] = array('label' => $this->lang->task->activate, 'icon' => 'magic', 'url' => helper::createLink('task', 'activate', "taskID=$task->id&extra=from=taskkanban", '', true));
                    if(common::hasPriv('task', 'batchCreate') and $this->task->isClickable($task, 'batchCreate') and !$task->mode) $menu[] = array('label' => $this->lang->task->children, 'icon' => 'split', 'url' => helper::createLink('task', 'batchCreate', "execution=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'create') and $this->task->isClickable($task, 'create'))                            $menu[] = array('label' => $this->lang->task->copy, 'icon' => 'copy', 'url' => helper::createLink('task', 'create', "projctID=$task->execution&storyID=$task->story&moduleID=$task->module&taskID=$task->id", '', true), 'size' => '95%');
                    if(common::hasPriv('task', 'cancel') and $this->task->isClickable($task, 'cancel'))                            $menu[] = array('label' => $this->lang->task->cancel, 'icon' => 'ban-circle', 'url' => helper::createLink('task', 'cancel', "taskID=$task->id&extra=from=taskkanban", '', true));
                    if(common::hasPriv('task', 'delete'))                                                                          $menu[] = array('label' => $this->lang->task->delete, 'icon' => 'trash', 'url' => helper::createLink('task', 'delete', "executionID=$task->execution&taskID=$task->id&confirm=no&from=taskkanban"));

                    $menus[$task->id] = $menu;
                }
                break;
        }
        return $menus;
    }

    /**
     * Get toList and ccList.
     *
     * @param  object    $card
     * @access public
     * @return bool|array
     */
    public function getToAndCcList($card)
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
     * Check if user can execute an action.
     *
     * @param  object $object
     * @param  string $action
     * @access public
     * @return bool
     */
    public function isClickable($object, $action)
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
                if($object->parent) return false;   // The current column is a child column.

                $count = $this->dao->select('COUNT(id) AS count')->from(TABLE_KANBANCOLUMN)
                    ->where('parent')->eq($object->id)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('archived')->eq('0')
                    ->fetch('count');
                return $count == 0;     // The column has child columns.
            case 'restorecolumn' :
                if($object->parent > 0)
                {
                    $parent = $this->getColumnByID($object->parent);
                    if(!empty($parent) and $parent->deleted == '1' || $parent->archived == '1') return false;
                }
                return $object->archived == '1';
            case 'archivecolumn' :
                if($object->archived != '0') return false;    // The column has been archived.
            case 'deletecolumn' :
                if($object->deleted != '0') return false;

                $count = $this->dao->select('COUNT(id) AS count')->from(TABLE_KANBANCOLUMN)
                    ->where('region')->eq($object->region)
                    ->andWhere('parent')->in('0,-1')
                    ->andWhere('`group`')->eq($object->group)
                    ->andWhere('deleted')->eq('0')
                    ->andWhere('archived')->eq('0')
                    ->fetch('count');

                return $count > 1;
            case 'sortColumn' :
                if($object->deleted != '0') return false;
        }

        return true;
    }

    /**
     * Get kanban lane count.
     *
     * @param  int    $kanbanID
     * @param  string $type
     * @access public
     * @return int
     */
    public function getLaneCount($kanbanID, $type = 'common')
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
     * Check display card count.
     *
     * @param  int    $count
     * @access public
     * @return bool
     */
    public function checkDisplayCards($count)
    {
        if(!preg_match("/^-?\d+$/", $count) or $count <= DEFAULT_CARDCOUNT or $count > MAX_CARDCOUNT) dao::$errors['displayCards'] = $this->lang->kanbanlane->error->mustBeInt;
        return !dao::isError();
    }
}
