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
jsVar('repoID', $repoID);

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID=$objectID&module={$app->rawModule}&method={$app->rawMethod}"))
);

formPanel
(
    setID('createForm'),
    set::title($title),
    set::labelWidth($app->clientLang == 'zh-cn' ? '6em' : '10em'),
    on::change('[name=targetBranch]')->call('loadReviewers'),
    on::change('[name=sourceBranch]')->call('loadReviewers'),
    formGroup
    (
        set::width('535px'),
        set::required(true),
        set::name('title'),
        set::label($lang->mr->title)
    ),
    formRow
    (
        formGroup
        (
            set::width('458px'),
            set::required(true),
            set::label($lang->mr->sourceBranch),
            set::name('sourceBranch'),
            set::items($branches),
            set::value($activeBranch)
        ),
        span
        (
            setClass('ml-5 mr-2'),
            icon('angle-double-right icon-2x')
        ),
        formGroup
        (
            set::width('458px'),
            set::required(true),
            set::label($lang->mr->targetBranch),
            set::name('targetBranch'),
            set::items($branches),
            set::value($defaultBranch)
        )
    ),
    formGroup
    (
        set::width('535px'),
        set::required(true),
        set::name('reviewers'),
        set::label($lang->mr->reviewer),
        set::items($users),
        set::multiple(true)
    ),
    formGroup
    (
        set::name('description'),
        set::label($lang->mr->description),
        set::control(array('control' => 'editor', 'upload-url' => 'disabled'))
    ),
    formGroup
    (
        setClass('hidden'),
        set::name('reviewFlowID'),
        set::value(0)
    ),
    set::actions(array(
        'submit',
        array(
            'text'     => $lang->goback,
            'class'    => 'btn',
            'data-app' => $app->tab,
            'url'      => createLink($app->rawModule, 'browse', "repoID=" . ($executionID ? 0 : $repo->id) . "&mode=status&param=opened&objectID={$executionID}")
        )
    ))
);

div(setID('createCheckList'), setClass('panel-form size-lg'));
