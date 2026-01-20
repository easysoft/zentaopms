<?php
$lang->repobranchrule->common                   = '分支规则';
$lang->repobranchrule->setBranchRule            = '设置分支规则';
$lang->repobranchrule->specifyValueEmptyError   = '指定值不能为空';
$lang->repobranchrule->defaultValueRestoreError = '默认值还原失败';

$lang->repo->branchType            = '分支类型';
$lang->repo->applicableBranchTypes = '适用分支类型';
$lang->repo->allBranchTypes        = '全部分支类型';

$lang->repo->branchRuleMode = array();
$lang->repo->branchRuleMode['inheritance']  = '继承';
$lang->repo->branchRuleMode['redefinition'] = '重定义';

$lang->repo->branchTypeRule = new stdClass();
$lang->repo->branchTypeRule->allowCreatedBy     = '允许哪些用户可以创建该类型分支';
$lang->repo->branchTypeRule->allowDeletedBy     = '允许哪些用户可以删除该类型分支';
$lang->repo->branchTypeRule->allowUpdatedBy     = '允许哪些用户可以更新该类型分支';
$lang->repo->branchTypeRule->allowForcePushedBy = '允许哪些用户可以强制进行推送';
$lang->repo->branchTypeRule->allowMergeFrom     = '允许哪些分支类型合并到该分支类型';
$lang->repo->branchTypeRule->allowMergeTo       = '允许合并到哪些分支类型';

$lang->repo->branchTypeRule->userOptionList = array();
$lang->repo->branchTypeRule->userOptionList['hasPriv'] = '有权限的用户均可';
$lang->repo->branchTypeRule->userOptionList['specify'] = '仅指定人员';

$lang->repo->branchTypeRule->branchTypeOptionList = array();
$lang->repo->branchTypeRule->branchTypeOptionList['all']     = '全部分支';
$lang->repo->branchTypeRule->branchTypeOptionList['specify'] = '指定分支类型';

$lang->repo->branchRule = new stdClass();
$lang->repo->branchRule->allowDeletedBy     = '允许哪些用户可以删除该分支';
$lang->repo->branchRule->allowUpdatedBy     = '允许哪些用户可以更新该分支';
$lang->repo->branchRule->allowForcePushedBy = '允许哪些用户可以强制进行推送';
$lang->repo->branchRule->allowMergeFrom     = '允许哪些分支类型合并到该分支';
$lang->repo->branchRule->allowMergeTo       = '允许合并到哪些分支类型';
$lang->repo->branchRule->delete             = '删除分支规则';
$lang->repo->branchRule->mode               = '规则控制';

$lang->repo->branchRule->userOptionList = array();
$lang->repo->branchRule->userOptionList['hasPriv'] = '有权限的用户均可';
$lang->repo->branchRule->userOptionList['specify'] = '仅指定人员';

$lang->repo->branchRule->branchTypeOptionList = array();
$lang->repo->branchRule->branchTypeOptionList['all']     = '全部分支';
$lang->repo->branchRule->branchTypeOptionList['specify'] = '指定分支类型';
