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
        $this->dao->insert(TABLE_KANBAN)->data($kanban)
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
}
