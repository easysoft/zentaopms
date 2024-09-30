<?php
declare(strict_types=1);
/**
 * The moveCard view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2024 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Mengyi Liu
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader
(
    set::title($lang->kanban->moveCard),
    set::entityText($card->name),
    set::entityID($card->id)
);

formPanel
(
    setID('moveCardForm'),
    formRow
    (
        formGroup
        (
            on::change('changeRegion'),
            set::label($lang->kanbanregion->name),
            set::name('region'),
            set::control(array('control' => 'picker', 'required' => false)),
            set::required(true),
            set::items($regions)
        )
    ),
    formRow
    (
        formGroup
        (
            on::change('changeLane'),
            set::label($lang->kanbanlane->name),
            set::name('lane'),
            set::control(array('control' => 'picker', 'required' => false)),
            set::items(array()),
            set::required(true),
            set::disabled(true)
        )
    ),
    formRow
    (
    )
);

render();
