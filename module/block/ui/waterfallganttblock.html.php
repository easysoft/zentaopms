<?php
declare(strict_types=1);
/**
* The waterfallgantt block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;
$waterfallGanttID = uniqid('wg');
jsVar('waterfallGanttID', $waterfallGanttID);
jsVar('ganttPlans', array_values($plans));
jsVar('progressLang', $lang->programplan->progress);

$productItems = array();
foreach($products as $id => $productName)
{
    $url = createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("module={$block->module}&productID={$id}"));
    $productItems[] = array('text' => $productName, 'data-url' => $url, 'active' => $productID == $id, 'data-on' => 'click', 'data-do' => "loadBlock('$block->id', options.url)");
}
panel
(
    to::titleSuffix
    (
        icon
        (
            setClass('text-light text-sm cursor-pointer'),
            toggle::tooltip
            (
                array
                (
                    'title'     => sprintf($lang->block->tooltips['metricTime'], $metricTime),
                    'placement' => 'bottom',
                    'type'      => 'white',
                    'className' => 'text-dark border border-light leading-5'
                )
            ),
            'help'
        ),
        $productItems ?  dropdown
        (
            btn
            (
                setClass('ghost text-gray font-normal'),
                set::caret(true),
                $products[$productID]
            ),
            set::items($productItems)
        ) : null,
        $productItems ? span(setClass('text-gray-400 font-normal'), "* {$lang->block->selectProduct}") : null
    ),
    setID($waterfallGanttID),
    set('headingClass', 'border-b'),
    set::title($block->title),
    div
    (
        set::className('waterfall-gantt'),
        empty($plans) ? div(setClass('h-32 center text-gray'), $lang->error->noData) : div
        (
            setClass('gantt clearfix'),
            div(setClass('gantt-plans pull-left'), setID('ganttPlans')),
            div
            (
                setClass('gantt-container scrollbar-hover'),
                setID('ganttContainer'),
                div
                (
                    setClass('gantt-canvas'),
                    div
                    (
                        setClass('gantt-today'),
                        div($lang->programplan->today)
                    )
                )
            )
        )
    )
);
