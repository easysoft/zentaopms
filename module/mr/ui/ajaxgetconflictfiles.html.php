<?php
declare(strict_types=1);
namespace zin;

tabs
(
    tabPane
    (
        set::key('commit'),
        set::title($lang->mr->conflictFiles . ' (' . count($conflictFiles) . ')'),
        set::active(true),
        dtable
        (
            set::cols($config->mr->createCheck->conflictFile->dtable->fieldList),
            set::data($conflictFiles),
            set::loadPartial(true)
        )
    )
);
