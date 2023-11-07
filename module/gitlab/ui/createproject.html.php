<?php
declare(strict_types=1);
/**
 * The createproject view file of gitlab module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     gitlab
 * @link        https://www.zentao.net
 */
namespace zin;

$publicTip = "<span id='publicTip' class='text-danger'>" . $lang->gitlab->project->publicTip . '</span>';
jsVar('publicTip', $publicTip);

$urlOptions = array();
if(count($namespaces) < 2)
{
    $urlOptions[] = input
        (
            set::name('url'),
            set::value($gitlab->url . '/' . $user->username . '/'),
        );
}
else
{
    $urlOptions[] = $gitlab->url;
    $urlOptions[] = picker
        (
            set::name('namespace_id'),
            set::value(!empty($defaultSpace) ? $defaultSpace : ''),
            set::items($namespaces),
            set::required(true)
        );
}

formPanel
(
    set::title($lang->gitlab->project->create),
    formGroup
    (
        set::name('name'),
        set::label($lang->gitlab->project->name),
        set::placeholder($lang->gitlab->project->name),
        set::required(true),
        set::width('2/3'),
        on::input('onNameChange')
    ),
    formGroup
    (
        set::label($lang->gitlab->project->url),
        set::width('2/3'),
        inputGroup
        (
            $urlOptions
        ),
    ),
    formGroup
    (
        set::label($lang->gitlab->project->path),
        set::name('path'),
        set::placeholder($lang->gitlab->placeholder->projectPath),
        set::required(true),
        set::width('2/3'),
    ),
    formGroup
    (
        set::label($lang->gitlab->project->description),
        set::name('description'),
        set::control('textarea'),
        set::width('2/3')
    ),
    formGroup
    (
        set::label($lang->gitlab->project->visibility),
        set::control('radioList'),
        set::items($lang->gitlab->project->visibilityList),
        set::value('private'),
        set::name('visibility'),
        on::change('onAclChange')
    )
);

render();
