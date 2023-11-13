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
jsVar('spaceID', $spaceID);
jsVar('spaceType', $type);

modalHeader(set::title($lang->kanban->create), set::titleClass('article-h1'));

formPanel
(
    on::change('[name=type]', 'changeKanbanType'),
    on::change('[name=space]', 'changeKanbanSpace'),
    on::click('#allUsers', 'loadAllUsers'),
    set::headingClass('headingActions'),
    to::headingActions
    (
        btn
        (
            setClass('primary-pale'),
            set::icon('copy'),
            set::url('#copyKanbanModal'),
            set('data-destoryOnHide', true),
            set('data-toggle', 'modal'),
            $lang->kanban->copy . $lang->kanban->common
        )
    ),
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
        set::id('WIPCountBox'),
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
        set::id('spaceBox'),
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
        set::id('nameBox'),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanban->name),
            set::name('name'),
            set::value(isset($copyKanban->name) ? $copyKanban->name : '')
        )
    ),
    formRow
    (
        set::id('ownerBox'),
        set::className($type == 'private' ? 'hidden' : ''),
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanban->owner),
            inputGroup
            (
                picker
                (
                    set::name('owner'),
                    set::items($ownerPairs),
                    set::value(isset($copyKanban->owner) ? $copyKanban->owner : '')
                ),
                span
                (
                    set('class', 'input-group-addon'),
                    a
                    (
                        set('id', 'allUsers'),
                        set('href', 'javascript:;'),
                        $lang->kanban->allUsers
                    )
                )
            )
        )
    ),
    formRow
    (
        set::id('teamBox'),
        set::className($type == 'private' ? 'hidden' : ''),
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
    ),
    formRow
    (
        set::id('fixedColBox'),
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
                input(set::type('number'), set::min($config->colWidth), set::name('colWidth'), set::className('w-16 size-sm mx-1'), set::value(isset($copyKanban->colWidth) ? $copyKanban->colWidth : $config->colWidth)),
                span('px')
            )
        )

    ),
    formRow
    (
        set::id('autoColBox'),
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
                input(set::type('number'), set::min($config->minColWidth), set::name('minColWidth'), set::className('w-16 size-sm mx-1'), set::value(isset($copyKanban->minColWidth) ? $copyKanban->minColWidth : $config->minColWidth)),
                span('px'),
                span('~', set::className('mx-1')),
                input(set::type('number'), set::min($config->maxColWidth), set::name('maxColWidth'), set::className('w-16 size-sm mx-1'), set::value(isset($copyKanban->maxColWidth) ? $copyKanban->maxColWidth : $config->maxColWidth)),
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
        set::id('archiveBox'),
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
        set::id('manageProgressBox'),
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
        set::id('alignmentBox'),
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
        set::id('descBox'),
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
    formRow
    (
        set::id('whitelistBox'),
        set::className($type != 'private' ? 'hidden' : ''),
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
    )
);

$kanbanList = array();
if($copyKanbanID != 0)
{
    $kanbanList[] = cell
    (
        set::width('1/3'),
        set::className('p-2'),
        div
        (
            set::className('copy-card p-2 border rounded-md text-danger'),
            icon('cancel', set::className('pr-2')),
            $lang->kanban->cancelCopy
        )
    );
}
foreach ($kanbans as $id => $name)
{
    $kanbanList[] = cell
    (
        set::width('1/3'),
        set::className('p-2'),
        div
        (
            set('data-on', 'click'),
            set('data-call', 'clickCopyCard'),
            set('data-params', 'event'),
            set('data-id', $id),
            set::className('copy-card p-2 border rounded-md'),
            icon('kanban', set::className('pr-2')),
            $name
        )
    );
}

modalTrigger
(
    modal
    (
        set::id('copyKanbanModal'),
        to::header
        (
            span
            (
                set::className('copy-title article-h1'),
                $lang->kanban->copyTitle,
            ),
            span
            (
                $lang->kanban->copyContent,
            ),
            checkList
            (
                set::name('copyContent[]'),
                set::items($lang->kanban->copyContentList),
                set::inline(true),
                set::value('basicInfo')
            )
        ),
        div
        (
            set::id('copyKanbans'),
            setClass('flex flex-wrap'),
            $kanbanList
        )
    )
);

render();
