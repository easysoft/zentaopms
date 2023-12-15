<?php
$lang->mr = new stdclass;
$lang->mr->common       = "合并请求";
$lang->mr->server       = "服务器";
$lang->mr->hostID       = "服务器";
$lang->mr->view         = "概况";
$lang->mr->viewAction   = "{$lang->mr->common}详情";
$lang->mr->create       = "创建{$lang->mr->common}";
$lang->mr->apiCreate    = "接口：创建{$lang->mr->common}";
$lang->mr->browse       = "浏览{$lang->mr->common}";
$lang->mr->browseAction = "{$lang->mr->common}列表";
$lang->mr->list         = $lang->mr->browse;
$lang->mr->edit         = "编辑{$lang->mr->common}";
$lang->mr->delete       = "删除{$lang->mr->common}";
$lang->mr->accept       = "合并请求";
$lang->mr->source       = '源项目分支';
$lang->mr->target       = '目标项目分支';
$lang->mr->viewDiff     = '比对代码';
$lang->mr->diff         = '比对代码';
$lang->mr->viewInGit    = '在应用中查看';
$lang->mr->link         = '关联需求、Bug、任务';
$lang->mr->createAction = '%s, 由 <strong>%s</strong> 提交了 <a href="%s">合并请求</a>。';
$lang->mr->editAction   = '%s, 由 <strong>%s</strong> 编辑了 <a href="%s">合并请求</a>。';
$lang->mr->removeAction = '%s, 由 <strong>%s</strong> 删除了 <a href="%s">合并请求</a>。';
$lang->mr->submitType   = '提交方式';

$lang->mr->linkList  = '浏览关联需求、Bug、任务';
$lang->mr->linkStory = '关联需求';
$lang->mr->linkBug   = '关联Bug';
$lang->mr->linkTask  = '关联任务';
$lang->mr->unlink    = '取消关联需求、Bug、任务';
$lang->mr->addReview = '添加评审';

$lang->mr->id          = 'ID';
$lang->mr->mriid       = "MR原始ID";
$lang->mr->title       = '名称';
$lang->mr->status      = '状态';
$lang->mr->author      = '创建人';
$lang->mr->createdDate = '创建时间';
$lang->mr->assignee    = '指派给';
$lang->mr->reviewer    = '评审人';
$lang->mr->mergeStatus = '是否可合并';
$lang->mr->commits     = '提交数';
$lang->mr->changes     = '更改数';
$lang->mr->gitlabID    = 'GitLab';
$lang->mr->repoID      = '版本库';
$lang->mr->jobID       = '流水线任务';

$lang->mr->canMerge  = "可合并";
$lang->mr->cantMerge = "不可合并";

$lang->mr->approval = '评审';
$lang->mr->approve  = '通过';
$lang->mr->reject   = '拒绝';
$lang->mr->close    = '关闭';
$lang->mr->reopen   = '重新打开';

$lang->mr->reviewType     = '评审类型';
$lang->mr->reviewTypeList = array();
$lang->mr->reviewTypeList['bug']  = 'Bug';
$lang->mr->reviewTypeList['task'] = '任务';

$lang->mr->approvalResult     = '评审意见';
$lang->mr->approvalResultList = array();
$lang->mr->approvalResultList['approve'] = '通过';
$lang->mr->approvalResultList['reject']  = '拒绝';

$lang->mr->needApproved       = '需要通过评审才能合并';
$lang->mr->needCI             = '需要通过流水线才能合并';
$lang->mr->removeSourceBranch = '合并后删除源分支';
$lang->mr->squash             = '合并提交记录';

$lang->mr->repeatedOperation = '请勿重复操作';

$lang->mr->approvalStatus     = '审核状态';
$lang->mr->approvalStatusList = array();
$lang->mr->approvalStatusList['notReviewed'] = '未评审';
$lang->mr->approvalStatusList['approved']    = '已通过';
$lang->mr->approvalStatusList['rejected']    = '已拒绝';

$lang->mr->notApproved  = '审核拒绝的';
$lang->mr->assignedToMe = '指派给我';
$lang->mr->createdByMe  = '由我创建';

$lang->mr->statusList = array();
$lang->mr->statusList['all']    = '全部';
$lang->mr->statusList['opened'] = '开放中';
$lang->mr->statusList['merged'] = '已合并';
$lang->mr->statusList['closed'] = '已关闭';

$lang->mr->mergeStatusList = array();
$lang->mr->mergeStatusList['unchecked']            = '未检查';
$lang->mr->mergeStatusList['checking']             = '检查中';
$lang->mr->mergeStatusList['can_be_merged']        = '可合并';
$lang->mr->mergeStatusList['cannot_be_merged']     = '不可自动合并';
$lang->mr->mergeStatusList['cannot_merge_by_fail'] = '不可合并,检查未通过';

$lang->mr->description       = '描述';
$lang->mr->confirmDelete     = '确认删除该合并请求吗？';
$lang->mr->sourceProject     = '源仓库';
$lang->mr->sourceBranch      = '源分支';
$lang->mr->targetProject     = '目标仓库';
$lang->mr->targetBranch      = '目标分支';
$lang->mr->noCompileJob      = '没有流水线任务';
$lang->mr->compileUnexecuted = '还未执行';

$lang->mr->notFound          = "此{$lang->mr->common}不存在。";
$lang->mr->toCreatedMessage  = "您提交的合并请求：<a href='%s'>%s</a> 流水线任务执行通过。";
$lang->mr->toReviewerMessage = "有一个合并请求：<a href='%s'>%s</a> 待审核。";
$lang->mr->failMessage       = "您提交的合并请求：<a href='%s'>%s</a> 流水线任务执行失败，查看执行结果。";
$lang->mr->storySummary      = "本页共 <strong>%s</strong> 个" . $lang->SRCommon;

