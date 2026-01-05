<?php
declare(strict_types=1);

namespace zin;

$groupClass = common::checkNotCN() ? 'group-en-option' : '';

$createUserArray    = !empty($originRule->createUser) ? explode(',', $originRule->createUser) : array();
$deleteUserArray    = !empty($originRule->deleteUser) ? explode(',', $originRule->deleteUser) : array();
$updateUserArray    = !empty($originRule->updateUser) ? explode(',', $originRule->updateUser) : array();
$forcePushUserArray = !empty($originRule->forcePushUser) ? explode(',', $originRule->forcePushUser) : array();
$sourceBranchArray  = !empty($originRule->sourceBranch) ? explode(',', $originRule->sourceBranch) : array();
$targetBranchArray  = !empty($originRule->targetBranch) ? explode(',', $originRule->targetBranch) : array();

$createUserOption    = !empty($originRule->createUser) ? 'specify' : 'hasPriv';
$deleteUserOption    = !empty($originRule->deleteUser) ? 'specify' : 'hasPriv';
$updateUserOption    = !empty($originRule->updateUser) ? 'specify' : 'hasPriv';
$forcePushUserOption = !empty($originRule->forcePushUser) ? 'specify' : 'hasPriv';
$sourceBranchOption  = !empty($originRule->sourceBranch) ? 'specify' : 'all';
$targetBranchOption  = !empty($originRule->targetBranch) ? 'specify' : 'all';

$langAllowDelete    = empty($branchTypeID) ? $lang->repo->branchRule->allowDeletedBy : $lang->repo->branchTypeRule->allowDeletedBy;
$langAllowUpdate    = empty($branchTypeID) ? $lang->repo->branchRule->allowUpdatedBy : $lang->repo->branchTypeRule->allowUpdatedBy;
$langAllowForcePush = empty($branchTypeID) ? $lang->repo->branchRule->allowForcePushedBy : $lang->repo->branchTypeRule->allowForcePushedBy;
$langAllowMergeFrom = empty($branchTypeID) ? $lang->repo->branchRule->allowMergeFrom : $lang->repo->branchTypeRule->allowMergeFrom;
$langAllowMergeTo   = empty($branchTypeID) ? $lang->repo->branchRule->allowMergeTo : $lang->repo->branchTypeRule->allowMergeTo;

$backURL = empty($branchTypeID) ? createLink('repo', 'browseBranch', "repoID=$repoID") : createLink('repo', 'browsebranchtype', "repoID=$repoID");

$module = $app->tab == 'devops' ? 'repo' : $app->tab;
dropmenu
(
    set::module($module),
    set::tab($module),
    set::url(createLink($module, 'ajaxGetDropMenu', "objectID={$repoID}&module={$app->rawModule}&method={$app->rawMethod}"))
);

formPanel
(
    setID('setBranchRuleForm'),
    set::title($title),
    set::actions($branchTypeID != 0
        ? array('submit', array('text' => $lang->cancel, 'url' => $backURL))
        : array(
            'submit',
            array('text' => $lang->cancel, 'url' => $backURL),
            array(
                'text' => $lang->repo->branchRule->delete,
                'url'  => createLink('repobranchrule', 'ajaxDeleteBranchRule', "branchTypeID=$branchTypeID&repoID=$repoID&branchName=$branchName&ruleID=$ruleID&from=$from&isDefault=$isDefault")
            )
        )
    ),
    set::backUrl(inlink('browseBranch', "repoID=$repoID")),
    $branchTypeID != 0 ? formGroup
    (
        set::label($lang->repo->branchTypeRule->allowCreatedBy),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio"),
                set::value($createUserOption),
                set::name("allowCreate[option]"),
                set::items($lang->repo->branchRule->userOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowCreateChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('userAllowCreateGroup'),
                set::name("allowCreate[value]"),
                set::items($users),
                set::value($createUserArray),
                set::multiple(true)
            )
        )
    ) : null,
    $branchTypeID != 0 || !$isDefault ? formGroup
    (
        set::label($langAllowDelete),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio"),
                set::value($deleteUserOption),
                set::name("allowDelete[option]"),
                set::items($lang->repo->branchRule->userOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowDeleteChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('userAllowDeleteGroup'),
                set::name("allowDelete[value]"),
                set::items($users),
                set::value($deleteUserArray),
                set::multiple(true)
            )
        )
    ) : null,
    formGroup
    (
        set::label($langAllowUpdate),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio"),
                set::value($updateUserOption),
                set::name("allowUpdate[option]"),
                set::items($lang->repo->branchRule->userOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowUpdateChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('userAllowUpdateGroup'),
                set::name("allowUpdate[value]"),
                set::items($users),
                set::value($updateUserArray),
                set::multiple(true)
            )
        )
    ),
    formGroup
    (
        set::label($langAllowForcePush),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio"),
                set::value($forcePushUserOption),
                set::name("allowForcePush[option]"),
                set::items($lang->repo->branchRule->userOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowForcePushChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('userAllowForcePushGroup'),
                set::name("allowForcePush[value]"),
                set::items($users),
                set::value($forcePushUserArray),
                set::multiple(true)
            )
        )
    ),
    formGroup
    (
        set::label($langAllowMergeFrom),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio pl-52px"),
                set::value($sourceBranchOption),
                set::name("allowMergeFrom[option]"),
                set::items($lang->repo->branchRule->branchTypeOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowMergeFromChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('branchTypeAllowMergeFromGroup'),
                set::name("allowMergeFrom[value]"),
                set::items($branchTypes),
                set::value($sourceBranchArray),
                set::multiple(true)
            )
        )
    ),
    formGroup
    (
        set::label($langAllowMergeTo),
        set::width('full'),
        set::className($groupClass),
        inputGroup
        (
            radioList
            (
                set::className("$groupClass branchRuleRadio pl-52px"),
                set::value($targetBranchOption),
                set::name("allowMergeTo[option]"),
                set::items($lang->repo->branchRule->branchTypeOptionList),
                set::inline(true),
                on::change()->call('onRadioForAllowMergeToChange')
            ),
            picker
            (
                set::className('hidden branchRulePicker'),
                setID('branchTypeAllowMergeToGroup'),
                set::name("allowMergeTo[value]"),
                set::items($branchTypes),
                set::value($targetBranchArray),
                set::multiple(true)
            )
        )
    )
);
