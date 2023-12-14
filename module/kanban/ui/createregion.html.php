<?php
declare(strict_types=1);
/**
 * The createregion view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->createRegion), set::titleClass('text-lg font-bold'));

formPanel
(
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanregion->name),
            set::name('name')
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanregion->style),
            picker
            (
                set::name('region'),
                set::items($regions),
                set::value('custom')
            )
        )
    )
);

render();
