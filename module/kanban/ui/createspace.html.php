<?php
declare(strict_types=1);
/**
 * The createspace view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->createSpace), set::titleClass('article-h1'));

unset($this->lang->kanban->featureBar['space']['involved']);
formPanel
(
    on::change('[name="type"]', 'changeSpaceType'),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanspace->type),
            radioList
            (
                set::name('type'),
                set::items($lang->kanban->featureBar['space']),
                set::inline(true),
                set::value($type)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanspace->name),
            set::name('name')
        )
    ),
    formRow
    (
        set::className('ownerBox ' . ($type == 'private' ? 'hidden' : '')),
        formGroup
        (
            set::label($lang->kanbanspace->owner),
            set::required(true),
            picker
            (
                set::name('owner'),
                set::items($users)
            )
        )
    ),
    formRow
    (
        set::className('teamBox ' . ($type == 'private' ? 'hidden' : '')),
        formGroup
        (
            set::label($lang->kanbanspace->team),
            picker
            (
                set::name('team'),
                set::multiple(true),
                set::items($users)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanspace->desc),
            editor(set::name('desc'))
        )
    ),
    formRow
    (
        set::className('whitelistBox ' . ($type != 'private' ? 'hidden' : '')),
        formGroup
        (
            set::label($lang->whitelist),
            picker
            (
                set::name('whitelist'),
                set::multiple(true),
                set::items($users)
            )
        )
    )
);

render();
