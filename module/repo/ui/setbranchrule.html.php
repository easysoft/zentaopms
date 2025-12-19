<?php
declare(strict_types=1);

namespace zin;

$groupClass = common::checkNotCN() ? 'group-en-option' : '';

$deleteUserArray    = !empty($originRule->deleteUser) ? explode(',', $originRule->deleteUser) : array();
$updateUserArray    = !empty($originRule->updateUser) ? explode(',', $originRule->updateUser) : array();
$forcePushUserArray = !empty($originRule->forcePushUser) ? explode(',', $originRule->forcePushUser) : array();
$sourceBranchArray  = !empty($originRule->sourceBranch) ? explode(',', $originRule->sourceBranch) : array();
$targetBranchArray  = !empty($originRule->targetBranch) ? explode(',', $originRule->targetBranch) : array();

formPanel
(
    setID('setBranchRuleForm'),
    set::title($title),
    set::actions(array('submit', 'cancel', array('text' => $lang->repo->branchRule->delete, 'url' => createLink('repo', 'ajaxDeleteBranchRule', "repoID=$repoID&branchName=$branchName&ruleID=$ruleID")))),
    set::backUrl(inlink('browseBranch', "repoID=$repoID")),
    formGroup
    (
        set::label($lang->repo->branchRule->allowDeletedBy),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio"),
                set::value(!empty($originRule->deleteUser) ? 'specify' : 'hasPriv'),
                set::name('radioForAllowDelete'),
                set::items($lang->repo->branchRule->userOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowDeleteChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('userAllowDeleteGroup'),
                set::name('userAllowDeleteGroup'),
                set::items($users),
                set::value($deleteUserArray),
                set::multiple(true)
            )
        )
    ),
    formGroup
    (
        set::label($lang->repo->branchRule->allowUpdatedBy),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio"),
                set::value(!empty($originRule->updateUser) ? 'specify' : 'hasPriv'),
                set::name('radioForAllowUpdate'),
                set::items($lang->repo->branchRule->userOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowUpdateChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('userAllowUpdateGroup'),
                set::name('userAllowUpdateGroup'),
                set::items($users),
                set::value($updateUserArray),
                set::multiple(true)
            )
        )
    ),
    formGroup
    (
        set::label($lang->repo->branchRule->allowForcePushedBy),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio"),
                set::value(!empty($originRule->forcePushUser) ? 'specify' : 'hasPriv'),
                set::name('radioForAllowForcePush'),
                set::items($lang->repo->branchRule->userOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowForcePushChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('userAllowForcePushGroup'),
                set::name('userAllowForcePushGroup'),
                set::items($users),
                set::value($forcePushUserArray),
                set::multiple(true)
            )
        )
    ),
    formGroup
    (
        set::label($lang->repo->branchRule->allowMergeFrom),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio pl-52px"),
                set::value(!empty($originRule->sourceBranch) ? 'specify' : 'all'),
                set::name('radioForAllowMergeFrom'),
                set::items($lang->repo->branchRule->branchTypeOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowMergeFromChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('branchTypeAllowMergeFromGroup'),
                set::name('branchTypeAllowMergeFromGroup'),
                set::items($branchTypes),
                set::value($sourceBranchArray),
                set::multiple(true)
            )
        )
    ),
    formGroup
    (
        set::label($lang->repo->branchRule->allowMergeTo),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio pl-52px"),
                set::value(!empty($originRule->targetBranch) ? 'specify' : 'all'),
                set::name('radioForAllowMergeTo'),
                set::items($lang->repo->branchRule->branchTypeOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowMergeToChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('branchTypeAllowMergeToGroup'),
                set::name('branchTypeAllowMergeToGroup'),
                set::items($branchTypes),
                set::value($targetBranchArray),
                set::multiple(true)
            )
        )
    )
);