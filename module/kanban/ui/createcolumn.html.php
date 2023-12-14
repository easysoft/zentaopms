<?php
declare(strict_types=1);
/**
 * The createcolumn view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->createColumn), set::titleClass('text-lg font-bold'));

formPanel
(
    on::change('[name=noLimit]', 'changeColumnLimit'),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbancolumn->name),
            inputControl
            (
                input(set::name('name')),
                set::suffixWidth('icon'),
                to::suffix
                (
                    colorPicker
                    (
                        set::name('color'),
                        set::items($config->kanban->columnColorList),
                        set('data-on', 'change'),
                        set('data-call', "$('[name=name]').css('color', $('[name=color]').val())")
                    )
                )
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->WIPCount),
            inputGroup
            (
                input(set::name('limit'), set::disabled(true)),
                span
                (
                    set('class', 'input-group-addon'),
                    checkList
                    (
                        set::name('noLimit'),
                        set::items(array('-1' => $this->lang->kanban->noLimit)),
                        set::value('-1')
                    )
                ),
                input(set::className('hidden'), set::name('group'), set::value($fromColumn->group)),
                input(set::className('hidden'), set::name('parent'), set::value($fromColumn->parent > 0 ? $fromColumn->parent : 0))
            )
        )
    )
);

render();
