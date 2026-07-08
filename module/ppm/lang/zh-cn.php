<?php
$lang->ppm->common            = "评审请求";
$lang->ppm->server            = "服务器";
$lang->ppm->hostID            = "服务器";
$lang->ppm->view              = "概况";
$lang->ppm->viewAction        = "{$lang->ppm->common}详情";
$lang->ppm->create            = "提交合并请求";
$lang->ppm->mirrorRepoTip     = '当前代码库为镜像代码库，将代码库重新导入为"可读、可写、可管理"的模式即可使用代码评审功能。';
$lang->ppm->hasMirrorRepoTip = '当前已关联代码库包含镜像代码库，镜像代码库无法使用代码评审功能，不支持创建合并请求';
$lang->ppm->apiCreate         = "接口：创建{$lang->ppm->common}";
$lang->ppm->browse            = "浏览{$lang->ppm->common}";
$lang->ppm->browseAction      = "{$lang->ppm->common}列表";
$lang->ppm->list              = $lang->ppm->browse;
$lang->ppm->edit              = "编辑{$lang->ppm->common}";
$lang->ppm->delete            = "删除{$lang->ppm->common}";
$lang->ppm->accept            = "合并请求";
$lang->ppm->source            = '源项目分支';
$lang->ppm->target            = '目标项目分支';
$lang->ppm->viewDiff          = '比对代码';
$lang->ppm->diff              = '比对代码';
$lang->ppm->viewInGit         = '在应用中查看';
$lang->ppm->link              = '关联需求、Bug、任务';
$lang->ppm->createAction      = '%s, 由 <strong>%s</strong> 提交了 <a href="%s">合并请求</a>。';
$lang->ppm->editAction        = '%s, 由 <strong>%s</strong> 编辑了 <a href="%s">合并请求</a>。';
$lang->ppm->removeAction      = '%s, 由 <strong>%s</strong> 删除了 <a href="%s">合并请求</a>。';
$lang->ppm->submitType        = '提交方式';
$lang->ppm->linkedObject      = '关联项';
$lang->ppm->object            = '对象';
$lang->ppm->mergeInfo         = '合并概览';
$lang->ppm->locateView        = '查看详情';
$lang->ppm->codeConflict      = '代码冲突检查';
$lang->ppm->hasConflict       = '是否有代码冲突';
$lang->ppm->request           = '要求';
$lang->ppm->AIReview          = 'AI评审';
$lang->ppm->AICodeScore       = '代码评分';
$lang->ppm->AISevereIssue     = '高危问题';
$lang->ppm->AIOrdinaryIssue   = '一般问题';
$lang->ppm->manualReview      = '人工评审';
$lang->ppm->approvalReviewer  = '评审人数';
$lang->ppm->doneReviewer      = '已完成评审数量';
$lang->ppm->codeScan          = '代码扫描';
$lang->ppm->scanSevereIssue   = '高危问题';
$lang->ppm->scanOrdinaryIssue = '一般问题';
$lang->ppm->scanPassRate      = '安全门禁通过率';
$lang->ppm->runResult         = '执行结果';
$lang->ppm->basicInfo         = '基本信息';
$lang->ppm->sourceBranch      = '源分支';
$lang->ppm->targetBranch      = '目标分支';
$lang->ppm->filePath          = '文件路径';
$lang->ppm->conflictFiles     = '冲突文件';
$lang->ppm->changeFiles       = '变更的文件';
$lang->ppm->issueList         = '问题清单';
$lang->ppm->add               = '添加';
$lang->ppm->addReviewer       = '添加评审人';
$lang->ppm->reviewStatus      = '审批状态';
$lang->ppm->review            = '评审';
$lang->ppm->decision          = '评审结果';
$lang->ppm->opinion           = '评审意见';
$lang->ppm->merge             = '合并' . $lang->ppm->common;
$lang->ppm->assignedTo        = '指派给';

$lang->ppm->opinionPlaceholder = '请输入评审意见';

$lang->ppm->action = new stdclass();
$lang->ppm->action->synced   = '$date, 由 <strong>$actor</strong> 同步了合并请求。';
$lang->ppm->action->imported = '$date, 由 <strong>$actor</strong> 导入了合并请求。';

$lang->ppm->linkList   = '浏览关联需求、Bug、任务';
$lang->ppm->linkStory  = '关联需求';
$lang->ppm->linkBug    = '关联Bug';
$lang->ppm->linkTask   = '关联任务';
$lang->ppm->unlinkTask = '移除任务';
$lang->ppm->unlink     = '取消关联需求、Bug、任务';
$lang->ppm->addReview  = '添加评审';

