<?php
declare(strict_types=1);
/**
 * The edit view file of productplan module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easycorp.ltd>
 * @package     productplan
 * @link        http://www.zentao.net
 */

namespace zin;

formPanel
(
    set::title($lang->productplan->edit),
    on::change('#future_', 'toggleDateVisibility'),
    formGroup
    (
        set::width("1/2"),
        set::name("parent"),
        set::label($lang->productplan->parent),
        set::control('picker'),
        set::value("0"),
        set::items($parentPlanPairs)
    ),
    formGroup
    (
        set::width("1/2"),
        set::name("branch[]"),
        set::label($lang->product->branch),
        set::required(true),
        set::control('picker'),
        set::multiple(true),
        set::id("branch"),
        set::items($branches)
    ),
    formGroup
    (
        set::width("1/2"),
        set::name("title"),
        set::label($lang->productplan->title),
        set::required(true),
        set::control("text")
    ),
    formRow
    (
        formGroup
        (
            set::width("1/4"),
            set::name("begin"),
            set::label($lang->execution->charts->cfd->begin),
            set::value("2023-06-08"),
            set::control("date")
        ),
        formGroup
        (
            set::width("1/4"),
            set::class("ml-4"),
            checkbox
            (
                set::name("future"),
                set::text($lang->productplan->future)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width("1/4"),
            set::name("end"),
            set::label($lang->execution->charts->cfd->end),
            set::control("date")
        ),
        formGroup
        (
            set::width("3/4"),
            radioList
            (
                set::name("delta"),
                set::control("radioList"),
                set::items($lang->productplan->endList),
                set::inline(true)
            )
        )
    ),
    formGroup
    (
        set::width("full"),
        set::name("desc"),
        set::label($lang->story->spec),
        set::control("editor")
    ),
    formGroup
    (
        set::name("product"),
        set::control('hidden'),
        set::value($product->id),
    ),
);

render();
