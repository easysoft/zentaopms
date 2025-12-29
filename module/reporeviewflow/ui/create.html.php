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
formPanel
(
    setID('createReviewFlow'),
    set::title($title),
    set::labelWidth(common::checkNotCN() ? '280px' : '200px'),
    on::change('[name=isAllBranchTypes]')->call('window.disableBranchType'),
    on::change('[name=aiReview]')->call('window.loadAiReviewScores'),
    on::change('[name=addressOption]')->call('window.loadIssueType'),
    formRowGroup(set::title($lang->reporeviewflow->basicInfo)),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->reporeviewflow->name),
        set::name('name'),
        set::required(true)
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->reporeviewflow->applicableBranchTypes),
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
                    set::required(true)
                )
            ),
            div
            (
                setClass('input-group-addon flex'),
                checkbox
                (
                    set::name('isAllBranchTypes'),
                    set::text($lang->reporeviewflow->allBranchTypes)
                )
            )
        )
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->reporeviewflow->desc),
        set::name('desc'),
        set::control(array('type' => 'textarea', 'rows' => 1)),
    ),
    formRowGroup(set::title($lang->reporeviewflow->aiReview)),
    formGroup
    (
        setID('aiReview'),
        set::width('2/3'),
        set::name('aiReview'),
        set::required(true),
        set::label($lang->reporeviewflow->aiAssistedReview),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->reporeviewflow->aiReviewList),
        set::value('disable')
    ),
    formGroup
    (
        setID('aiReviewScores'),
        set::width('2/3'),
        set::name('aiReviewScores'),
        set::label($lang->reporeviewflow->aiReviewScores),
        set::control(array('type' => 'number', 'min' => 0, 'max' => 100, 'placeholder' => $lang->reporeviewflow->aiScoreTips)),
        set::value(0)
    ),
    formRowGroup(set::title($lang->reporeviewflow->manualReview)),
    formGroup
    (
        setID('defaultReviewers'),
        set::width('2/3'),
        set::name('defaultReviewers'),
        set::label($lang->reporeviewflow->defaultReviewers),
        set::items($repoMembers),
        set::control(array('onSelect' => jsRaw('addSpecifiedReviewers'), 'onDeselect' => jsRaw('removeSpecifiedReviewers'))),
        set::multiple(true)
    ),
    formGroup
    (
        setID('specifiedReviewers'),
        set::width('2/3'),
        set::name('specifiedReviewers'),
        set::label($lang->reporeviewflow->specifiedReviewers),
        set::items($repoMembers),
        set::multiple(true)
    ),
    formGroup
    (
        setID('minReviewers'),
        set::width('2/3'),
        set::label($lang->reporeviewflow->minReviewers),
        set::name('minReviewers'),
        set::control(array('type' => 'number', 'min' => 0, 'max' => 9)),
        set::value(0)
    ),
    formRowGroup(set::title($lang->reporeviewflow->solveIssues)),
    formRow
    (
        set::width('2/3'),
        formGroup
        (
            set::width('4/5'),
            set::name('addressOption'),
            set::label($lang->reporeviewflow->addressOption),
            set::control(array('type' => 'radioList', 'inline' => true)),
            set::items($lang->reporeviewflow->addressOptionList),
            set::value('noNeedToSolve')
        ),
        formGroup
        (
            set::width('1/5'),
            setID('issueType'),
            set::name('issueType'),
            set::items($lang->bug->typeList),
            set::multiple(true),
            set::value('codeerror')
        )
    ),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->reporeviewflow->newCommits),
        set::name('newCommits'),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->reporeviewflow->newCommitsAddressOptionList),
        set::value('defaultApproval')
    ),
    formRowGroup(set::title($lang->reporeviewflow->mergeStrategy)),
    formGroup
    (
        set::width('2/3'),
        set::label($lang->reporeviewflow->mergeOptions),
        set::name('mergeOptions'),
        set::items($lang->reporeviewflow->mergeOptionList),
        set::multiple(true),
        set::required(true),
        set::value('merge,squash,rebase,fast')
    ),
    formGroup
    (
        set::width('2/3'),
        set::name('autoArchive'),
        set::label($lang->reporeviewflow->autoArchive),
        set::labelHintIcon('help'),
        set::labelHint($lang->reporeviewflow->autoArchiveNotice),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->reporeviewflow->autoArchiveStatusList),
        set::value('disable')
    )
);
