<?php
declare(strict_types=1);
/**
 * The close view file of project module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Shujie Tian<tianshujie@easycorp.ltd>
 * @package     project
 * @link        https://www.zentao.net
 */
namespace zin;

formPanel
(
    set::title($lang->project->close),
    set::headingClass('status-heading'),
    set::titleClass('form-label .form-grid'),
    set::shadow(false),
    set::actions(array('submit')),
    set::submitBtnText($lang->project->close),
    to::headingActions
    (
        entityLabel
        (
            setClass('my-3 gap-x-3'),
            set::level(1),
            set::text($project->name),
            set::entityID($project->id),
            set::reverse(true),
        )
    ),
    formGroup
    (
        set::width('1/2'),
        set::label($lang->project->realEnd),
        set::name('realEnd'),
        set::control('date'),
        set::value(!helper::isZeroDate($project->realEnd) ? $project->realEnd : helper::today()),
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('6'),
        )
    ),
);

h::hr(set::class('mt-6'));

history();
/* ====== Render page ====== */
render();
