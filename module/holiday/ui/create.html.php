<?php
declare(strict_types=1);
/**
 * The create view file of holiday module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu <liumengyi@easycorp.ltd>
 * @package     holiday
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->holiday->create),
    set::formClass('border-0'),
    formRow
    (
        formGroup
        (
            set::label($lang->holiday->type),
            radioList
            (
                set::inline(true),
                set::name('type'),
                set::items($lang->holiday->typeList),
                set::value('holiday')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->holiday->begin),
            set::control('date'),
            set::name('begin'),
            set::value(''),
            set::required('true')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->holiday->end),
            set::control('date'),
            set::name('end'),
            set::value(''),
            set::required('true')
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->holiday->name),
            set::name('name'),
            set::value(''),
            set::required('true')
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->holiday->desc),
            textarea
            (
                set::name('desc'),
                set::rows('2')
            )
        )
    )
);

render();

