<?php
declare(strict_types=1);
/**
 * The create view file of design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
namespace zin;
/* Cannot show product field in no-product project. */
$productRow = '';
if($project->hasProduct)
{
    $productRow = formRow(
        formGroup
        (
            set::width('1/2'),
            set::name('product'),
            set::label($lang->design->product),
            set::value($productID),
            set::items($products),
            set::required(true),
        )
    );
}

formPanel
(
    set::title($lang->design->create),
    $productRow,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('story'),
            set::label($lang->design->story),
            set::items($stories),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('type'),
            set::label($lang->design->type),
            set::items($lang->design->typeList),
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::name('name'),
            set::label($lang->design->name),
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->design->desc),
            editor
            (
                set::name('desc'),
                set::rows('5'),
            )
        ),
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->design->file),
            set::name('files[]'),
            set::control('file'),
        ),
    ),
);

/* ====== Render page ====== */
render();
