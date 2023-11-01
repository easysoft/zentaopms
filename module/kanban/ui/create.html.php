<?php
declare(strict_types=1);
/**
 * The create view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->create), set::titleClass('article-h1'));

formPanel
(
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanspace->type),
            radioList
            (
                set::name('type'),
                set::items($typeList),
                set::inline(true),
                set::value($type)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->WIPCount),
            radioList
            (
                set::name('showWIP'),
                set::items($lang->kanban->showWIPList),
                set::inline(true),
                set::value(isset($copyKanban->showWIP) ? $copyKanban->showWIP : 1)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanban->space),
            picker
            (
                set::name('space'),
                set::items($spacePairs),
                set::value(isset($copyKanban->space) ? $copyKanban->space : $spaceID)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanban->name),
            set::name('name'),
            set::value(isset($copyKanban->name) ? $copyKanban->name : '')
        )
    ),
    $type != 'private' ? formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanban->owner),
            picker
            (
                set::name('owner'),
                set::items($ownerPairs),
                set::value(isset($copyKanban->owner) ? $copyKanban->owner : '')
            )
        )
    ) : null,
    $type != 'private' ? formRow
    (
        formGroup
        (
            set::label($lang->kanban->team),
            picker
            (
                set::name('team'),
                set::items($users),
                set::multiple(true),
                set::value(isset($copyKanban->team) ? $copyKanban->team : '')
            )
        )
    ) : null,
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->columnWidth),
            radioList
            (
                set::name('fluidBoard'),
                set::items(array('0' => $lang->kanbancolumn->fluidBoardList[0])),
                set::value(isset($copyKanban->fluidBoard) ? $copyKanban->fluidBoard : 0)
            ),
            div
            (
                set::className('flex items-center ml-8 py-1'),
                set::style(array('padding-left' => '1px')),
                span($lang->kanban->colWidth),
                input(set::type('number'), set::min('200'), set::name('colWidth'), set::className('w-16 size-sm mx-1'), set::value($kanban->colWidth ? $kanban->colWidth : 200)),
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
                set::value(isset($copyKanban->fluidBoard) ? $copyKanban->fluidBoard : 0)
            ),
            div
            (
                set::className('flex items-center ml-5 py-1'),
                span($lang->kanban->colWidth),
                input(set::type('number'), set::min('240'), set::name('minColWidth'), set::className('w-16 size-sm mx-1'), set::value($kanban->minColWidth ? $kanban->minColWidth : 240)),
                span('px'),
                span('~', set::className('mx-1')),
                input(set::type('number'), set::min('240'), set::name('maxColWidth'), set::className('w-16 size-sm mx-1'), set::value($kanban->maxColWidth ? $kanban->maxColWidth : 240)),
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
                set::value($enableImport)
            )
        )
    ),
    formRow
    (
        set::style(array('margin-top' => '0px')),
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
                set::value(isset($copyKanban->archived) ? $copyKanban->archived : '1')
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
                set::value(isset($copyKanban->performable) ? $copyKanban->performable : '1')
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
                set::value(isset($copyKanban->alignment) ? $copyKanban->alignment : 'center')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->desc),
            editor
            (
                set::name('desc'),
                html(isset($copyKanban->desc) ? $copyKanban->desc : '')
            )
        )
    ),
    $type == 'private' ? formRow
    (
        formGroup
        (
            set::label($lang->whitelist),
            picker
            (
                set::name('whitelist'),
                set::items($users),
                set::multiple(true),
                set::value(isset($copyKanban->whitelist) ? $copyKanban->whitelist : '')
            )
        ),
        input(set::className('hidden'), set::name('copyKanbanID'), set::value($copyKanbanID)),
        input(set::className('hidden'), set::name('copyRegion'), set::value($copyRegion))
    ) : null
);

render();
