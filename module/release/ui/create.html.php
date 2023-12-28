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

jsVar('projectID', isset($projectID) ? $projectID : 0);

$productRow = array();
if(!empty($projectID))
{
    $productRow[] = on::change('#product', 'loadBuilds');
    $productRow[] = formRow
    (
        setClass($product->shadow ? 'hidden' : ''),
        formGroup
        (
            set::width('1/2'),
            set::name('product'),
            set::label($lang->release->product),
            set::items($products),
            set::value($product->id)
        )
    );
}

formPanel
(
    set::title($lang->release->create),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('name'),
            set::label($lang->release->name)
        ),
        $app->tab != 'project' || empty($product->shadow) ? formGroup
        (
            set::width('1/2'),
            setClass('items-center'),
            checkbox(
                set::name('marker'),
                set::rootClass('ml-4'),
                set::value(1),
                set::text($lang->release->marker)
            ),
            $lastRelease ? '(' . $lang->release->last . ': ' . $lastRelease->name . ')' : ''
        ) : ''
    ),
    $productRow,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->release->includedBuild),
            picker
            (
                set::name('build[]'),
                set::placeholder($lang->build->placeholder->multipleSelect),
                set::items($builds),
                set::multiple(true)
            )
        ),
        formGroup
        (
            set::width('1/2'),
            setClass('items-center'),
            checkbox(
                set::name('sync'),
                set::rootClass('ml-4'),
                set::checked(1),
                set::value(1),
                set::text($lang->release->syncFromBuilds)
            )
        )
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
        )
    ),
    formGroup
    (
        set::label($lang->release->desc),
        set::name('desc'),
        set::control('editor'),
        set::rows('10')
    ),
    formGroup
    (
        set::label($lang->release->mailto),
        mailto(set::items($users))
    ),
    formGroup
    (
        set::label($lang->release->files),
        upload()
    )
);

/* ====== Render page ====== */
render();