$lang->ppm->id          = 'ID';
$lang->ppm->mriid       = "MR原始ID";
$lang->ppm->title       = '名称';
$lang->ppm->status      = '状态';
$lang->ppm->author      = '提交人';
$lang->ppm->createdDate = '提交时间';
$lang->ppm->assignee    = '指派给';
$lang->ppm->reviewer    = '评审人';
$lang->ppm->mergeStatus = '是否可合并';
$lang->ppm->commits     = '提交数';
$lang->ppm->changes     = '更改数';
$lang->ppm->gitlabID    = 'GitLab';
$lang->ppm->repoID      = '版本库';
$lang->ppm->jobID       = '流水线任务';
$lang->ppm->commitLogs  = '提交记录';
$lang->ppm->execJob     = '执行';
$lang->ppm->execJobTip  = '手动执行流水线任务';
$lang->ppm->repo        = '代码库';

$lang->ppm->canMerge  = "可合并";
$lang->ppm->cantMerge = "不可合并";

$lang->ppm->approval = '评审';
$lang->ppm->approve  = '通过';
$lang->ppm->reject   = '拒绝';
$lang->ppm->close    = '关闭' . $lang->ppm->common;
$lang->ppm->reopen   = '重新打开' . $lang->ppm->common;

$lang->ppm->reviewType     = '评审类型';
$lang->ppm->reviewTypeList = array();
$lang->ppm->reviewTypeList['bug']  = 'Bug';
$lang->ppm->reviewTypeList['task'] = '任务';

$lang->ppm->approvalResult     = '评审意见';
$lang->ppm->approvalResultList = array();
$lang->ppm->approvalResultList['approved'] = '通过';
$lang->ppm->approvalResultList['rejected'] = '拒绝';

$lang->ppm->needApproved       = '需要通过评审才能合并';
$lang->ppm->needCI             = '需要通过流水线才能合并';
$lang->ppm->removeSourceBranch = '合并后删除源分支';
$lang->ppm->squash             = '合并提交记录';
$lang->ppm->triggeredCI        = '目标分支或流水线变动，触发流水线执行。';
$lang->ppm->acceptTip          = '评审通过后才能合并';
$lang->ppm->conflictsTip       = '该合并请求存在冲突，无法评审通过';
$lang->ppm->noChangesTip       = '源分支与目标分支没有变化，无法评审通过';
$lang->ppm->compileTip         = '该合并请求流水线构建未成功，无法评审通过';
$lang->ppm->notifyTip          = '存在冲突或分支间没有变化，无法评审通过';
$lang->ppm->branchUpdateTip    = '分支有更新，可执行流水线';
$lang->ppm->draftTips          = '合并请求处于草稿状态，不可合并。';

$lang->ppm->repeatedOperation = '请勿重复操作';

$lang->ppm->approvalStatus     = '审批流状态';
$lang->ppm->approvalStatusList = array();
$lang->ppm->approvalStatusList['pending']    = '待评审';
$lang->ppm->approvalStatusList['inProgress'] = '评审中';
$lang->ppm->approvalStatusList['approved']   = '已通过';
$lang->ppm->approvalStatusList['rejected']   = '已拒绝';

$lang->ppm->notApproved  = '审核拒绝的';
$lang->ppm->assignedToMe = '指派给我';
$lang->ppm->createdByMe  = '由我创建';

$lang->ppm->statusList = array();
$lang->ppm->statusList['all']    = '全部';
$lang->ppm->statusList['opened'] = '开放中';
$lang->ppm->statusList['merged'] = '已合并';
$lang->ppm->statusList['closed'] = '已关闭';

$lang->ppm->mergeStatusList = array();
$lang->ppm->mergeStatusList['unchecked']            = '未检查';
$lang->ppm->mergeStatusList['checking']             = '检查中';
$lang->ppm->mergeStatusList['can_be_merged']        = '可合并';
$lang->ppm->mergeStatusList['cannot_be_merged']     = '不可合并';
$lang->ppm->mergeStatusList['cannot_merge_by_fail'] = '不可合并,检查未通过';

$lang->ppm->description       = '描述';
$lang->ppm->confirmDelete     = '确认删除该合并请求吗？';
$lang->ppm->sourceProject     = '源仓库';
$lang->ppm->sourceBranch      = '源分支';
$lang->ppm->targetProject     = '目标仓库';
$lang->ppm->targetBranch      = '目标分支';
$lang->ppm->noCompileJob      = '没有流水线任务';
$lang->ppm->compileUnexecuted = '还未执行';
$lang->ppm->compileID         = '构建任务';
$lang->ppm->compileStatus     = '构建结果';

