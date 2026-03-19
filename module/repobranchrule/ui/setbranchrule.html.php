<?php
declare(strict_types=1);
namespace zin;
$module = $app->tab == 'devops' ? 'repo' : $app->tab;
if(!isInModal())
{
    dropmenu
    (
        set::module($module),
        set::tab($module),
        set::url(createLink($module, 'ajaxGetDropMenu', "objectID={$repoID}&module={$app->rawModule}&method={$app->rawMethod}"))
    );
}
$basicSettings = array();
//$basicRules    = empty($branchTypeID) ? $lang->repobranchrule->branchRule : $lang->repobranchrule->branchTypeRule;
//foreach($basicRules as $ruleType => $label)
//{
//    $options = in_array($ruleType, array('sourceBranch', 'targetBranch')) ? $lang->repobranchrule->branchTypeOptionList : $lang->repobranchrule->userOptionList;
//
//    $basicSettings[] = formGroup
//    (
//        set::label($label),
//        inputGroup
//        (
//            radioList
//            (
//                setClass('switch-rule mr-5'),
//                set::value(0),
//                set::name("{$ruleType}Option"),
//                set::items($options),
//                set::inline(true)
//            ),
//            picker
//            (
//                set::width('150px'),
//                setClass('hidden'),
//                set::name($ruleType),
//                set::items($users),
//                set::value(),
//                set::multiple(true)
//            )
//        )
//    );
//}

$backURL = empty($branchTypeID) ? createLink('repo', 'browseBranch', "repoID=$repoID") : createLink('repobranchtype', 'browse', "repoID=$repoID");
$url     = createLink('repobranchrule', 'setBranchRule', "branchTypeID=$branchTypeID&repoID=$repoID&branchName=$branchName&from=$from");
formPanel
(
    setID('setBranchRuleForm'),
    set::title($title),
    set::actions
    (
        !empty($branchTypeID) ? array('submit', array('text' => $lang->cancel, 'url' => $backURL)) :
        array
        (
            'submit',
            array('text' => $lang->cancel, 'url' => $backURL),
            array
            (
                'text' => $lang->repo->branchRule->delete,
                'url'  => createLink('repobranchrule', 'ajaxDeleteBranchRule', "branchTypeID=$branchTypeID&repoID=$repoID&branchName=$branchName&ruleID=$ruleID&from=$from&isDefault=$isDefault")
            )
        )
    ),
    on::change('.switch-rule')->call('setBranchRule', jsRaw('this')),
    //formRowGroup(set::title($lang->repobranchrule->basicSetting)),
    $basicSettings,
    //formRowGroup(set::title($lang->repobranchrule->commitSetting)),
    //formRowGroup(set::title($lang->repobranchrule->codeReviewFlow)),
    formGroup
    (
        setID('forceReview'),
        set::label($lang->repobranchrule->forceReview),
        set::name('forceReview'),
        set::control(array('type' => 'radioList', 'inline' => true)),
        set::items($lang->repobranchrule->enableStatusList),
        set::value(zget($originRule, 'forceReview', 0))
    ),
    formGroup
    (
        setID('reviewFlow'),
        set::label($lang->repobranchrule->reviewFlow),
        inputGroup
        (
            picker
            (
                set::name('reviewFlowID'),
                set::required(false),
                set::items($reviewFlows),
                set::value(zget($originRule, 'reviewFlowID', 0))
            ),
            div
            (
                a(set::className('btn ml-1'), on::click()->do("loadCurrentPage({url: '{$url}', selector: '#reviewFlow', partial: true})"), set::href('#'), $lang->refresh)
            ),
            hasPriv('reporeviewflow', 'create') ? div
            (
                a(set::className('btn secondary ml-1'), set::href(createLink('reporeviewflow', 'create', 'repoID=' . $repoID)), set::target('_blank'), $lang->create)
            ) : null
        )
    )
);
