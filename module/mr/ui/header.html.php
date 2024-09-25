<?php
declare(strict_types=1);
namespace zin;

$moduleName = $app->rawModule;
$methodName = $app->rawMethod;
if(empty($type)) $type = '';
$headers = nav
(
    li
    (
        setClass('nav-item'),
        a
        (
            $lang->mr->view,
            set::href(createLink($moduleName, 'view', "MRID={$MR->id}")),
            set('data-app', $app->tab),
            $methodName == 'view' ? setClass('active') : null
        )
    ),
    li
    (
        setClass('nav-item'),
        a
        (
            $lang->mr->commitLogs,
            set::href(createLink($moduleName, 'commitlogs', "MRID={$MR->id}")),
            set('data-app', $app->tab),
            $methodName == 'commitlogs' ? setClass('active') : null
        )
    ),
    li
    (
        setClass('nav-item'),
        a
        (
            $lang->mr->viewDiff,
            set::href(createLink($moduleName, 'diff', "MRID={$MR->id}")),
            set('data-app', $app->tab),
            $methodName == 'diff' ? setClass('active') : null
        )
    ),
    li
    (
        setClass('nav-item story'),
        a
        (
            icon($lang->icons['story']),
            $lang->productplan->linkedStories,
            set('data-app', $app->tab),
            set::href($methodName == 'link' ? '#mr-story' : createLink($moduleName, 'link', "MRID={$MR->id}&type=story")),
            $methodName == 'link' ? set('data-toggle', 'tab') : null,
            $methodName == 'link' && $type == 'story' ? setClass('active') : null
        )
    ),
    li
    (
        setClass('nav-item bug'),
        a
        (
            icon($lang->icons['bug']),
            $lang->productplan->linkedBugs,
            set('data-app', $app->tab),
            set::href($methodName == 'link' ? '#mr-bug' : createLink($moduleName, 'link', "MRID={$MR->id}&type=bug")),
            $methodName == 'link' ? set('data-toggle', 'tab') : null,
            $methodName == 'link' && $type == 'bug' ? setClass('active') : null
        )
    ),
    li
    (
        setClass('nav-item task'),
        a
        (
            icon('todo'),
            $lang->mr->linkedTasks,
            set('data-app', $app->tab),
            set::href($methodName == 'link' ? '#mr-task' : createLink($moduleName, 'link', "MRID={$MR->id}&type=task")),
            $methodName == 'link' ? set('data-toggle', 'tab') : null,
            $methodName == 'link' && $type == 'task' ? setClass('active') : null
        )
    )
);
