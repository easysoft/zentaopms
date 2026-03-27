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
    formGroup
    (
        setID('reviewFlow'),
        set::label($lang->repobranchrule->reviewFlow),
        set::labelWidth('200px'),
        inputGroup
        (
            setID('reviewFlowBox'),
            picker
            (
                set::name('reviewFlowID'),
                set::required(false),
                set::items($reviewFlows),
                set::value(zget($originRule, 'reviewFlowID', 0))
            ),
            div
            (
                a(set::className('btn ml-1'), on::click()->do("loadCurrentPage({url: '{$url}', selector: '#reviewFlowBox', partial: true})"), set::href('#'), $lang->refresh)
            ),
            hasPriv('reporeviewflow', 'create') ? div
            (
                a(set::className('btn secondary ml-1'), set::href(createLink('reporeviewflow', 'create', 'repoID=' . $repoID)), set::target('_blank'), $lang->create)
            ) : null
        )
    )
);
