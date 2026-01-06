<?php
declare(strict_types=1);
namespace zin;
jsVar('repoID', $repoID);

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
    ),
);
