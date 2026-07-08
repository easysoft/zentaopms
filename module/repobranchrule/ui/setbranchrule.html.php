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
        set::url(createLink($module, 'ajaxGetDropMenu', "objectID={$repoID}&module={$app->rawModule}&method={$app->rawMethod}&tab={$app->tab}"))
    );
}

$backURL = empty($branchTypeID) ? createLink('repo', 'browseBranch', "repoID=$repoID&objectID=$objectID") : createLink('repobranchtype', 'browse', "repoID=$repoID");
$url     = createLink('repobranchrule', 'setBranchRule', "branchTypeID=$branchTypeID&repoID=$repoID&branchName=$branchName&from=$from&objectID=$objectID");

formPanel
(
    setID('setBranchRuleForm'),
    set::title($title),
    set::actions
    (
        array
        (
            'submit',
            array('text' => $lang->cancel, 'url' => $backURL, 'data-app' => $app->tab),
            empty($branchTypeID) ? array
            (
                'text'     => $lang->repo->branchRule->delete,
                'data-app' => $app->tab,
                'url'      => createLink('repobranchrule', 'ajaxDeleteBranchRule', "branchTypeID=$branchTypeID&repoID=$repoID&branchName=$branchName&ruleID=$ruleID&from=$from&objectID=$objectID")
            ) : null
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
