<?php
declare(strict_types=1);
/**
 * The create view file of company module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     metric
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title(''),
    div
    (
        setClass('text-lg pb-2.5'),
        $lang->metric->create
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->metric->scope),
            set::name('scope'),
            set::items($lang->metric->scopeList),
            set::value('system'),
            set::required(true)
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->metric->object),
            set::name('object'),
            set::items($lang->metric->objectList),
            set::value('program'),
            set::required(true)
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->metric->purpose),
            set::name('purpose'),
            set::items($lang->metric->purposeList),
            set::value('scale'),
            set::required(true)
        ),
    ),
    formGroup
    (
        set::label($lang->metric->name),
        set::name('name'),
        set::required(true),
    ),
    formGroup
    (
        set::label($lang->metric->code),
        set::name('code'),
        set::required(true),
    ),
    formRow
    (
        set::id('unitBox'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->metric->unit),
            inputGroup
            (
                div
                (
                    setClass('grow'),
                    picker
                    (
                        set::name('unit'),
                        set::items($lang->metric->unitList),
                    )
                ),
                div
                (
                    setClass('flex items-center pl-2 clip'),
                    checkbox
                    (
                        set::name('customUnit'),
                        set::text($lang->metric->customUnit),
                    )
                )
            )
        )
    ),
    formRow
    (
        set::id('addUnitBox'),
        setClass('hidden'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->metric->unit),
            inputGroup
            (
                div
                (
                    setClass('grow'),
                    input(set::name('addunit')),
                ),
                div
                (
                    setClass('flex items-center pl-2 clip'),
                    checkbox
                    (
                        set::name('customUnit'),
                        set::text($lang->metric->customUnit),
                    )
                )
            )
        )
    ),
    on::change('[name=customUnit]', 'addUnit'),
    formGroup
    (
        set::label($lang->metric->desc),
        set::control(array('type' => 'textarea', 'rows' => 3)),
        set::name('desc'),
        set::placeholder($lang->metric->descTip),
    ),
    formGroup
    (
        set::label($lang->metric->definition),
        set::control(array('type' => 'textarea', 'rows' => 3)),
        set::name('definition'),
        set::placeholder($lang->metric->definitionTip),
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('afterCreate'),
        set::label($lang->metric->afterCreate),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->metric->afterCreateList),
        set::value('implement'),
    ),
    set::submitBtnText($lang->save),
);
