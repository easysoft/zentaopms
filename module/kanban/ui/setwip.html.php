<?php
declare(strict_types=1);
/**
 * The setwip view file of kanban module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yuting Wang <wangyuting@easycorp.ltd>
 * @package     kanban
 * @link        https://www.zentao.net
 */
namespace zin;
modalHeader(set::title($lang->kanban->setWIP), set::entityText($column->name), set::entityID($column->id));

$stage = \zget($config->kanban->storyColumnStageList, $column->type);
formPanel
(
    on::change('[name=noLimit]', 'changeColumnLimit'),
    $column->parent != -1 && $from !='kanban' ? formRow
    (
        formGroup
        (
            set::label($lang->kanban->WIPStatus),
            input(set::name('WIPStatus'), set::value(\zget($lang->kanban->{$column->laneType . 'Column'}, $column->type, '')), set::disabled(true))
        )
    ) : null,
    $column->parent != -1 && $from !='kanban' && $column->laneType == 'story' ? formRow
    (
        formGroup
        (
            set::label($lang->kanban->WIPStage),
            input(set::name('WIPStage'), set::value(zget($lang->story->stageList, $stage, '')), set::disabled(true))
        )
    ) : null,
    formRow
    (
        formGroup
        (
            set::label($lang->kanban->WIPCount),
            inputGroup
            (
                input(set::name('limit'), set::disabled($column->limit == -1), set::value($column->limit != -1 ? $column->limit : '')),
                span
                (
                    set('class', 'input-group-addon'),
                    checkList
                    (
                        set::name('noLimit'),
                        set::items(array('-1' => $this->lang->kanban->noLimit)),
                        set::value($column->limit == '-1' ? '-1' : '0')
                    )
                )
            )
        )
    )
);

render();
