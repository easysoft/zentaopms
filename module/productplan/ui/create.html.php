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
jsVar('productID', $product->id);
jsVar('lastLang', $lang->productplan->last);
jsVar('parentPlanID', $parent);
jsVar('parentList', $parentList);

if($parent)
{
    foreach($branches as $branchID => $branchName)
    {
        if(strpos(",$parentPlan->branch,", ",$branchID,") === false) unset($branches[$branchID]);
    }
}

formPanel
(
    setID('createPlanPanel'),
    set::ajax(array('beforeSubmit' => jsRaw("clickSubmit"))),
    set::title($parent ? $lang->productplan->createChildren : $lang->productplan->create),
    $parent ? formGroup
    (
        set::className('items-center'),
        set::label($lang->productplan->parent),
        $parentPlan->title
    ) : null,
    !$parent && !$product->shadow ? formGroup
    (
        set::className('items-center'),
        set::label($lang->productplan->product),
        $product->name
    ) : null,
    !$parent ? formGroup
    (
        set::width('1/2'),
        set::label($lang->productplan->parent),
        picker
        (
            set::name('parent'),
            set::items($parentPlanPairs),
            $product->type != 'normal' ? on::change('loadBranches') : ''
        )
    ) : formHidden('parent', $parent),
    !$product->shadow && $product->type != 'normal' ? formGroup
    (
        set::width('1/2'),
        set::label($lang->productplan->branch),
        set::required(true),
        picker
        (
            setID('branch'),
            set::name('branch[]'),
            set::items($branches),
            set::multiple(true),
            on::change('loadTitle')
        )
    ) : null,
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->productplan->title),
            set::name('title')
        ),
        $lastPlan ? formGroup
        (
            set::width('1/2'),
            setClass('items-center text-gray'),
            span
            (
                setClass('ml-4'),
                setID('lastTitleBox'),
                '(' . $lang->productplan->last . ': ' . $lastPlan->title . ')'
            )
        ) : null
    ),
    formRow
    (
        formGroup
        (
            set::width('1/4'),
            set::label($lang->productplan->begin),
            set::control('date'),
            setID('begin'),
            set::name('begin'),
            set::value(formatTime($begin))
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
            setID('end'),
            set::name('end')
        ),
        formGroup
        (
            radioList
            (
                set::name('delta'),
                set::inline(true),
                set::items($lang->productplan->endList),
                on::change('computeEndDate')
            )
        )
    ),
    formGroup
    (
        set::label($lang->productplan->desc),
        set::name('desc'),
        set::control('editor'),
        set::rows(10)
    ),
    formHidden('product', $product->id)
);

/* ====== Render page ====== */
render();
