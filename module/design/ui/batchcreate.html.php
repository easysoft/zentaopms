<?php
declare(strict_types=1);
/**
 * The batchCreate view file of design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('type', strtolower($type));

/* ====== Define the page structure with zin widgets ====== */
formBatchPanel
(
    formBatchItem
    (
        set::name('id'),
        set::label($lang->idAB),
        set::control('index'),
        set::width('32px')
    ),
    formBatchItem
    (
        set::name('story'),
        set::label($lang->design->story),
        set::control('picker'),
        set::items($stories),
        set::width('200px'),
        set::ditto(true)
    ),
    formBatchItem
    (
        set::name('type'),
        set::label($lang->design->type),
        set::control('picker'),
        set::items($lang->design->typeList),
        set::width('200px'),
        set::ditto(true)
    ),
    formBatchItem
    (
        set::name('name'),
        set::label($lang->design->name),
        set::width('240px')
    ),
    formBatchItem
    (
        set::name('desc'),
        set::label($lang->design->desc),
        set::control('textarea'),
        set::width('240px')
    )
);

/* ====== Render page ====== */
render();
