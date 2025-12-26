<?php
declare(strict_types=1);
/**
 * The create review flow view file of repo module of ZenTaoPMS.
 * @copyright   Copyright 2009-2025 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.zentao.net)
 * @license     ZPL(https://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yang Li <liyang@chandao.com>
 * @package     repo
 * @link        https://www.zentao.net
 */
namespace zin;
dropmenu
(
    set::module('repo'),
    set::tab('repo'),
    set::url(createLink('repo', 'ajaxGetDropMenu', "repoID={$repoID}&module={$app->rawModule}&method={$app->rawMethod}"))
);

unset($branchTypes[0]);
$definition = zget($reviewFlow, 'definition', array());
jsVar('editBranchTypes', explode(',', $reviewFlow->branchType));
formPanel
(
    setID('editReviewFlow'),
    set::title($title),
    set::labelWidth(common::checkNotCN() ? '320px' : '240px'),
    on::change('[name=isAllBranchTypes]')->call('window.disableBranchType'),
    on::change('[name=aiReview]')->call('window.loadAiReviewScores'),
    on::change('[name=addressOption]')->call('window.loadIssueType'),
    formGroup
    (
        set::name(''),
        set::control('static'),
        set::label($lang->repo->basicInfo),
        set::labelClass('font-black')
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->repo->name),
        set::name('name'),
        set::required(true),
        set::value($reviewFlow->name)
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->repo->applicableBranchTypes),
        set::required(true),
        inputGroup
        (
            div
            (
                setClass('w-full'),
                setID('branchTypesBox'),
                picker
                (
                    set::name('branchType'),
                    set::items($branchTypes),
                    set::multiple(true),
                    set::required(true),
                    set::value(zget($reviewFlow, 'branchType', ''))
                )
            ),
            div
            (
                setClass('input-group-addon flex'),
                checkbox
                (
                    set::name('isAllBranchTypes'),
                    set::text($lang->repo->allBranchTypes),
                    set::checked(empty($reviewFlow->branchType))
                )
            )
        )
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->repo->desc),
        set::name('desc'),
        set::control(array('type' => 'textarea', 'rows' => 5)),
        set::value($reviewFlow->desc)
    ),
    formGroup
    (
        set::name(''),
        set::control('static'),
        set::label($lang->repo->aiReview),
        set::labelClass('font-black')
    ),
    formGroup
    (
        setID('aiReview'),
        set::width('2/3'),
        set::name('aiReview'),
        set::required(true),
        set::label($lang->repo->aiAssistedReview),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->repo->aiReviewList),
        set::value(empty($definition->ai) || empty($definition->ai->enable) ? 'disable' : 'enable')
    ),
    formGroup
    (
        setID('aiReviewScores'),
        set::width('2/3'),
        set::name('aiReviewScores'),
        set::label($lang->repo->aiReviewScores),
        set::control(array('type' => 'number', 'min' => 0, 'max' => 100, 'placeholder' => $lang->repo->aiScoreTips)),
        set::value(empty($definition->ai) || empty($definition->ai->approvals) ? 0 : $definition->ai->approvals->score)
    ),
    formGroup
    (
        set::name(''),
        set::control('static'),
        set::label($lang->repo->manualReview),
        set::labelClass('font-black')
    ),
    formGroup
    (
        setID('defaultReviewers'),
        set::width('2/3'),
        set::name('defaultReviewers'),
        set::label($lang->repo->defaultReviewers),
        set::items($repoMembers),
        set::control(array('onSelect' => jsRaw('addSpecifiedReviewers'), 'onDeselect' => jsRaw('removeSpecifiedReviewers'))),
        set::multiple(true),
        set::value(empty($definition->reviewFlow) || empty($definition->reviewFlow->approvals) ? '' : $definition->reviewFlow->approvals->defaultReviewers)
    ),
    formGroup
    (
        setID('specifiedReviewers'),
        set::width('2/3'),
        set::name('specifiedReviewers'),
        set::label($lang->repo->specifiedReviewers),
        set::items($repoMembers),
        set::multiple(true),
        set::value(empty($definition->reviewFlow) || empty($definition->reviewFlow->approvals) ? '' : $definition->reviewFlow->approvals->specifiedReviewers)
    ),
    formGroup
    (
        setID('minReviewers'),
        set::width('2/3'),
        set::label($lang->repo->minReviewers),
        set::name('minReviewers'),
        set::control(array('type' => 'number', 'min' => 0, 'max' => 9)),
        set::value(empty($definition->reviewFlow) || empty($definition->reviewFlow->approvals) ? 0 : $definition->reviewFlow->approvals->minReviewers)
    ),
    formGroup
    (
        set::name(''),
        set::control('static'),
        set::label($lang->repo->solveIssues),
        set::labelClass('font-black')
    ),
    formRow
    (
        set::width('2/3'),
        formGroup
        (
            set::width('4/5'),
            set::name('addressOption'),
            set::label($lang->repo->addressOption),
            set::control(array('type' => 'radioList', 'inline' => true)),
            set::items($lang->repo->addressOptionList),
            set::value(empty($definition->reviewFlow) || empty($definition->reviewFlow->issues) ? 'noNeedToSolve' : $definition->reviewFlow->issues->addressOption)
        ),
        formGroup
        (
            set::width('1/5'),
            setID('issueType'),
            set::name('issueType'),
            set::items($lang->bug->typeList),
            set::multiple(true),
            set::value(empty($definition->reviewFlow) || empty($definition->reviewFlow->issues) || empty($definition->reviewFlow->issues->mandatoryType) ? 'codeerror' : $definition->reviewFlow->issues->mandatoryType)
        )
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->repo->newCommits),
        set::name('newCommits'),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->repo->newCommitsAddressOptionList),
        set::value(empty($definition->reviewFlow) || empty($definition->reviewFlow->newCommits) ? 'defaultApproval' : $definition->reviewFlow->newCommits->addressOption)
    ),
    formGroup
    (
        set::name(''),
        set::control('static'),
        set::label($lang->repo->mergeStrategy),
        set::labelClass('font-black')
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->repo->mergeOptions),
        set::name('mergeOptions'),
        set::items($lang->repo->mergeOptionList),
        set::multiple(true),
        set::required(true),
        set::value(empty($definition->reviewFlow) || empty($definition->reviewFlow->merge) ? 'merge,squash,rebase,fast' : $definition->reviewFlow->merge->options)
    ),
    formGroup
    (
        set::width('2/3'),
        set::name('autoArchive'),
        set::label($lang->repo->autoArchive),
        set::labelHintIcon('help'),
        set::labelHint($lang->repo->autoArchiveNotice),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->repo->autoArchiveStatusList),
        set::value(empty($definition->reviewFlow) || empty($definition->reviewFlow->merge) || empty($definition->reviewFlow->merge->autoArchive) ? 'disable' : 'enable')
    )
);
