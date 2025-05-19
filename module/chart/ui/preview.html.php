<?php
declare(strict_types = 1);
/**
 * The preview view file of chart module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chenxuan Song <songchenxuan@easycorp.ltd>
 * @package     chart
 * @link        https://www.zentao.net
 */
namespace zin;

include 'charts.html.php';

jsVar('previewUrl', inlink('preview', "dimension={$dimensionID}&group={$groupID}"));
jsVar('maxPreviewCount', $config->chart->chartMaxChecked);
jsVar('maxPreviewTips', sprintf($lang->chart->chartMaxChecked, $config->chart->chartMaxChecked));

$lang->chart->featureBar['preview'] = array();
foreach($groups as $id => $name) $lang->chart->featureBar['preview'][$id] = $name;

featureBar
(
    set::current($groupID),
    set::load(''),
    set::linkParams("dimension={$dimensionID}&group={key}"),
);

if($config->edition != 'open')
{
    toolbar
    (
        hasPriv('chart', 'export') ? item(set(array
        (
            'text'  => $lang->export,
            'icon'  => 'export',
            'class' => 'ghost',
            'url' => inlink('export', "chartID=&mode=preview", '', true),
            'data-toggle' => 'modal',
            'data-type' => 'iframe',
            'data-size' => 'sm'
        ))) : null,
        hasPriv('chart', 'browse') ? item(set(array
        (
            'text'  => $lang->chart->toDesign,
            'class' => 'primary',
            'url'   => inlink('browse'),
        ))) : null,
    );
}

$chart = zget($charts, 0, null);

sidebar
(
    set::width(240),
    moduleMenu
    (
        to::header
        (
            div
            (
                setClass('bg-canvas my-3 mx-5 text-xl font-semibold text-ellipsis'),
                $groups[$groupID]
            )
        ),
        set::title($groups[$groupID]),
        set::modules($treeMenu),
        $charts ? set::activeKey($charts[0]->currentGroup . '_' . $charts[0]->id) : null,
        set::closeLink(''),
        set::showDisplay(false),
        set::titleShow(false),
        set::checkbox(true),
        set::checkOnClick('any'),
        to::footer
        ([
            $treeMenu ? div
            (
                setClass('bg-canvas px-4 py-2 module-menu'),
                btn($lang->chart->preview, setClass('primary'), set::hint($lang->chart->updateCharts), on::click('previewCharts'))
            ) : null,
            $config->edition == 'open' ? div
            (
                setClass('bg-canvas px-4 py-2 module-menu'),
                html(empty($config->isINT) ? $lang->bizVersion : $lang->bizVersionINT)
            ) : null
        ])
    ),
);
div
(
    setID('chartPanel'),
    $generateCharts()
);
