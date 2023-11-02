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

include 'echarts.html.php';

jsVar('previewUrl', inlink('preview', "dimension={$dimensionID}&group={$group->id}"));

featureBar
(
    set::current($group->id),
    set::linkParams("dimension={$dimensionID}&group={key}")
);

div
(
    setClass('flex gap-4'),
    sidebar
    (
        set::width('60'),
        moduleMenu
        (
            set::title($group->name),
            set::modules($chartTree),
            set::closeLink(''),
            set::showDisplay(false),
            set::checkbox(true),
            set::checkOnClick('any')
        ),
        div
        (
            setClass('flex bg-canvas gap-4 px-4 pb-4'),
            btn($lang->selectAll, on::click("$('#moduleMenu ul').zui('tree').$.toggleAllChecked()")),
            btn($lang->chart->preview, setClass('primary'), on::click('previewCharts'))
        ),
        $config->edition == 'open' ? div
        (
            setClass('bg-canvas px-4 pb-4'),
            html(empty($config->isINT) ? $lang->bizVersion : $lang->bizVersionINT)
        ) : null
    ),
    div
    (
        setClass('w-full'),
        $chartItems()
    )
);

render();
