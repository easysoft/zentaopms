<?php
declare(strict_types=1);
/**
 * The editroject view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;

$publicTip = "<span id='publicTip' class='text-danger'>" . $lang->gitlab->project->publicTip . '</span>';
jsVar('publicTip', $publicTip);
jsVar('visibility', $project->visibility);


formPanel
(
    set::title($lang->gitlab->project->edit),
    formGroup
    (
        set::name('id'),
        set::label($lang->gitlab->project->id),
        set::readonly(true),
        set::value($project->id),
        set::width('2/3')
    ),
    formGroup
    (
        set::name('name'),
        set::label($lang->gitlab->project->name),
        set::placeholder($lang->gitlab->project->name),
        set::required(true),
        set::value($project->name),
        set::width('2/3'),
    ),
    formGroup
    (
        set::label($lang->gitlab->project->description),
        set::name('description'),
        set::control('textarea'),
        set::value($project->description),
        set::width('2/3')
    ),
    formGroup
    (
        set::label($lang->gitlab->project->visibility),
        set::control('radioList'),
        set::items($lang->gitlab->project->visibilityList),
        set::value($project->visibility),
        set::name('visibility'),
        on::change('onAclChange')
    )
);

render();
