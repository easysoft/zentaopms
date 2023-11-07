<?php
namespace zin;

dropmenu(set::tab('kanban'), set::objectID($kanban->id));

$laneCount   = 0;
$columnCount = array();
$parentCols  = array();
$groupCols   = array(); // 卡片可以移动到的同一group下的列。
foreach($kanbanList as $regionID => $region)
{
    foreach($region['items'] as $index => $group)
    {
        $groupID = $group['id'];

        $group['getLane']     = jsRaw('window.getLane');
        $group['getCol']      = jsRaw('window.getCol');
        $group['getItem']     = jsRaw('window.getItem');
        $group['minColWidth'] = $kanban->fluidBoard == '0' ? $kanban->colWidth : $kanban->minColWidth;
        $group['maxColWidth'] = $kanban->fluidBoard == '0' ? $kanban->colWidth : $kanban->maxColWidth;
        $group['colProps']    = array('actions' => jsRaw('window.getColActions'));
        $group['laneProps']   = array('actions' => jsRaw('window.getLaneActions'));
        $group['itemProps']   = array('actions' => jsRaw('window.getItemActions'));

        foreach($group['data']['cols'] as $col)
        {
            $colID = $col['id'];
            if($col['parent'] != '-1') $groupCols[$groupID][$colID] = $col['title'];
            $parentCols[$colID] = $col['parent'];
        }

        /* 计算各个列上的卡片数量。 */
        foreach($group['data']['items'] as $colGroup)
        {
            foreach($colGroup as $colID => $items)
            {
                if(!isset($columnCount[$colID])) $columnCount[$colID] = 0;
                $columnCount[$colID] += count($items);

                if(isset($parentCols[$colID]) && $parentCols[$colID] > 0)
                {
                    if(!isset($columnCount[$parentCols[$colID]])) $columnCount[$parentCols[$colID]] = 0;
                    $columnCount[$parentCols[$colID]] += count($items);
                }
            }
        }

        $kanbanList[$regionID]['items'][$index] = $group;
    }

    $laneCount += $region['laneCount'];
}

jsVar('laneCount',  $laneCount);
jsVar('kanbanLang', $lang->kanban);
jsVar('columnLang', $lang->kanbancolumn);
jsVar('laneLang', $lang->kanbanlane);
jsVar('cardLang', $lang->kanbancard);
jsVar('kanbanID', $kanban->id);
jsVar('kanban', $kanban);
jsVar('groupCols', $groupCols);
jsVar('columnCount', $columnCount);
jsVar('vision', $config->vision);
jsVar('colorList', $config->kanban->cardColorList);

zui::kanbanList
(
    set::key('kanban'),
    set::items($kanbanList),
    set::height('calc(100vh - 80px)')
);
