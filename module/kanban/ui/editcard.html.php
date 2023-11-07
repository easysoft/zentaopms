<?php
declare(strict_types=1);
/**
 * The editcard view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanbancard->edit), set::entityText($card->name), set::entityID($card->id));

formPanel
(
    formRow
    (
        formGroup
        (
            set::label($lang->kanbancard->name),
            input(set::name('name'), set::value($card->name))
        ),
        formGroup
        (
            set::width('120px'),
            inputGroup
            (
                span
                (
                    set('class', 'input-group-addon'),
                    $lang->kanbancard->pri
                ),
                priPicker
                (
                    set::name('pri'),
                    set::items($lang->kanbancard->priList),
                    set::value('3')
                )
            )
        ),
        formGroup
        (
            set::width('120px'),
            inputGroup
            (
                span
                (
                    set('class', 'input-group-addon'),
                    $lang->kanbancard->estimate
                ),
                input(set::name('estimate'), set::placeholder($lang->kanbancard->lblHour), set::value($card->estimate))
            )
        ),
        formGroup
        (
            set::width('120px'),
            inputGroup
            (
                span
                (
                    set('class', 'input-group-addon'),
                    $lang->kanbancard->progress
                ),
                input(set::name('progress'), set::value($card->progress)),
                span(set('class', 'input-group-addon'), '%')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbancard->assignedTo),
            picker
            (
                set::name('assignedTo'),
                set::items($kanbanUsers),
                set::value($card->assignedTo),
                set::multiple(true)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbancard->beginAndEnd),
            inputGroup
            (
                datePicker(set::name('begin'), set::placeholder($lang->kanbancard->begin), set::value($card->begin)),
                span(set::className('input-group-addon'), '~'),
                datePicker(set::name('end'), set::placeholder($lang->kanbancard->end), set::value($card->end)),
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbancard->desc),
            editor(set::name('desc'), html($card->desc))
        )
    )
);

render();
