<?php
declare(strict_types=1);
/**
 * The createcard view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanbancard->create), set::titleClass('text-lg font-bold'));

formPanel
(
    formRow
    (
        formGroup
        (
            set::label($lang->kanbancard->name),
            set::name('name')
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
                input(set::name('estimate'), set::placeholder($lang->kanbancard->lblHour))
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanlane->common),
            picker
            (
                set::name('lane'),
                set::items($lanePairs),
                set::value(key($lanePairs))
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
                set::items($users),
                set::value($app->user->account),
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
                datePicker(set::name('begin'), set::placeholder($lang->kanbancard->begin)),
                span(set::className('input-group-addon'), '~'),
                datePicker(set::name('end'), set::placeholder($lang->kanbancard->end))
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbancard->desc),
            editor(set::name('desc'))
        )
    )
);

render();
