<?php
declare(strict_types=1);
/**
 * The createlane view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->createLane), set::titleClass('text-lg font-bold'));

formPanel
(
    on::change('[name=mode]', 'changeMode'),
    set::labelWidth('140px'),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanlane->name),
            set::name('name')
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanlane->column),
            radioList
            (
                set::name('mode'),
                set::items($lang->kanbanlane->modeList),
                set::inline(true),
                set::value('sameAsOther')
            )
        )
    ),
    formRow
    (
        setID('otherLaneBox'),
        formGroup
        (
            set::label($lang->kanbanlane->otherlane),
            picker
            (
                set::name('otherLane'),
                set::items($lanes)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->kanbanlane->color),
            colorPicker
            (
                set::name('color'),
                set::items($config->kanban->laneColorList),
                set::value('#3C4353')
            )
        )
    )
);

render();
