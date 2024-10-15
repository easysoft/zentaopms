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

$beforeSubmit = jsRaw("() =>
{
    zui.Modal.confirm('{$confirmTip}').then((res) =>
    {
        if(res)
        {
            const formData = new FormData($('#zin_project_close_{$project->id}_form')[0]);
            const url      = $('#zin_project_close_{$project->id}_form').attr('action');
            $.ajaxSubmit({url: url, data: formData});
        }
    });
    return false;
}");

modalHeader();
formPanel
(
    set::formID("zin_project_close_{$project->id}_form"),
    !empty($confirmTip) ? set::ajax(array('beforeSubmit' => $beforeSubmit)) : null,
    formGroup
    (
        set::width('1/2'),
        set::label($lang->project->realEnd),
        set::name('realEnd'),
        set::control('date'),
        set::value(!helper::isZeroDate($project->realEnd) ? $project->realEnd : helper::today())
    ),
    formGroup
    (
        set::label($lang->comment),
        editor
        (
            set::name('comment'),
            set::rows('6')
        )
    )
);
hr();
history();

/* ====== Render page ====== */
render();
