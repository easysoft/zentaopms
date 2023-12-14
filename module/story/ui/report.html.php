<?php
declare(strict_types=1);
/**
 * The view file of product module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      chen.tao <chentao@easycorp.ltd>
 * @package     product
 * @link        https://www.zentao.net
 */

namespace zin;

jsVar('storyType', $storyType);

/* Generate optional fields of report. */
$fnGenerateFormFields = function() use($lang, $checkedCharts)
{
    $fields = array();
    foreach($lang->story->report->charts as $val => $name)
    {
        $fields[] =li(checkbox
        (
            set::value($val),
            set::text($name),
            set::name('charts[]'),
            set::checked(str_contains($checkedCharts, $val))
        ));
    }

    return ul($fields);
};

$fnGenerateTabCharts = function(string $type) use($charts, $lang, $datas)
{
    $tabCharts = array();
    foreach(array_keys($charts) as $chartType)
    {
        $tabCharts[] = tableChart
        (
            set::type($type),
            set::title($lang->story->report->charts[$chartType]),
            set::datas($datas[$chartType] ?? null),
            set::tableHeaders(array
            (
                'item'    => $lang->story->report->$chartType->item,
                'value'   => $lang->story->report->value,
                'percent' => $lang->report->percent
            ))
        );
    }

    return $tabCharts;
};

$fnGenerateTabs = function() use($fnGenerateTabCharts, $lang, $chartType)
{
    unset($lang->report->typeList['default']);
    $notice = str_replace('%tab%', $lang->product->unclosed . $lang->story->common, $lang->report->notice->help);

    $tabs = array();
    foreach($lang->report->typeList as $type => $typeName)
    {
        $tabs[] = tabPane
        (
            set::key($type),
            set::title($typeName),
            set::active($type == $chartType),
            to::prefix(icon($type == 'default' ? 'list-alt' : "chart-{$type}")),
            div(html($notice)),
            $fnGenerateTabCharts($type)
        );
    }

    return $tabs;
};

/* Layout. */
featureBar
(
    to::before(
        backBtn
        (
            set::icon('back'),
            set::type('secondary'),
            $lang->goback
        ),
        div(setClass('nav-divider'))
    ),
    div
    (
        setClass('entity-label flex items-center gap-x-2 text-lg font-bold'),
        $lang->story->report->common
    )
);

sidebar
(
    set::showToggle(false),
    setStyle(array('width' => '240px')),
    formPanel
    (
        set::title($lang->story->report->select),
        setClass('shadow'),
        set::actions(array
        (
            array('text'=> $lang->selectAll,             'onclick' => 'window.reportSelectAllFields(this)', 'type' => 'button', 'class' => ''),
            array('text'=> $lang->story->report->create, 'onclick' => 'window.reportSubmit(this)',          'type' => 'button', 'class' => 'primary')
        )),
        $fnGenerateFormFields()
    )
);

panel(
    setID('mainPanel'),
    tabs
    (
        set::collapse(false),
        $fnGenerateTabs()
    )
);
