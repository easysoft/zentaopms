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
            set::value('global'),
            set::required(true),
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->metric->object),
            set::name('object'),
            set::items($lang->metric->objectList),
            set::value('program'),
            set::required(true),
        ),
        formGroup
        (
            set::width('1/3'),
            set::label($lang->metric->purpose),
            set::name('purpose'),
            set::items($lang->metric->purposeList),
            set::value('scale'),
            set::required(true),
        ),
    ),
    formGroup
    (
        set::label($lang->metric->name),
        set::name('name'),
        set::placeholder($lang->metric->nameTip),
        set::required(true),
    ),
    formGroup
    (
        set::label($lang->metric->code),
        set::name('code'),
        set::placeholder($lang->metric->codeTip),
        set::required(true),
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->metric->unit),
        set::name('unit'),
        set::items($lang->metric->unitList),
        set::required(true),
    ),
    formGroup
    (
        set::label($lang->metric->desc),
        set::control(array('type' => 'textarea', 'rows' => 3)),
        set::name('desc'),
        set::placeholder($lang->metric->descTip),
        set::required(true),
    ),
    formGroup
    (
        set::label($lang->metric->definition),
        set::control(array('type' => 'textarea', 'rows' => 3)),
        set::name('definition'),
        set::placeholder($lang->metric->definitionTip),
        set::required(true),
    ),
    formGroup
    (
        set::width('1/2'),
        set::name('afterCreate'),
        set::label($lang->metric->afterCreate),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->metric->afterCreateList),
        set::value('back'),
        set::required(true),
    ),

);
