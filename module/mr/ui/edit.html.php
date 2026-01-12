<?php
declare(strict_types=1);
/**
 * The create view file of mr module of ZenTaoPMS.
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Zeng Gang<zenggang@easycorp.ltd>
 * @package     mr
 * @link        https://www.zentao.net
 */
namespace zin;

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

formPanel
(
    set::title($lang->mr->edit),
    formGroup
    (
        set::width('535px'),
        set::required(true),
        set::name('title'),
        set::label($lang->mr->title),
        set::value($MR->title)
    ),
    formRow
    (
        formGroup
        (
            set::width('458px'),
            set::required(true),
            set::label($lang->mr->sourceBranch),
            set::value($MR->sourceBranch),
            set::control(array('control' => 'input', 'disabled' => true))
        ),
        span
        (
            setClass('ml-5 mr-2'),
            icon('angle-double-right icon-2x')
        ),
        formGroup
        (
            set::width('458px'),
            set::label($lang->mr->targetBranch),
            set::value($MR->targetBranch),
            set::control(array('control' => 'input', 'disabled' => true))
        )
    ),
    !empty($reviewers) ? formGroup
    (
        set::width('535px'),
        set::name('reviewer'),
        set::label($lang->mr->reviewer),
        set::items($users),
        set::control(array('control' => 'picker', 'disabled' => true, 'multiple' => true)),
        set::value($reviewers)
    ) : null,
    formGroup
    (
        set::name('desc'),
        set::label($lang->mr->description),
        set::control(array('control' => 'editor', 'upload-url' => 'disabled')),
        set::value($MR->desc)
    )
);
