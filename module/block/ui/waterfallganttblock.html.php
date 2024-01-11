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
jsVar('ganttPlans', $plans);
jsVar('taskLang', $lang->programplan->task);

$productItems = array();
foreach($products as $id => $productName)
{
    $url = createLink('block', 'printBlock', "blockID={$block->id}&params=" . helper::safe64Encode("module={$block->module}&productID={$id}"));
    $productItems[] = array('text' => $productName, 'data-url' => $url, 'data-on' => 'click', 'data-do' => "loadBlock('$block->id', options.url)");
}
panel
(
    to::titleSuffix
    (
        dropdown
        (
            btn
            (
                setClass('ghost text-gray font-normal'),
                set::caret(true),
                $products[$productID]
            ),
            set::items($productItems)
        )
    ),
    setID($waterfallGanttID),
    set('headingClass', 'border-b'),
    set::title($block->title),
    div
    (
        set::class('waterfall-gantt'),
        empty($plans['data']) ? div(setClass('gantt-product-tips'), $lang->block->selectProduct) : div
        (
            setClass('gantt clearfix'),
            div(setClass('gantt-plans pull-left')),
            div
            (
                setClass('gantt-container scrollbar-hover'),
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
