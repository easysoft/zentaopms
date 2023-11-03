<?php
declare(strict_types=1);
/**
 * The setcolumn view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->setColumn), set::entityText($column->name), set::entityID($column->id));

formPanel
(
    on::change('[name=noLimit]', 'changeColumnLimit'),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbancolumn->name),
            input(set::name('name'), set::value($column->name))
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbancolumn->color),
            colorPicker
            (
                set::name('color'),
                set::items($config->kanban->columnColorList),
                set::value($column->color)
            ),
        )
    )
);

render();
