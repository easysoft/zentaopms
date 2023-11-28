<?php
declare(strict_types=1);
/**
 * The view file of metric module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      zhouxin<zhouxin@easycorp.ltd>
 * @package     metric
 * @link        http://www.zentao.net
 */
namespace zin;

jsVar('metricID', $metric->id);
jsVar('params', $params);

/**
 * Build content of table data.
 *
 * @param  array  $items
 * @access public
 * @return array
 */
$buildItems = function($items): array
{
    $itemList = array();
    foreach($items as $item)
    {
        $itemList[] = item
        (
            set::name($item['name']),
            !empty($item['href']) ? a
            (
                set::href($item['href']),
                !empty($item['attr']) && is_array($item['attr']) ? set($item['attr']) : null,
                $item['text']
            ) : $item['text'],
            set::collapse(!empty($item['text']))
        );
    }

    return $itemList;
};

$formAction = array(
    array(
        'text' => $lang->metric->testMetric,
        'class' => 'secondary',
        'type' => 'submit',
        'onclick' => 'window.testMetric()'
    )
);

/**
 * Build default value and query value control.
 *
 * @param string $name
 * @param string $controlType
 * @param string $value
 * @param string $optionType
 * @access public
 * @return array
 */
$buildValueControl = function($paramName, $controlType, $value, $optionType, $varID)
{
    $name = $paramName . '[]';
    $options = $this->metric->getControlOptions($optionType);
    $dateControl = formGroup
    (
        set::width('150px'),
        set::label(''),
        datePicker
        (
            set::name($name),
            set::value($value)
        ),
        setClass("form-body-item {$paramName}-{$varID}-date"),
        setClass($controlType !== 'date' ? 'hidden' : '')
    );
    $selectControl = formGroup
    (
        set::width('150px'),
        set::label(''),
        picker
        (
            set::name($name),
            set::items($options),
            set::disabled($controlType !== 'select' ? true : false)
        ),
        setClass("form-body-item {$paramName}-{$varID}-select"),
        setClass($controlType !== 'select' ? 'hidden' : '')
    );
    $inputControl = formGroup
    (
        set::width('150px'),
        set::label(''),
        input
        (
            set::name($name),
            set::value($value),
            set::disabled($controlType !== 'input' ? true : false)
        ),
        setClass("form-body-item {$paramName}-{$varID}-input"),
        setClass($controlType !== 'input' ? 'hidden' : '')
    );

    return div
    (
        $dateControl,
        $selectControl,
        $inputControl,
        setClass("{$paramName}-{$varID}-group")
    );

    return $control;
};

$formHeader = div
(
    div
    (
        setClass('form-header-item'),
        setStyle('width', '50px'),
        setStyle('text-align', 'center'),
        $lang->metric->param->varName
    ),
    div
    (
        setClass('form-header-item'),
        setStyle('width', '150px'),
        setStyle('text-align', 'center'),
        $lang->metric->param->showName
    ),
    div
    (
        setClass('form-header-item'),
        setStyle('width', '300px'),
        setStyle('text-align', 'center'),
        $lang->metric->param->varType
    ),
    div
    (
        setClass('form-header-item'),
        setStyle('width', '150px'),
        $lang->metric->param->defaultValue,
        setStyle('text-align', 'center')
    ),
    div
    (
        setClass('form-header-item'),
        setStyle('width', '150px'),
        setStyle('text-align', 'center'),
        $lang->metric->param->queryValue
    ),
    setClass('flex form-header'),
    setStyle('justify-content', 'space-between'),
    setStyle('flex', '1')
);

/**
 * Build a param control group.
 *
 * @param object   $param
 * @param callable $buildValueControl
 * @access public
 * @return array
 */
