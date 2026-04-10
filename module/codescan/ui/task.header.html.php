<?php
declare(strict_types=1);
namespace zin;
global $app;
if(!empty($repoID)) dropmenu(set::objectID($repoID), set::tab('repo'));

detailHeader
(
    to::prefix
    (
        backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            set::back('codescan-task'),
            $lang->goback
        ),
        entityLabel(set(array('entityID' => $taskID, 'level' => 2, 'text' => $task->name))),
    )
);

$headers = nav
(
    setClass('flex-auto'),
    /* li（扫描任务详情 tab）先注释掉。
    li
    (
        setClass('nav-item'),
        a
        (
            icon('flag', setClass('text-special')),
            $lang->codescan->taskView,
            set::href(inLink('taskview', "serviceRepoID={$serviceRepoID}&taskID={$taskID}&repoID={$repoID}&type=view")),
            set('data-app', $app->tab),
            $type == 'view' ? setClass('active') : null
        )
    )
    */
    li
    (
        setClass('nav-item link'),
        a
        (
            icon('bug', setClass('text-special')),
            $lang->codescan->issue,
            set::href(inLink('taskview', "serviceRepoID={$serviceRepoID}&taskID={$taskID}&repoID={$repoID}&type=issue")),
            $type == 'issue' ? setClass('active') : null
        )
    ),
    /* li（扫描日志tab）先注释掉。
    li
    (
        setClass('nav-item'),
        a
        (
            icon('file-log', setClass('text-special')),
            $lang->codescan->taskLog,
            set::href(inLink('taskview', "serviceRepoID={$serviceRepoID}&taskID={$taskID}&repoID={$repoID}&type=log")),
            set('data-app', $app->tab),
            $type == 'log' ? setClass('active') : null
        )
    )
    */
);
