<?php
namespace zin;

$laneCount = 0;
foreach($kanbanList as $regionID => $region)
{
    foreach($region['items'] as $groupID => $group)
    {
        $group['getLane']   = jsRaw('window.getLane');
        $group['getCol']    = jsRaw('window.getCol');
        $group['getItem']   = jsRaw('window.getItem');
        $group['colProps']  = array('actions' => jsRaw('window.getColActions'));
        $group['laneProps'] = array('actions' => jsRaw('window.getLaneActions'));
        $group['itemProps'] = array('actions' => jsRaw('window.getItemActions'));

        $kanbanList[$regionID]['items'][$groupID] = $group;
    }

    $laneCount += $region['laneCount'];
}

jsVar('laneCount',  $laneCount);
jsVar('kanbanLang', $lang->kanban);
jsVar('kanbanID', $kanbanID);

zui::kanbanList
(
    set::key('kanban'),
    set::items($kanbanList),
    set::height('calc(100vh - 80px)')
);
