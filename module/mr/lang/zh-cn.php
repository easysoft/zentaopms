<?php
$lang->mr->common   = "合并请求";
$lang->mr->create   = "创建{$lang->mr->common}";
$lang->mr->browse   = "浏览{$lang->mr->common}";
$lang->mr->list     = $lang->mr->browse;
$lang->mr->edit     = "编辑{$lang->mr->common}";
$lang->mr->delete   = "删除{$lang->mr->common}";
$lang->mr->view     = "{$lang->mr->common}详情";
$lang->mr->source   = '源项目分支';
$lang->mr->target   = '目标项目分支';
$lang->mr->viewDiff = '查看Merge Request 改动';

$lang->mr->id          = 'ID';
$lang->mr->mriid       = "MR原始ID";
$lang->mr->name        = '名称';
$lang->mr->status      = '状态';
$lang->mr->author      = '创建人';
$lang->mr->assignee    = '指派给';
$lang->mr->reviewer    = '评审人';
$lang->mr->link        = 'GitLab链接';
$lang->mr->mergeStatus = '是否可合并';
$lang->mr->commits     = '提交数';
$lang->mr->changes     = '更改数';

$lang->mr->statusList = array();
$lang->mr->statusList['opened'] = '开放中';
$lang->mr->statusList['closed'] = '已关闭';
$lang->mr->statusList['merged'] = '已合并';

$lang->mr->mergeStatusList = array();
$lang->mr->mergeStatusList['checking']         = '检查中';
$lang->mr->mergeStatusList['can_be_merged']    = '可合并';
$lang->mr->mergeStatusList['cannot_be_merged'] = '不可合并';

$lang->mr->description   = '描述';
$lang->mr->confirmDelete = '确认删除该merge request吗？';
$lang->mr->sourceProject = '源项目';
$lang->mr->sourceBranch  = '源分支';
$lang->mr->targetProject = '目标项目';
$lang->mr->targetBranch  = '目标分支';

$lang->mr->usersTips = '提示：如果无法选择指派人和评审人，请先前往GitLab页面绑定用户。';
$lang->mr->notFound  = "此{$lang->mr->common}不存在。";

$lang->mr->createFailedFromAPI = "创建合并请求失败。";
$lang->mr->accessGitlabFailed  = "当前无法连接到GitLab服务器。";

$lang->mr->description = "描述";

$lang->mr->from = "请求合并";
$lang->mr->to   = "入";
$lang->mr->at   = "于";
$lang->mr->pipeline        = "流水线";
$lang->mr->pipelineSuccess = "已通过";
$lang->mr->pipelineFailed  = "未通过";
$lang->mr->pipelineCancled = "已取消";
$lang->mr->pipelineUnknown = "未知";

$lang->mr->pipelineStatus = array();
$lang->mr->pipelineStatus['success']  = "已通过";
$lang->mr->pipelineStatus['failed']   = "未通过";
$lang->mr->pipelineStatus['canceled'] = "已取消";

$lang->mr->MRHasConflicts = "是否存在合并冲突";
$lang->mr->hasConflicts   = "存在合并冲突";
$lang->mr->hasNoConflict  = "可以合并请求";
$lang->mr->mergeByManual  = "此合并请求可以手动合并，请使用以下";
$lang->mr->commandLine    = "命令行";

/**
 * Merge Command Document.
 *
 * %s source_roject::http_url_to_repo
 * %s mr::source_branch
 * %s source_project::path_with_namespace . '-' . mr::source_branch
 * %s mr::target_branch
 * %s source_project::path_with_namespace . '-' . mr::source_branch
 * %s mr::target_branch
 */
$lang->mr->commandDocument = <<< EOD
<h4>在本地检出、审核和合并</h4>
    <strong>第 1 步</strong>. 获取并查看此合并请求的分支
    <pre>
    git fetch "%s" %s
    git checkout -b "%s" FETCH_HEAD
    </pre>
    <strong>第 2 步</strong>. 在本地查看更改
    <br>
    <strong>第 3 步</strong>. 合并分支并解决出现的任何冲突
    <pre>
    git fetch origin
    git checkout "%s"
    git merge --no-ff "%s"
    </pre>
    <strong>第 4 步</strong>. 将合并结果推送到GitLab
    <pre>
    git push origin "%s"
    </pre>
EOD;

