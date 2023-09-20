<?php
declare(strict_types=1);
/**
 * The edit view file of metric module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin <zhouxin@easycorp.ltd>
 * @package     metric
 * @link        https://www.zentao.net
 */
namespace zin;

$isCustomUnit = !isset($this->lang->metric->unitList[$metric->unit]);
$isDisabled   = $metric->stage == 'wait' && $metric->builtin === '1' && !$this->metric->isOldMetric($metric);

$afterEdit = $isDisabled ? '' : formGroup
(
    set::width('1/2'),
    set::name('afterEdit'),
    set::label($lang->metric->afterCreate),
    set::control(array('type' => 'radioList', 'inline' => true)),
    set::items($lang->metric->afterCreateList),
    set::value('implement'),
);

formPanel
(
    set::title(''),
    div
    (
        setClass('text-lg pb-2.5'),
        $lang->metric->edit
    ),
    formRow
    (
        formGroup
        (
            set::width('1/3'),
            set::label($lang->metric->scope),
            set::name('scope'),
            set::items($lang->metric->scopeList),
            set::value($metric->scope),
            set::required(true),
            set::disabled($isDisabled),
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->metric->object),
            set::name('object'),
            set::items($lang->metric->objectList),
            set::value($metric->object),
            set::required(true),
            set::disabled($isDisabled),
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->metric->purpose),
            set::name('purpose'),
            set::items($lang->metric->purposeList),
            set::value($metric->purpose),
            set::required(true),
            set::disabled($isDisabled),
        ),
    ),
    formGroup
    (
        set::label($lang->metric->name),
        set::name('name'),
        set::value($metric->name),
        set::required(true),
    ),
    formGroup
    (
        set::label($lang->metric->code),
        set::name('code'),
        set::value($metric->code),
        set::required(true),
        set::disabled($isDisabled),
    ),
    formRow
    (
        set::id('unitBox'),
        setClass($isCustomUnit ? 'hidden' : ''),
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
                        set::value($isCustomUnit ? '' : $metric->unit),
                        set::disabled($isDisabled),
                    )
                ),
                div
                (
                    setClass('flex items-center pl-2 clip'),
                    checkbox
                    (
                        set::name('customUnit'),
                        set::text($lang->metric->customUnit),
                        set::checked($isCustomUnit),
                        set::disabled($isDisabled),
                    )
                )
            )
        )
    ),
    formRow
    (
        set::id('addUnitBox'),
        setClass($isCustomUnit ? '' : 'hidden'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->metric->unit),
            inputGroup
            (
                div
                (
                    setClass('grow'),
                    input(set::name('addunit'), set::value($isCustomUnit ? $metric->unit : '')),
                ),
                div
                (
                    setClass('flex items-center pl-2 clip'),
                    checkbox
                    (
                        set::name('customUnit'),
                        set::text($lang->metric->customUnit),
                        set::checked($isCustomUnit),
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
        set::value($metric->desc),
        set::placeholder($lang->metric->descTip),
    ),
    formGroup
    (
        set::label($lang->metric->definition),
        set::control(array('type' => 'textarea', 'rows' => 3)),
        set::name('definition'),
        set::value($metric->definition),
        set::placeholder($lang->metric->definitionTip),
        set::disabled($isDisabled),
    ),
    $afterEdit,
    set::submitBtnText($lang->save),
);
