<?php
declare(strict_types=1);
/**
* The waterfall estimate block view file of block module of ZenTaoPMS.
* @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
* @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
* @author      Yuting Wang <wangyuting@easycorp.ltd>
* @package     block
* @link        https://www.zentao.net
*/

namespace zin;

blockPanel
(
    div
    (
        setClass('flex py-4'),
        cell
        (
            set::width('1/2'),
            setClass('border-r'),
            div($lang->block->estimate->costs),
            div
            (
                setClass('flex py-4 text-gray'),
                cell(set::width('1/2'), setClass('text-right'), $lang->durationestimation->people . ' :'),
                cell(setClass('ml-1'), ($people ? $people : 0) . ' ' . $lang->block->estimate->people)
            ),
            div
            (
                setClass('flex py-4 text-gray'),
                cell(set::width('1/2'), setClass('text-right'), $lang->durationestimation->members . ' :'),
                cell(setClass('ml-1'), ($members ? $members : 0) . ' ' . $lang->block->estimate->people)
            ),
            div
            (
                setClass('flex py-4 text-gray'),
                cell(set::width('1/2'), setClass('text-right'), $lang->workestimation->totalLaborCost . ' :'),
                cell(setClass('ml-1'), '￥' . zget($budget, 'totalLaborCost', 0))
            )
        ),
        cell
        (
            set::width('1/2'),
            setClass('px-4'),
            div($lang->block->estimate->workhour),
            div
            (
                setClass('flex py-4 text-gray'),
                cell(set::width('1/2'), setClass('text-right'), $lang->block->estimate->expect . ' :'),
                cell(setClass('ml-1'), zget($budget, 'duration', 0) . ' ' . $lang->block->estimate->hour)
            ),
            div
            (
                setClass('flex py-4 text-gray'),
                cell(set::width('1/2'), setClass('text-right'), $lang->block->estimate->consumed . ' :'),
                cell(setClass('ml-1'), ($consumed ? $consumed : 0) . ' ' . $lang->block->estimate->hour)
            ),
            div
            (
                setClass('flex py-4 text-gray'),
                cell(set::width('1/2'), setClass('text-right'), $lang->block->estimate->surplus . ' :'),
                cell(setClass('ml-1'), ($totalLeft ? $totalLeft : 0)  . ' ' . $lang->block->estimate->hour)
            )
        )
    )
);
