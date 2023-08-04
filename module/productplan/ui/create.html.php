<?php
declare(strict_types=1);
/**
 * The create view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('weekend', $config->execution->weekend);
jsVar('productID', $productID);
jsVar('lastLang', $lang->productplan->last);
jsVar('parentPlanID', $parent);

if($parent)
{
    foreach($branches as $branchID => $branchName)
    {
        if(strpos(",$parentPlan->branch,", ",$branchID,") === false) unset($branches[$branchID]);
    }
}

formPanel
(
    setID('createForm'),
    set::title($parent ? $lang->productplan->createChildren : $lang->productplan->create),
    $parent ? formGroup
    (
        set::class('items-center'),
        set::label($lang->productplan->parent),
        $parentPlan->title
    ) : null,
    !$parent && !$product->shadow ? formGroup
    (
        set::class('items-center'),
        set::label($lang->productplan->product),
        $product->name
    ) : null,
    !$parent ? formGroup
    (
        set::width('1/2'),
        set::label($lang->productplan->parent),
        set::name('parent'),
        set::items($parentPlanPairs),
        $product->type != 'normal' ? on::change('loadBranches') : '',
    ) : null,
    !$product->shadow && $product->type != 'normal' ? formGroup
    (
        set::width('1/2'),
        set::label($lang->productplan->branch),
        set::required(true),
        select
        (
            set::name('branch[]'),
            set::items($branches),
            set::multiple(true),
            on::change('loadTitle'),
        )
    ) : null,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->productplan->title),
            set::name('title'),
        ),
        $lastPlan ? formGroup
        (
            set::width('1/2'),
            setClass('items-center text-gray'),
            span
            (
                setClass('ml-4'),
                set::id('lastTitleBox'),
                '(' . $lang->productplan->last . ': ' . $lastPlan->title . ')'
            ),
        ) : null,
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->productplan->begin),
            set::control('date'),
            set::name('begin'),
            set::value(formatTime($begin)),
        ),
        formGroup
        (
            setClass('items-center'),
            checkbox
            (
                set::name('future'),
                set::text($lang->productplan->future),
                set::value(1),
                set::rootClass('ml-4'),
                on::change('toggleDateBox')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->productplan->end),
            set::control('date'),
            set::name('end'),
        ),
        formGroup
        (
            radioList
            (
                set::name('delta'),
                set::inline(true),
                set::items($lang->productplan->endList),
                on::change('computeEndDate'),
            )
        )
    ),
    formGroup
    (
        set::label($lang->productplan->desc),
        editor
        (
            set::name('desc'),
            set::rows(10),
        )
    ),
    formHidden('product', $product->id),
    formHidden('parent', $parent)
);

/* ====== Render page ====== */
render();