$buildParamControlGroup = function($param, $buildValueControl, $typeList, $optionList)
{
    $varID   = ltrim($param['varName'], '$');
    $varType = zget($param, 'varType', '');

    $varNameLabel = div
    (
        $param['varName'],
        setStyle('width', '50px'),
        setClass("form-body-item varName-{$varID}")
    );
    $showNameControl = formGroup
    (
        set::width('150px'),
        set::control('input'),
        set::name('showName[]'),
        set::value($param['showName']),
        setClass("form-body-item showName-{$varID}")
    );
    $varTypeControl = formGroup
    (
        set::width('150px'),
        set::name('varType[]'),
        set::control('select'),
        set::items($typeList),
        set::value(zget($param, 'varType')),
        set::label(''),
        setClass("form-body-item varType-{$varID}")
    );
    $optionsControl = formGroup
    (
        set::width('150px'),
        set::name('options[]'),
        set::control('select'),
        set::items($optionList),
        set::value($param['options']),
        setClass("form-body-item options-{$varID}"),
        set::label('')
    );
    $defaultValueControl = $buildValueControl('defaultValue', $varType, zget($param, 'defaultValue', ''), $param['options'], $varID);
    $queryValueControl  = $buildValueControl('queryValue', $varType, zget($param, 'queryValue', ''), $param['options'], $varID);
    $varNameControl = formGroup
    (
        set::className('hidden'),
        set::control('hidden'),
        set::name('varName[]'),
        set::value($param['varName']),
        setClass("form-body-item varNameLabel-{$varID}")
    );

    $paramControlGroup = formRow
    (
        $varNameLabel,
        $showNameControl,
        div
        (
            $varTypeControl,
            $optionsControl,
            setClass('flex form-body-group'),
            setStyle('justify-content', 'flex-start')
        ),
        $defaultValueControl,
        $queryValueControl,
        $varNameControl,
        setClass('flex form-body'),
        setStyle('justify-content', 'space-between'),
        setStyle('align-items', 'center'),
        setStyle('flex', '1'),
        setStyle('width', '100%')
    );

    return $paramControlGroup;
};

$paramControlGroups = array();
foreach($params as $param) $paramControlGroups[] = $buildParamControlGroup($param, $buildValueControl, $lang->metric->param->typeList, $lang->metric->param->options);

$formAction = array(
    array(
        'text' => $lang->metric->testMetric,
        'class' => 'secondary',
        'type' => 'submit',
        'onclick' => 'testMetric({id})'
    )
);

detailHeader
(
    to::title
    (
        entityLabel
        (
            set::entityID($metric->id),
            set::level(1),
            set::text($metric->name)
        )
    ),
    to::prefix
    (
        backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            $lang->goback
        )
    )
);

$actionMenuList = !$metric->deleted ? $this->metric->buildOperateMenu($metric) : array();
detailBody
(
    on::change('#varType', 'toggleVarType'),
    on::change('#options', 'toggleOptionsList'),
    sectionList
    (
        section
        (
            set::title($lang->metric->declaration),
            set::content(empty(trim($metric->definition)) ? $lang->metric->noFormula : $metric->definition),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->metric->sqlStatement),
            set::content(empty(trim($metric->sql)) ? $lang->metric->noSQL : $metric->sql),
            set::useHtml(true)
        ),
        section
        (
            set::title($lang->metric->metricData),
            formBase
            (
                $formHeader,
                $paramControlGroups,
                setClass('flex params-form'),
                setStyle('flex-direction', 'column'),
                setStyle('justify-content', 'space-between'),
                set::actions($formAction),
                div
                (
                    setClass('response-box')
                )
            )
        )
    ),
    history
    (
        set::commentUrl(createLink('action', 'comment', array('objectType' => 'metric', 'objectID' => $metric->id)))
    ),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        !empty($actionMenuList['main']) ? set::main($actionMenuList['main']) : null,
        !empty($actionMenuList['suffix']) ? set::suffix($actionMenuList['suffix']) : null,
        set::object($metric)
    ),
    detailSide
    (
        tabs
        (
            set::collapse(true),
            tabPane
            (
                set::key('legendBasicInfo'),
                set::className('overflow-hidden'),
                set::title($lang->metric->legendBasicInfo),
                set::active(true),
                tableData
                (
                    $buildItems($legendBasic)
                )
            )
        )
    )
);

if(!isInModal())
{
    floatPreNextBtn
    (
        !empty($preAndNext->pre)  ? set::preLink(createLink('metric', 'view', "id={$preAndNext->pre->id}"))   : null,
        !empty($preAndNext->next) ? set::nextLink(createLink('metric', 'view', "id={$preAndNext->next->id}")) : null
    );
}

render();
