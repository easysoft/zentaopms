<?php
declare(strict_types=1);

namespace zin;

$labelWidth = in_array($currentLang, array('zh-cn', 'zh-tw')) ? '190px' : '340px';

$deleteUserArray    = !empty($originRule->deleteUser) ? explode(',', $originRule->deleteUser) : array();
$updateUserArray    = !empty($originRule->updateUser) ? explode(',', $originRule->updateUser) : array();
$forcePushUserArray = !empty($originRule->forcePushUser) ? explode(',', $originRule->forcePushUser) : array();
$sourceBranchArray  = !empty($originRule->sourceBranch) ? explode(',', $originRule->sourceBranch) : array();
$targetBranchArray  = !empty($originRule->targetBranch) ? explode(',', $originRule->targetBranch) : array();

formPanel
(
    set::title($title),
    set::actions(array('submit', 'cancel')),
    set::backUrl(inlink('browseBranch', "repoID=$repoID")),
    on::init()->call('onRadioForAllowDeleteChange'),
    on::init()->call('onRadioForAllowUpdateChange'),
    on::init()->call('onRadioForAllowForcePushChange'),
    on::init()->call('onRadioForAllowMergeFromChange'),
    on::init()->call('onRadioForAllowMergeToChange'),
    formRow
    (
        formGroup
        (
            set::label($lang->repo->branchRule->allowDeletedBy),
            set::labelWidth($labelWidth),
            radioList
            (
                set::value(!empty($originRule->deleteUser) ? 'specify' : 'hasPriv'),
                set::name('radioForAllowDelete'),
                set::items($lang->repo->branchRule->userOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowDeleteChange')
            )
        ),
        formGroup
        (
            setClass('hidden'),
            setID('userAllowDeleteGroup'),
            picker
            (
                set::name('userAllowDeleteGroup'),
                set::items($users),
                set::value($deleteUserArray),
                set::multiple(true)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->repo->branchRule->allowUpdatedBy),
            set::labelWidth($labelWidth),
            radioList
            (
                set::value(!empty($originRule->updateUser) ? 'specify' : 'hasPriv'),
                set::name('radioForAllowUpdate'),
                set::items($lang->repo->branchRule->userOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowUpdateChange')
            )
        ),
        formGroup
        (
            setClass('hidden'),
            setID('userAllowUpdateGroup'),
            picker
            (
                set::name('userAllowUpdateGroup'),
                set::items($users),
                set::value($updateUserArray),
                set::multiple(true)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->repo->branchRule->allowForcePushedBy),
            set::labelWidth($labelWidth),
            radioList
            (
                set::value(!empty($originRule->forcePushUser) ? 'specify' : 'hasPriv'),
                set::name('radioForAllowForcePush'),
                set::items($lang->repo->branchRule->userOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowForcePushChange')
            )
        ),
        formGroup
        (
            setClass('hidden'),
            setID('userAllowForcePushGroup'),
            picker
            (
                set::name('userAllowForcePushGroup'),
                set::items($users),
                set::value($forcePushUserArray),
                set::multiple(true)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->repo->branchRule->allowMergeFrom),
            set::labelWidth($labelWidth),
            radioList
            (
                set::value(!empty($originRule->sourceBranch) ? 'specify' : 'all'),
                set::name('radioForAllowMergeFrom'),
                set::items($lang->repo->branchRule->branchTypeOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowMergeFromChange')
            )
        ),
        formGroup
        (
            setClass('hidden'),
            setID('branchTypeAllowMergeFromGroup'),
            picker
            (
                set::name('branchTypeAllowMergeFromGroup'),
                set::items($branchTypes),
                set::value($sourceBranchArray),
                set::multiple(true)
            )
        )
    ),
    formRow
    (
        formGroup
        (
            set::label($lang->repo->branchRule->allowMergeTo),
            set::labelWidth($labelWidth),
            radioList
            (
                set::value(!empty($originRule->targetBranch) ? 'specify' : 'all'),
                set::name('radioForAllowMergeTo'),
                set::items($lang->repo->branchRule->branchTypeOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowMergeToChange')
            )
        ),
        formGroup
        (
            setClass('hidden'),
            setID('branchTypeAllowMergeToGroup'),
            picker
            (
                set::name('branchTypeAllowMergeToGroup'),
                set::items($branchTypes),
                set::value($targetBranchArray),
                set::multiple(true)
            )
        )
    )
);