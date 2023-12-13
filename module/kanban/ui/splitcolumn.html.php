<?php
declare(strict_types=1);
/**
 * The splitcolumn view file of kanban module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->splitColumn), set::entityText($column->name), set::entityID($column->id));

jsVar('+index', 2);

$rows = array();
for($index = 0; $index <= 1; $index ++)
{
    $rows[] = formRow
    (
        set('data-row', $index),
        formGroup
        (
            set::label($lang->kanbancolumn->childName),
            inputGroup
            (
                inputControl
                (
                    input(set::name("name[$index]")),
                    set::suffixWidth('icon'),
                    to::suffix
                    (
                        colorPicker
                        (
                            set::name("color[$index]"),
                            set::items($config->kanban->laneColorList),
                            set::value('#333'),
                            set('data-on', 'change'),
                            set('data-call', "$('[name=\"name\[$index\]\"]').css('color', $('[name=\"color\[$index\]\"]').val())")
                        )
                    )
                ),
                span
                (
                    set('class', 'input-group-addon'),
                    $lang->kanban->WIPCount
                ),
                input(set::name("limit[$index]"), set::disabled(true), set::style(array('width' => '80px'))),
                span
                (
                    set('class', 'input-group-addon'),
                    checkList
                    (
                        set::name("noLimit[$index]"),
                        set::items(array('-1' => $this->lang->kanban->noLimit)),
                        set::value('-1')
                    )
                ),
                btn(set::className('ghost ml-2 addRows'), icon('plus')),
                btn(set::className('ghost removeRows'), icon('trash'))
            )
        )
    );
}

formPanel
(
    on::click('.addRows',  'clickAddRows'),
    on::click('.removeRows', 'clickRemoveRows'),
    on::change('[name^=noLimit]', 'changeColumnLimit'),
    $rows
);

render();
