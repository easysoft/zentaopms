<?php
declare(strict_types=1);
/**
 * The create view file of release module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     release
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->release->create),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('name'),
            set::label($lang->release->name),
        ),
        formGroup
        (
            set::width('1/4'),
            setClass('items-center'),
            checkbox(
                set::name('marker'),
                set::rootClass('ml-4'),
                set::value(1),
                set::text($lang->release->marker),
            ),
            $lastRelease ? '(' . $lang->release->last . ': ' . $lastRelease->name . ')' : ''
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('build[]'),
            set::label($lang->release->includedBuild),
            set::items($builds),
        ),
        formGroup
        (
            set::width('1/2'),
            setClass('items-center'),
            checkbox(
                set::name('sync'),
                set::rootClass('ml-4'),
                set::value(1),
                set::text($lang->release->syncFromBuilds),
            ),
        ),
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::name('date'),
            set::label($lang->release->date),
            set::value(helper::today()),
            set::control('date')
        ),
    ),
    formGroup
    (
        set::label($lang->release->desc),
        editor
        (
            set::name('desc'),
            set::rows('10'),
        )
    ),
    formGroup
    (
        set::label($lang->release->mailto),
        set::name('mailto[]'),
        set::items($users),
    ),
    formGroup
    (
        set::name('files[]'),
        set::label($lang->release->files),
        set::control('file')
    ),
);

/* ====== Render page ====== */
render();
