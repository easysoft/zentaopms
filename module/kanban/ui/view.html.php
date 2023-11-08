<?php
namespace zin;

dropmenu(set::tab('kanban'), set::objectID($kanban->id));

$laneCount = 0;
$groupCols = array(); // 卡片可以移动到的同一group下的列。
foreach($kanbanList as $regionID => $region)
{
    foreach($region['items'] as $index => $group)
    {
        $groupID = $group['id'];

        $group['getLane']     = jsRaw('window.getLane');
        $group['getCol']      = jsRaw('window.getCol');
        $group['getItem']     = jsRaw('window.getItem');
        $group['canDrop']     = jsRaw('window.canDrop');
        $group['onDrop']      = jsRaw('window.onDrop');
        $group['minColWidth'] = $kanban->fluidBoard == '0' ? $kanban->colWidth : $kanban->minColWidth;
        $group['maxColWidth'] = $kanban->fluidBoard == '0' ? $kanban->colWidth : $kanban->maxColWidth;
        $group['colProps']    = array('actions' => jsRaw('window.getColActions'));
        $group['laneProps']   = array('actions' => jsRaw('window.getLaneActions'));
        $group['itemProps']   = array('actions' => jsRaw('window.getItemActions'));

        foreach($group['data']['cols'] as $colIndex => $col)
        {
            if($col['parent'] != '-1') $groupCols[$groupID][$col['id']] = $col['title'];
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
jsVar('vision', $config->vision);
jsVar('colorList', $config->kanban->cardColorList);
jsVar('canMoveCard', common::hasPriv('kanban', 'moveCard'));

zui::kanbanList
(
    set::key('kanban'),
    set::items($kanbanList),
    set::height('calc(100vh - 80px)')
);
