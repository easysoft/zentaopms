<?php
$lang->reporeviewflow->browse       = '查看评审流程';
$lang->reporeviewflow->create       = '创建评审流程';
$lang->reporeviewflow->edit         = '编辑评审流程';
$lang->reporeviewflow->changeStatus = '启用/停用评审流程';
$lang->reporeviewflow->delete       = '删除评审流程';

$lang->reporeviewflow->name                  = '名称';
$lang->reporeviewflow->desc                  = '描述';
$lang->reporeviewflow->flowName              = '流程名称';
$lang->reporeviewflow->branchType            = '分支类型';
$lang->reporeviewflow->enableFlow            = '启用';
$lang->reporeviewflow->disableFlow           = '停用';
$lang->reporeviewflow->basicInfo             = '基本信息';
$lang->reporeviewflow->applicableBranchTypes = '适用的目标分支';
$lang->reporeviewflow->allBranchTypes        = '全部分支类型';
$lang->reporeviewflow->aiReview              = 'AI评审';
$lang->reporeviewflow->aiAssistedReview      = 'AI辅助评审';
$lang->reporeviewflow->aiReviewScores        = '可通过的最低AI评审分数';
$lang->reporeviewflow->manualReview          = '人工评审';
$lang->reporeviewflow->defaultReviewers      = '默认评审人';
$lang->reporeviewflow->specifiedReviewers    = '评审人必须包含指定成员';
$lang->reporeviewflow->minReviewers          = '最低评审人数量';
$lang->reporeviewflow->solveIssues           = '问题处理';
$lang->reporeviewflow->addressOption         = '如何处理评审人发现的问题';
$lang->reporeviewflow->newCommits            = '待合并分支有新的提交请求时';
$lang->reporeviewflow->mergeStrategy         = '合并策略';
$lang->reporeviewflow->mergeOptions          = '可采用的合并分支策略';
$lang->reporeviewflow->autoArchive           = '合并后自动归档来源分支';
$lang->reporeviewflow->autoArchiveNotice     = '仅当开启分支归档功能后可用';
$lang->reporeviewflow->allBranchTypesNotice  = '已存在全部分支类型审批流程';
$lang->reporeviewflow->enableSuccess         = '评审规则已启用';
$lang->reporeviewflow->disableSuccess        = '评审规则已停用';
$lang->reporeviewflow->aiScoreTips           = 'AI对代码评分超过该分数即通过AI评审';
$lang->reporeviewflow->status                = '状态';

$lang->reporeviewflow->flowStatusList = array();
$lang->reporeviewflow->flowStatusList['enable']  = '启用';
$lang->reporeviewflow->flowStatusList['disable'] = '停用';

$lang->reporeviewflow->aiReviewList = array();
$lang->reporeviewflow->aiReviewList['enable']  = '开启';
$lang->reporeviewflow->aiReviewList['disable'] = '关闭';

$lang->reporeviewflow->addressOptionList = array();
$lang->reporeviewflow->addressOptionList['noNeedToSolve']        = '无需解决';
$lang->reporeviewflow->addressOptionList['allMustBeSolved']      = '必须全部解决';
$lang->reporeviewflow->addressOptionList['specificMustBeSolved'] = '指定类型的必须解决';

$lang->reporeviewflow->newCommitsAddressOptionList = array();
$lang->reporeviewflow->newCommitsAddressOptionList['defaultApproval'] = '默认通过';
$lang->reporeviewflow->newCommitsAddressOptionList['requireReReview'] = '需重新评审';

$lang->reporeviewflow->mergeOptionList = array();
$lang->reporeviewflow->mergeOptionList['merge']  = '普通合并';
$lang->reporeviewflow->mergeOptionList['squash'] = '压缩并合并';
$lang->reporeviewflow->mergeOptionList['rebase'] = '变基并合并';
$lang->reporeviewflow->mergeOptionList['fast']   = '快速合并';

$lang->reporeviewflow->autoArchiveStatusList = array();
$lang->reporeviewflow->autoArchiveStatusList['enable']  = '开启';
$lang->reporeviewflow->autoArchiveStatusList['disable'] = '关闭';

$lang->reporeviewflow->notice = new stdclass();
$lang->reporeviewflow->notice->deleteReviewFlow = '您确定删除评审流程“%s”吗？';