$lang->ppm->notFound          = "此{$lang->ppm->common}不存在。";
$lang->ppm->toCreatedMessage  = "您提交的合并请求：<a href='%s'>%s</a> 流水线任务执行通过。";
$lang->ppm->toReviewerMessage = "有一个合并请求：<a href='%s'>%s</a> 待审核。";
$lang->ppm->failMessage       = "您提交的合并请求：<a href='%s'>%s</a> 流水线任务执行失败，查看执行结果。";
$lang->ppm->storySummary      = "本页共 <strong>%s</strong> 个" . $lang->SRCommon;

$lang->ppm->apiError = new stdclass;
$lang->ppm->apiError->createMR      = "通过API创建合并请求失败，失败原因：%s";
$lang->ppm->apiError->sudo          = "无法以当前用户绑定的GitLab账户进行操作，失败原因：%s";
$lang->ppm->apiError->emptyResponse = "API请求的对象不存在或者API请求失败。";
$lang->ppm->apiError->notFound      = "API请求的对象不存在，可能已被服务器删除。";

$lang->ppm->createFailedFromAPI  = "创建合并请求失败。";
$lang->ppm->hasSameOpenedMR      = "存在重复并且未关闭的合并请求: ID%u";
$lang->ppm->accessGitlabFailed   = "当前无法连接到GitLab服务器。";
$lang->ppm->reopenSuccess        = "已重新打开合并请求。";
$lang->ppm->closeSuccess         = "已关闭合并请求。";
$lang->ppm->unsupportedFeature   = "暂不支持该功能。";
$lang->ppm->checkSourceBranch    = '源分支允许合并到的目标分支类型：%s';
$lang->ppm->checkTargetBranch    = '目标分支允许合并的源分支类型：%s';
$lang->ppm->checkConflicts       = '检测到代码冲突，请先在本地解决冲突后再创建合并请求。';
$lang->ppm->checkReviewers       = '评审人必须包含%s';
$lang->ppm->sourceBranchNotExist = '源分支不存在';
$lang->ppm->targetBranchNotExist = '目标分支不存在';

$lang->ppm->apiErrorMap[1]  = "You can't use same project/branch for source and target";
$lang->ppm->apiErrorMap[2]  = "/Another open merge request already exists for this source branch: !([0-9]+)/";
$lang->ppm->apiErrorMap[3]  = "401 Unauthorized";
$lang->ppm->apiErrorMap[4]  = "403 Forbidden";
$lang->ppm->apiErrorMap[5]  = "/(pull request already exists for these targets).*/";
$lang->ppm->apiErrorMap[6]  = "Invalid PullRequest: There are no changes between the head and the base";
$lang->ppm->apiErrorMap[7]  = "/(user doesn't have access to repo).*/";
$lang->ppm->apiErrorMap[8]  = "/(git apply).*/";
$lang->ppm->apiErrorMap[9]  = "a pull request for this target and source branch already exists";
$lang->ppm->apiErrorMap[10] = 'Internal error occurred';
$lang->ppm->apiErrorMap[11] = "The source branch doesn't contain any new commits";

$lang->ppm->errorLang[1]  = '源项目分支与目标项目分支不能相同';
$lang->ppm->errorLang[2]  = '存在另外一个同样的合并请求在源项目分支中: ID%u';
$lang->ppm->errorLang[3]  = '权限不足';
$lang->ppm->errorLang[4]  = '权限不足';
$lang->ppm->errorLang[5]  = '存在另外一个同样的合并请求在源项目分支中';
$lang->ppm->errorLang[6]  = '源项目分支与目标项目分支不能相同';
$lang->ppm->errorLang[7]  = '您无权合并改版本库';
$lang->ppm->errorLang[8]  = '当前源分支和目标分支无法合并';
$lang->ppm->errorLang[9]  = '已存在相同的合并请求';
$lang->ppm->errorLang[10] = '服务器错误';
$lang->ppm->errorLang[11] = '源分支不包含任何新的提交';

$lang->ppm->from = "从";
$lang->ppm->to   = "合并到";
$lang->ppm->at   = "于";

$lang->ppm->pipeline         = "流水线";
$lang->ppm->pipelineSuccess  = "已通过";
$lang->ppm->pipelineFailed   = "未通过";
$lang->ppm->pipelineCanceled = "已取消";
$lang->ppm->pipelineUnknown  = "未知";

$lang->ppm->pipelineStatus = array();
$lang->ppm->pipelineStatus['success']  = "成功";
$lang->ppm->pipelineStatus['failed']   = "失败";
$lang->ppm->pipelineStatus['canceled'] = "取消";

$lang->ppm->MRHasConflicts = "是否存在冲突";
$lang->ppm->hasConflicts   = "代码有冲突";
$lang->ppm->hasNoChanges   = "代码无变动";
$lang->ppm->hasNoConflict  = "可以合并";
$lang->ppm->acceptMR       = "合并";
$lang->ppm->mergeFailed    = "无法合并，请核对合并请求状态";
$lang->ppm->mergeSuccess   = "已成功合并";
$lang->ppm->refreshSuccess = '刷新成功';

