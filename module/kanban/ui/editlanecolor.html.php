<?php
declare(strict_types=1);
/**
 * The editlanecolor view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->editLaneColor), set::entityText($lane->name), set::entityID($lane->id));
setClass("size-sm");
formPanel
(
    formRow
    (
        set::width('150px'),
        formGroup
        (
            set::label($lang->kanbanlane->color),
            colorPicker
            (
                set::name('color'),
                set::items($config->kanban->laneColorList),
                set::value($lane->color)
            )
        )
    )
);

render();
