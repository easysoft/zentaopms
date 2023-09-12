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
            set::collapse(!empty($item['text'])),
        );
    }

    return $itemList;
};

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
$buildValueControl = function($name, $controlType, $value, $optionType)
{
    if($controlType == 'date')
    {
        $control = formGroup
        (
            set::width('150px'),
            set::label(''),
            datePicker
            (
                set::id($name),
                set::name($name),
                set::value($value)
            ),
            setClass('form-body-item'),
        );
    }
    elseif($controlType == 'select')
    {
        $options = $this->metric->getControlOptions($optionType);
        $control = formGroup
        (
            set::width('150px'),
            set::label(''),
            picker
            (
                set::id($name),
                set::name($name),
                set::items($options),
            ),
            setClass('form-body-item'),
        );
    }
    else
    {
        $control = formGroup
        (
            set::width('150px'),
            set::label(''),
            input
            (
                set::id($name),
                set::name($name),
                set::value($value),
            ),
            setClass('form-body-item'),
        );
    }

    return $control;
};

$formHeader = div
(
    div
    (
        setClass('form-header-item'),
        setStyle('width', '50px'),
        setStyle('text-align', 'center'),
        $lang->metric->param->varName,
    ),
    div
    (
        setClass('form-header-item'),
        setStyle('width', '150px'),
        setStyle('text-align', 'center'),
        $lang->metric->param->showName,
    ),
    div
    (
        setClass('form-header-item'),
        setStyle('width', '300px'),
        setStyle('text-align', 'center'),
        $lang->metric->param->varType,
    ),
    div
    (
        setClass('form-header-item'),
        setStyle('width', '150px'),
        $lang->metric->param->defaultValue,
        setStyle('text-align', 'center'),
    ),
    div
    (
        setClass('form-header-item'),
        setStyle('width', '150px'),
        setStyle('text-align', 'center'),
        $lang->metric->param->queryValue,
    ),
    setClass('flex form-header'),
    setStyle('justify-content', 'space-between'),
    setStyle('flex', '1'),
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
    $varType = zget($param, 'varType', '');

    $varNameLabel = div
    (
        $param['varName'],
        setStyle('width', '50px'),
        setClass('form-body-item'),
    );
    $showNameControl = formGroup
    (
        set::width('150px'),
        set::control('input'),
        set::name('showName'),
        set::value($param['showName']),
        setClass('form-body-item'),
    );
    $varTypeControl = formGroup
    (
        set::width('150px'),
        set::name('varType'),
        set::control('select'),
        set::items($typeList),
        set::value(zget($param, 'varType')),
        set::label(''),
    );
    $optionsControl = formGroup
    (
        set::width('150px'),
        set::name('options'),
        set::control('select'),
        set::items($optionList),
        set::value($param['options']),
        set::label(''),
    );
    $defaultValueControl = $buildValueControl('defaultValue', $varType, zget($param, 'defaultValue', ''), $param['options']);
    $queryValueControl  = $buildValueControl('queryValue', $varType, zget($param, 'queryValue', ''), $param['options']);
    $varNameControl = formGroup
    (
        set::className('hidden'),
        set::control('hidden'),
        set::name('varName'),
        setClass('form-body-item'),
    );

    $paramControlGroup = formRow
    (
        $varNameLabel,
        $showNameControl,
        div
        (
            $varTypeControl,
            $optionsControl,
            setClass('flex'),
            setStyle('justify-content', 'flex-start'),
            setClass('form-body-item'),
        ),
        $defaultValueControl,
        $queryValueControl,
        $varNameControl,
        setClass('flex form-body'),
        setStyle('justify-content', 'space-between'),
        setStyle('flex', '1'),
        setStyle('width', '100%'),
    );

    return $paramControlGroup;
};

$paramControlGroups = array();
foreach($params as $param) $paramControlGroups[] = $buildParamControlGroup($param, $buildValueControl, $lang->metric->param->typeList, $lang->metric->param->options);

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

detailBody
(
    sectionList
    (
        section
        (
            set::title($lang->metric->definition),
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
                setClass('flex'),
                setStyle('flex-direction', 'column'),
                setStyle('justify-content', 'space-between'),
            )
        ),
    ),
    history
    (
        set::commentUrl(createLink('action', 'comment', array('objectType' => 'metric', 'objectID' => $metric->id))),
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
            ),
        ),
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

$actionMenuList = !$metric->deleted ? $this->metric->buildOperateMenu($metric) : array();
div
(
    set::className('w-2/3 text-center fixed actions-menu'),
    set::className($metric->deleted ? 'no-divider' : ''),
    floatToolbar
    (
        isAjaxRequest('modal') ? null : to::prefix(backBtn(set::icon('back'), $lang->goback)),
        !empty($actionMenuList['main']) ? set::main($actionMenuList['main']) : null,
        !empty($actionMenuList['suffix']) ? set::suffix($actionMenuList['suffix']) : null,
        set::object($metric)
    )
);

render();
