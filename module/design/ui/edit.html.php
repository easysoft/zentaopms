<?php
declare(strict_types=1);
/**
 * The edit view file of design module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     design
 * @link        https://www.zentao.net
 */
namespace zin;

/* zin: Set variables to define picker options for form. */
jsVar('projectID', $project->id);
jsVar('type', strtolower($design->type));

/* Cannot show product field in no-product project. */
$productRow = '';
if($project->hasProduct)
{
    $productRow = formGroup(
        set::width('1/2'),
        set::name('product'),
        set::label($lang->design->product),
        set::value($design->product),
        set::items($products),
        set::required(true),
        on::change('loadStory')
    );
}

formPanel
(
    set::title(''),
    set::back('GLOBAL'),
    set::backUrl(helper::createLink('design', 'browse', "projectID={$project->id}")),
    entityLabel
    (
        set::entityID($design->id),
        set::level(1),
        set::text($design->name)
    ),
    $productRow,
    formGroup
    (
        set::width('1/2'),
        set::name('story'),
        set::label($lang->design->story),
        set::value($design->story),
        set::items($stories)
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('type'),
        set::label($lang->design->type),
        set::value($design->type),
        set::items($lang->design->typeList)
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('name'),
        set::value($design->name),
        set::label($lang->design->name)
    ),
    formGroup
    (
        set::label($lang->design->desc),
        editor
        (
            set::name('desc'),
            html($design->desc),
        )
    ),
    formGroup
    (
        set::label($lang->design->file),
        upload()
    )
);

/* ====== Render page ====== */
render();