$lang->ppm->todomessage = "项目中指派给你了";
$lang->ppm->squashHelp  = '对应git参数：--squash';

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
$lang->ppm->commandDocument = <<< EOD
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

$lang->ppm->noChanges = "目前在这个合并请求的源分支中没有变化，请推送新的提交或使用不同的分支。";

$lang->ppm->linkTask          = "关联任务";
$lang->ppm->unlinkTask        = "移除任务";
$lang->ppm->linkedTasks       = '任务';
$lang->ppm->unlinkedTasks     = '未关联任务';
$lang->ppm->confirmUnlinkTask = "您确认移除该任务吗？";
$lang->ppm->taskSummary       = "本页共 <strong>%s</strong> 个任务";
$lang->ppm->notDelbranch      = "源分支为受保护分支时不可删除";
$lang->ppm->addForApp         = "该服务器下没有项目，是否前往添加？";
$lang->ppm->checkSuccess      = '检查已通过，此分支允许合并';
$lang->ppm->checkFailed       = '检查未通过，此分支无法合并';
$lang->ppm->MRHistory         = "本次合并由 <strong>%s</strong> 于 <strong>%s</strong> 创建，申请将 <label class='label primary size-sm px-2 cursor-pointer' data-on='click' data-call='copy' data-params='event'>%s<icon class='icon-copy ml-1'/></label> 的 <strong>%s</strong> 次提交，合并到 <label class='label primary size-sm px-2 cursor-pointer' data-on='click' data-call='copy' data-params='event'>%s<icon class='icon-copy ml-1'/></label> 。";

$lang->ppm->checkStatusList = array();
$lang->ppm->checkStatusList['fail']    = '未通过';
$lang->ppm->checkStatusList['success'] = '已通过';
$lang->ppm->checkStatusList['wait']    = '待确认';

$lang->ppm->hasConflictList['yes'] = '是';
$lang->ppm->hasConflictList['no']  = '否';

$lang->ppm->featureBar['browse']['all']      = $lang->ppm->statusList['all'];
$lang->ppm->featureBar['browse']['opened']   = $lang->ppm->statusList['opened'];
$lang->ppm->featureBar['browse']['merged']   = $lang->ppm->statusList['merged'];
$lang->ppm->featureBar['browse']['closed']   = $lang->ppm->statusList['closed'];
$lang->ppm->featureBar['browse']['creator']  = $lang->ppm->createdByMe;

$lang->ppm->bug = new stdclass();
$lang->ppm->bug->title    = '名称';
$lang->ppm->bug->source   = '来源';
$lang->ppm->bug->type     = '类型';
$lang->ppm->bug->file     = '所属文件';
$lang->ppm->bug->severity = '严重程度';
$lang->ppm->bug->status   = '状态';

$lang->ppm->mergeTypeInfoList = array();
$lang->ppm->mergeTypeInfoList['merge']  = '此分支上的所有提交将通过合并提交的方式添加到基础分支。';
$lang->ppm->mergeTypeInfoList['squash'] = '此分支上的所有提交将合并为一个提交，并添加到基础分支。';
$lang->ppm->mergeTypeInfoList['rebase'] = '此分支上的所有提交将被变基并添加到基础分支。';
$lang->ppm->mergeTypeInfoList['fast']   = '此分支上的所有提交将直接添加到基础分支，而不产生合并提交,可能需要进行变基。';

$lang->ppm->notice = new stdclass();
$lang->ppm->notice->confirmClose                 = '是否确认关闭该合并请求？';
$lang->ppm->notice->confirmReopen                = '是否开启该合并请求？';
$lang->ppm->notice->fastNotice                   = '目标分支已有新提交，无法进行快速合并';
$lang->ppm->notice->sameBranch                   = '源分支与目标分支不能相同。';
$lang->ppm->notice->userNotAllowMerge            = '只允许以下用户合并：%s';
$lang->ppm->notice->userNotAllowCreate           = '只允许以下用户创建：%s';
$lang->ppm->notice->hasUnresolvedIssues          = '有未解决的问题，请先解决。';
$lang->ppm->notice->hasUnresolvedSpecifiedIssues = '有未解决的%s类型的问题，请先解决。';

$lang->ppm->featureBar['view']['all']   = '全部';
$lang->ppm->featureBar['view']['story'] = '需求';
$lang->ppm->featureBar['view']['task']  = '任务';
$lang->ppm->featureBar['view']['bug']   = '缺陷';

$lang->ppm->issueSourceList = array();
$lang->ppm->issueSourceList['code']  = '代码';
$lang->ppm->issueSourceList['scan']  = '扫描';
