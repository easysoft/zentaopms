<?php
declare(strict_types=1);
/**
 * The setKanban view file of execution module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Sun Guangming<sunguangming@easycorp.ltd>
 * @package     execution
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader(set::title($lang->execution->setKanban));
formPanel
(
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->columnWidth),
            radioList
            (
                set::name('fluidBoard'),
                set::items(array('0' => $lang->kanbancolumn->fluidBoardList[0])),
                set::value($execution->fluidBoard),
                on::change('changeColWidth')
            ),
            div
            (
                set::className('flex items-center ml-8 py-1'),
                set::style(array('padding-left' => '1px')),
                span($lang->kanban->colWidth),
                input(set::type('number'), set::min($config->colWidth), set::name('colWidth'), set::className('w-16 size-sm mx-1'), set::value($execution->colWidth ? $execution->colWidth : $config->colWidth), $execution->fluidBoard == 0 ? '' : set::disabled(true)),
                span('px')
            ),
            div
            (
                set::className('flex items-center ml-4 py-2 tip'),
                $lang->kanbancolumn->autoTip
            ),
        )
    ),
    formRow
    (
        set::style(array('margin-top' => '0px')),
        formGroup
        (
            set::label(''),
            radioList
            (
                set::name('fluidBoard'),
                set::items(array(1 => $lang->kanbancolumn->fluidBoardList[1])),
                set::value($execution->fluidBoard),
                on::change('changeColWidth')
            ),
            div
            (
                set::className('flex items-center ml-5 py-1'),
                span($lang->kanban->colWidth),
                input(set::type('number'), set::min($config->minColWidth), set::name('minColWidth'), set::className('w-16 size-sm mx-1'), set::value($execution->minColWidth ? $execution->minColWidth : $config->minColWidth), $execution->fluidBoard == 1 ? '' : set::disabled(true)),
                span('px'),
                span('~', set::className('mx-1')),
                input(set::type('number'), set::min($config->maxColWidth), set::name('maxColWidth'), set::className('w-16 size-sm mx-1'), set::value($execution->maxColWidth ? $execution->maxColWidth : $config->maxColWidth), $execution->fluidBoard == 1 ? '' : set::disabled(true)),
                span('px')
            )
        )
    )
);
