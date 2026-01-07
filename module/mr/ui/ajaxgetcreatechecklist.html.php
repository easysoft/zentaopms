<?php
declare(strict_types=1);
namespace zin;
global $app;
jsVar('repoID', $repoID);

$config->mr->createCheck->linkObject->dtable->fieldList['createdBy']['map'] = $users;
$config->mr->createCheck->linkObject->dtable->fieldList['type']['map']      = array('story' => $lang->story->common, 'task' => $lang->task->common, 'bug' => $lang->bug->common);

tabs
(
    tabPane
    (
        set::key('commit'),
        set::title($lang->mr->commitLogs),
        set::active(true),
        dtable
        (
            set::cols($config->mr->createCheck->commit->dtable->fieldList),
            set::data($commits),
            set::footPager(usePager())
        )
    ),
    tabPane
    (
        set::key('diff'),
        set::title($lang->mr->viewDiff),
    ),
    tabPane
    (
        set::key('object'),
        set::title($lang->mr->linkedObject),
        dtable
        (
            set::cols($config->mr->createCheck->linkObject->dtable->fieldList),
            set::data($objects),
            set::footPager(usePager())
        )
    ),
);
