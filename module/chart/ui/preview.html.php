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

$items = array();
foreach($groups as $id => $name)
{
    $items[] = array('text' => $name, 'url' => inlink('preview', "dimension={$dimensionID}&group={$id}"), 'active' => $id == $groupID);
}

featureBar(set::items($items));

$chart = zget($charts, 0, null);

div
(
    setClass('flex gap-4'),
    sidebar
    (
        set::width(240),
        moduleMenu
        (
            set::title($groups[$groupID]),
            set::modules($treeMenu),
            $charts ? set::activeKey($charts[0]->currentGroup . '_' . $charts[0]->id) : null,
            set::closeLink(''),
            set::showDisplay(false),
            set::checkbox(true),
            set::checkOnClick('any')
        ),
        $treeMenu ? div
        (
            setClass('flex bg-canvas gap-4 px-4 pb-4'),
            btn($lang->chart->preview, setClass('primary'), on::click('previewCharts'))
        ) : null,
        $config->edition == 'open' ? div
        (
            setClass('bg-canvas px-4 pb-4'),
            html(empty($config->isINT) ? $lang->bizVersion : $lang->bizVersionINT)
        ) : null
    ),
    div
    (
        setID('chartPanel'),
        setClass('w-full'),
        $generateCharts()
    )
);

render();
