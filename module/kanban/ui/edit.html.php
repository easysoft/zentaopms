<?php
declare(strict_types=1);
/**
 * The edit view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
jsVar('spaceType', $type);

modalHeader();

formPanel
(
    on::change('[name=type]', 'changeKanbanType'),
    on::change('[name=space]', 'changeKanbanSpace'),
    on::click('#allUsers', 'loadAllUsers'),
    formRow
    (
        formGroup
        (
            set::width('1/2'),
            set::label($lang->kanban->space),
            set::required(true),
            picker
            (
                set::name('space'),
                set::items($spacePairs),
                set::value($kanban->space)
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
            set::value($kanban->name)
        )
    ),
    formRow
    (
        set::className('params ' . ($type == 'private' ? 'hidden' : '')),
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
                    set::value($kanban->owner)
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
        set::className('params ' . ($type == 'private' ? 'hidden' : '')),
        formGroup
        (
            set::label($lang->kanban->team),
            picker
            (
                set::name('team'),
                set::items($users),
                set::multiple(true),
                set::value($kanban->team)
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
                html($kanban->desc)
            )
        )
    ),
    formRow
    (
        set::className('whitelistBox ' . ($type != 'private' ? 'hidden' : '')),
        formGroup
        (
            set::label($lang->whitelist),
            whitelist(set::items($users), set::value($kanban->whitelist))
        )
    )
);

render();