$lang->mr->apiError = new stdclass;
$lang->mr->apiError->createMR = "通过API创建合并请求失败，失败原因：%s";
$lang->mr->apiError->sudo     = "无法以当前用户绑定的GitLab账户进行操作，失败原因：%s";

$lang->mr->createFailedFromAPI = "创建合并请求失败。";
$lang->mr->hasSameOpenedMR     = "存在重复并且未关闭的合并请求: ID%u";
$lang->mr->accessGitlabFailed  = "当前无法连接到GitLab服务器。";
$lang->mr->reopenSuccess       = "已重新打开合并请求。";
$lang->mr->closeSuccess        = "已关闭合并请求。";

$lang->mr->apiErrorMap[1] = "You can't use same project/branch for source and target";
$lang->mr->apiErrorMap[2] = "/Another open merge request already exists for this source branch: !([0-9]+)/";
$lang->mr->apiErrorMap[3] = "401 Unauthorized";
$lang->mr->apiErrorMap[4] = "403 Forbidden";
$lang->mr->apiErrorMap[5] = "/(pull request already exists for these targets).*/";
$lang->mr->apiErrorMap[6] = "Invalid PullRequest: There are no changes between the head and the base";
$lang->mr->apiErrorMap[7] = "/(user doesn't have access to repo).*/";
$lang->mr->apiErrorMap[8] = "/(git apply).*/";

$lang->mr->errorLang[1] = '源项目分支与目标项目分支不能相同';
$lang->mr->errorLang[2] = '存在另外一个同样的合并请求在源项目分支中: ID%u';
$lang->mr->errorLang[3] = '权限不足';
$lang->mr->errorLang[4] = '权限不足';
$lang->mr->errorLang[5] = '存在另外一个同样的合并请求在源项目分支中';
$lang->mr->errorLang[6] = '源项目分支与目标项目分支不能相同';
$lang->mr->errorLang[7] = '您无权合并改版本库';
$lang->mr->errorLang[8] = '当前源分支和目标分支无法合并';

$lang->mr->from = "从";
$lang->mr->to   = "合并到";
$lang->mr->at   = "于";

$lang->mr->pipeline         = "流水线";
$lang->mr->pipelineSuccess  = "已通过";
$lang->mr->pipelineFailed   = "未通过";
$lang->mr->pipelineCanceled = "已取消";
$lang->mr->pipelineUnknown  = "未知";

$lang->mr->pipelineStatus = array();
$lang->mr->pipelineStatus['success']  = "已通过";
$lang->mr->pipelineStatus['failed']   = "未通过";
$lang->mr->pipelineStatus['canceled'] = "已取消";

$lang->mr->MRHasConflicts = "是否存在冲突";
$lang->mr->hasConflicts   = "存在冲突或等待提交";
$lang->mr->hasNoConflict  = "可以合并";
$lang->mr->acceptMR       = "合并";
$lang->mr->mergeFailed    = "无法合并，请核对合并请求状态";
$lang->mr->mergeSuccess   = "已成功合并";

$lang->mr->todomessage = "项目中指派给你了";

/**
 * Merge Command Document.
 *
 * %s source_project::http_url_to_repo
 * %s mr::source_branch
 * %s source_project::path_with_namespace . '-' . mr::source_branch
 * %s mr::target_branch
 * %s source_project::path_with_namespace . '-' . mr::source_branch
 * %s mr::target_branch
 */
$lang->mr->commandDocument = <<< EOD
<div class='detail-title'>在本地检出、审核和手动合并</div>
<div class='detail-content'>
  <p><blockquote>提示：您在本地合并完成后，该合并请求将自动更新为已合并状态。</blockquote></p>
  <p>
    第 1 步. 切换到目标项目所在目录，获取并查看此合并请求的分支
    <pre>
    git fetch "%s" %s
    git checkout -b "%s" FETCH_HEAD</pre>
  </p>
  <p>
    第 2 步. 在本地查看更改，如使用<code>git log</code>等命令
  </p>
  <p>
    第 3 步. 合并分支并解决出现的任何冲突
    <pre>
    git fetch origin
    git checkout "%s"
    git merge --no-ff "%s"</pre>
  </p>
  <p>
    第 4 步. 将合并结果推送到Git
    <pre>
    git push origin "%s" </pre>
  </p>
</div>
EOD;

$lang->mr->noChanges = "目前在这个合并请求的源分支中没有变化，请推送新的提交或使用不同的分支。";

$lang->mr->linkTask          = "关联任务";
$lang->mr->unlinkTask        = "移除任务";
$lang->mr->linkedTasks       = '任务';
$lang->mr->unlinkedTasks     = '未关联任务';
$lang->mr->confirmUnlinkTask = "您确认移除该任务吗？";
$lang->mr->taskSummary       = "本页共 <strong>%s</strong> 个任务";
$lang->mr->notDelbranch      = "源分支为受保护分支时不可删除";
$lang->mr->addForApp         = "该服务器下没有项目，是否前往添加？";

$lang->mr->featureBar['browse']['all']      = $lang->mr->statusList['all'];
$lang->mr->featureBar['browse']['opened']   = $lang->mr->statusList['opened'];
$lang->mr->featureBar['browse']['merged']   = $lang->mr->statusList['merged'];
$lang->mr->featureBar['browse']['closed']   = $lang->mr->statusList['closed'];
$lang->mr->featureBar['browse']['assignee'] = $lang->mr->assignedToMe;
$lang->mr->featureBar['browse']['creator']  = $lang->mr->createdByMe;
