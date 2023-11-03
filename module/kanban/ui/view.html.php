<?php
namespace zin;

$laneCount = 0;
foreach($kanbanList as $regionID => $region)
{
    foreach($region['items'] as $groupID => $group)
    {
        $group['getLane'] = jsRaw('window.getLane');
        $group['getCol']  = jsRaw('window.getCol');
        $group['getItem'] = jsRaw('window.getItem');

        $kanbanList[$regionID]['items'][$groupID] = $group;
    }

    $laneCount += $region['laneCount'];
}

jsVar('laneCount', $laneCount);

zui::kanbanList
(
    set::key('kanban'),
    set::items($kanbanList),
    set::height('calc(100vh - 50px)')
);
