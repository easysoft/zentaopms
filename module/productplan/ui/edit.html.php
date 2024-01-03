<?php
declare(strict_types=1);
/**
 * The edit view file of productplan module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     productplan
 * @link        https://www.zentao.net
 */
namespace zin;

jsVar('today', helper::today());
jsVar('weekend', $config->execution->weekend);
jsVar('productID', $productID);
jsVar('oldBranch', $oldBranch);
jsVar('planID', $plan->id);
jsVar('parentList', $parentList);

$deltaValue = $plan->end == $config->productplan->future ? 0 : (strtotime($plan->end) - strtotime($plan->begin)) / 3600 / 24 + 1;

formPanel
(
    setID('editForm'),
    set::title($lang->productplan->edit),
    !$product->shadow ? formGroup
    (
        set::className('items-center'),
        set::label($lang->productplan->product),
        $product->name
    ) : null,
    $plan->parent == '-1' ? formHidden('parent', $plan->parent) : formGroup
    (
        set::width('1/2'),
        set::label($lang->productplan->parent),
        set::name('parent'),
        set::items($parentPlanPairs),
        set::value($plan->parent),
        $product->type != 'normal' ? on::change('loadBranches') : ''
    ),
    !$product->shadow && $product->type != 'normal' ? formGroup
    (
        set::width('1/2'),
        set::label($lang->productplan->branch),
        set::required(true),
        picker
        (
            setID('branch'),
            set::name('branch[]'),
            set::items($branchTagOption),
            set::value($plan->branch),
            set::multiple(true)
        )
    ) : null,
    formGroup
    (
        set::width('1/2'),
        set::label($lang->productplan->title),
        set::name('title'),
        set::value($plan->title)
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->productplan->status),
        set::name('status'),
        set::items(array_slice($lang->productplan->statusList, ($plan->status == 'wait' ? 0 : 1))),
        set::value($plan->status),
        set::required(true),
        set::disabled($plan->parent == -1)
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
            set::value($plan->begin != $config->productplan->future ? formatTime($plan->begin) : '')
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
                set::checked($plan->begin  == $config->productplan->future && $plan->end == $config->productplan->future),
                on::change('toggleDateBox')
            )
        )
    ),
    formRow
    (
        setClass($plan->begin  == $config->productplan->future && $plan->end == $config->productplan->future ? 'hidden' : ''),
        formGroup
        (
            set::width('1/4'),
            set::label($lang->productplan->end),
            set::control('date'),
            setID('end'),
            set::name('end'),
            set::value($plan->end != $config->productplan->future ? formatTime($plan->end) : '')
        ),
        formGroup
        (
            radioList
            (
                set::name('delta'),
                set::inline(true),
                set::items($lang->productplan->endList),
                set::value($deltaValue),
                on::change('computeEndDate')
            )
        ),
        formHidden('product', $product->id)
    ),
    formGroup
    (
        set::label($lang->productplan->desc),
        set::required(strpos(",{$this->config->productplan->edit->requiredFields},", ",desc,") !== false),
        editor
        (
            set::name('desc'),
            html($plan->desc)
        )
    )
);

/* ====== Render page ====== */
render();
