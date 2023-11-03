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
modalHeader(set::title($lang->kanban->activateCard), set::entityText($card->name), set::entityID($card->id));

formPanel
(
    formRow
    (
        formGroup
        (
            set::label($lang->kanbancard->progress),
            inputGroup
            (
                input(set::name('progress')),
                span(set('class', 'input-group-addon'), '%')
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->comment),
            editor(set::name('comment'))
        )
    )
);

render();
