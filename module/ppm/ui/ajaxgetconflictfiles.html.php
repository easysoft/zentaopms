<?php
declare(strict_types=1);
namespace zin;

tabs
(
    tabPane
    (
        set::key('commit'),
        set::title($lang->ppm->conflictFiles . ' (' . count($conflictFiles) . ')'),
        set::active(true),
        dtable
        (
            set::cols($config->ppm->createCheck->conflictFile->dtable->fieldList),
            set::data($conflictFiles),
            set::loadPartial(true)
        )
    )
);
