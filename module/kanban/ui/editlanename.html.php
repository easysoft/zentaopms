<?php
declare(strict_types=1);
/**
 * The editlanename view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->editLaneName), set::entityText($lane->name), set::entityID($lane->id));

formPanel
(
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanlane->name),
            set::name('name'),
            set::required(true),
            set::value($lane->name)
        )
    ),
    $from != 'kanban' ? formRow
    (
        formGroup
        (
            set::label($lang->kanban->WIPType),
            set::disabled(true),
            set::name('type'),
            set::value(\zget($lang->kanban->laneTypeList, $lane->type))
        )
    ) : null
);

render();
