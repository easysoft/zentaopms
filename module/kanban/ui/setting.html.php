<?php
declare(strict_types=1);
/**
 * The setting view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;

modalHeader();
formPanel
(
    set::labelWidth('100px'),
    formRow
    (
        set::id('WIPCountBox'),
        formGroup
        (
            set::label($lang->kanban->WIPCount),
            radioList
            (
                set::name('showWIP'),
                set::items($lang->kanban->showWIPList),
                set::inline(true),
                set::value($kanban->showWIP)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->columnWidth),
            radioList
            (
                set::name('fluidBoard'),
                set::items(array('0' => $lang->kanbancolumn->fluidBoardList[0])),
                set::value($kanban->fluidBoard)
            ),
            div
            (
                set::className('flex items-center ml-8 py-1'),
                set::style(array('padding-left' => '1px')),
                span($lang->kanban->colWidth),
                input(set::type('number'), set::min($config->colWidth), set::name('colWidth'), set::className('w-16 size-sm mx-1'), set::value($kanban->colWidth ? $kanban->colWidth : $config->colWidth)),
                span('px')
            )
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
                set::value($kanban->fluidBoard)
            ),
            div
            (
                set::className('flex items-center ml-5 py-1'),
                span($lang->kanban->colWidth),
                input(set::type('number'), set::min($config->minColWidth), set::name('minColWidth'), set::className('w-16 size-sm mx-1'), set::value($kanban->minColWidth ? $kanban->minColWidth : $config->minColWidth)),
                span('px'),
                span('~', set::className('mx-1')),
                input(set::type('number'), set::min($config->maxColWidth), set::name('maxColWidth'), set::className('w-16 size-sm mx-1'), set::value($kanban->maxColWidth ? $kanban->maxColWidth : $config->maxColWidth)),
                span('px')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->import),
            radioList
            (
                set::name('import'),
                set::items($lang->kanban->importList),
                set::inline(true),
                set::value($enableImport),
                on::change('toggleImportObjectBox')
            )
        )
    ),
    formRow
    (
        set::style(array('margin-top' => '0px')),
        setID('objectBox'),
        formGroup
        (
            set::label(''),
            checkList
            (
                set::name('importObjectList[]'),
                set::items($lang->kanban->importObjectList),
                set::inline(true),
                set::value($importObjects)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->archive),
            radioList
            (
                set::name('archived'),
                set::items($lang->kanban->archiveList),
                set::inline(true),
                set::value($kanban->archived)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->manageProgress),
            radioList
            (
                set::name('performable'),
                set::items($lang->kanban->enableList),
                set::inline(true),
                set::value($kanban->performable)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->alignment),
            radioList
            (
                set::name('alignment'),
                set::items($lang->kanban->alignmentList),
                set::inline(true),
                set::value($kanban->alignment)
            )
        )
    )
);

render();
