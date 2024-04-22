<?php
$lang->mr = new stdclass;
$lang->mr->common       = "合併請求";
$lang->mr->view         = "概況";
$lang->mr->create       = "創建{$lang->mr->common}";
$lang->mr->apiCreate    = "介面：創建{$lang->mr->common}";
$lang->mr->browse       = "瀏覽{$lang->mr->common}";
$lang->mr->list         = $lang->mr->browse;
$lang->mr->edit         = "編輯{$lang->mr->common}";
$lang->mr->delete       = "刪除{$lang->mr->common}";
$lang->mr->accept       = "合併請求";
$lang->mr->source       = '源項目分支';
$lang->mr->target       = '目標項目分支';
$lang->mr->viewDiff     = '比對代碼';
$lang->mr->diff         = '比對代碼';
$lang->mr->viewInGitlab = '在GitLab查看';
$lang->mr->link         = '關聯需求、Bug、任務';
$lang->mr->createAction = '%s, 由 <strong>%s</strong> 提交了 <a href="%s">合併請求</a>。';

$lang->mr->linkList  = '瀏覽關聯需求、Bug、任務';
$lang->mr->linkStory = '關聯需求';
$lang->mr->linkBug   = '關聯Bug';
$lang->mr->linkTask  = '關聯任務';
$lang->mr->unlink    = '取消關聯需求、Bug、任務';
$lang->mr->addReview = '添加評審';

$lang->mr->id          = 'ID';
$lang->mr->mriid       = "MR原始ID";
$lang->mr->title       = '名稱';
$lang->mr->status      = '狀態';
$lang->mr->author      = '創建人';
$lang->mr->assignee    = '指派給';
$lang->mr->reviewer    = '評審人';
$lang->mr->mergeStatus = '是否可合併';
$lang->mr->commits     = '提交數';
$lang->mr->changes     = '更改數';
$lang->mr->gitlabID    = 'GitLab';
$lang->mr->repoID      = '版本庫';
$lang->mr->jobID       = '構建任務';
$lang->mr->commitLogs  = '提交記錄';

$lang->mr->canMerge  = "可合併";
$lang->mr->cantMerge = "不可合併";

$lang->mr->approval = '評審';
$lang->mr->approve  = '通過';
$lang->mr->reject   = '拒絶';
$lang->mr->close    = '關閉';
$lang->mr->reopen   = '重新打開';

$lang->mr->reviewType     = '評審類型';
$lang->mr->reviewTypeList = array();
$lang->mr->reviewTypeList['bug']  = 'Bug';
$lang->mr->reviewTypeList['task'] = '任務';

$lang->mr->approvalResult     = '評審意見';
$lang->mr->approvalResultList = array();
$lang->mr->approvalResultList['approve'] = '通過';
$lang->mr->approvalResultList['reject']  = '拒絶';

$lang->mr->needApproved       = '需要通過評審才能合併';
$lang->mr->needCI             = '需要通過構建才能合併';
$lang->mr->removeSourceBranch = '合併後刪除源分支';

$lang->mr->repeatedOperation = '請勿重複操作';

$lang->mr->approvalStatus     = '審核狀態';
$lang->mr->approvalStatusList = array();
$lang->mr->approvalStatusList['notReviewed'] = '未評審';
$lang->mr->approvalStatusList['approved']    = '已通過';
$lang->mr->approvalStatusList['rejected']    = '已拒絶';

$lang->mr->notApproved  = '審核拒絶的';
$lang->mr->assignedToMe = '指派給我';
$lang->mr->createdByMe  = '由我創建';

$lang->mr->statusList = array();
$lang->mr->statusList['all']    = '所有';
$lang->mr->statusList['opened'] = '開放中';
$lang->mr->statusList['merged'] = '已合併';
$lang->mr->statusList['closed'] = '已關閉';

$lang->mr->mergeStatusList = array();
$lang->mr->mergeStatusList['checking']             = '檢查中';
$lang->mr->mergeStatusList['can_be_merged']        = '可合併';
$lang->mr->mergeStatusList['cannot_be_merged']     = '不可自動合併';
$lang->mr->mergeStatusList['cannot_merge_by_fail'] = '不可合併,檢查未通過';

$lang->mr->description       = '描述';
$lang->mr->confirmDelete     = '確認刪除該合併請求嗎？';
$lang->mr->sourceProject     = '源項目';
$lang->mr->sourceBranch      = '源分支';
$lang->mr->targetProject     = '目標項目';
$lang->mr->targetBranch      = '目標分支';
$lang->mr->noCompileJob      = '沒有構建任務';
$lang->mr->compileUnexecuted = '還未執行';

$lang->mr->notFound          = "此{$lang->mr->common}不存在。";
$lang->mr->toCreatedMessage  = "您提交的合併請求：<a href='%s'>%s</a> 構建任務執行通過。";
$lang->mr->toReviewerMessage = "有一個合併請求：<a href='%s'>%s</a> 待審核。";
$lang->mr->failMessage       = "您提交的合併請求：<a href='%s'>%s</a> 構建任務執行失敗，查看執行結果。";
$lang->mr->storySummary      = "本頁共 <strong>%s</strong> 個" . $lang->SRCommon;

$lang->mr->apiError = new stdclass;
$lang->mr->apiError->createMR = "通過API創建合併請求失敗，失敗原因：%s";
$lang->mr->apiError->sudo     = "無法以當前用戶綁定的GitLab賬戶進行操作，失敗原因：%s";

$lang->mr->createFailedFromAPI = "創建合併請求失敗。";
$lang->mr->accessGitlabFailed  = "當前無法連接到GitLab伺服器。";
$lang->mr->reopenSuccess       = "已重新打開合併請求。";
$lang->mr->closeSuccess        = "已關閉合併請求。";

$lang->mr->apiErrorMap[1] = "You can't use same project/branch for source and target";
$lang->mr->apiErrorMap[2] = "/Another open merge request already exists for this source branch: !([0-9]+)/";

$lang->mr->errorLang[1] = '源項目分支與目標項目分支不能相同';
$lang->mr->errorLang[2] = '存在另外一個同樣的合併請求在源項目分支中: !%u';

$lang->mr->from = "從";
$lang->mr->to   = "合併到";
$lang->mr->at   = "于";

$lang->mr->pipeline        = "流水綫";
$lang->mr->pipelineSuccess = "已通過";
$lang->mr->pipelineFailed  = "未通過";
$lang->mr->pipelineCanceled = "已取消";
$lang->mr->pipelineUnknown = "未知";

$lang->mr->pipelineStatus = array();
$lang->mr->pipelineStatus['success']  = "已通過";
$lang->mr->pipelineStatus['failed']   = "未通過";
$lang->mr->pipelineStatus['canceled'] = "已取消";

$lang->mr->MRHasConflicts = "是否存在衝突";
$lang->mr->hasConflicts   = "存在衝突或等待提交";
$lang->mr->hasNoConflict  = "可以合併";
$lang->mr->acceptMR       = "合併";
$lang->mr->mergeFailed    = "無法合併，請核對合併請求狀態";
$lang->mr->mergeSuccess   = "已成功合併";

$lang->mr->todomessage = "項目中指派給你了";

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
<div class='detail-title'>在本地檢出、審核和手動合併</div>
<div class='detail-content'>
  <p><blockquote>提示：您在本地合併完成後，該合併請求將自動更新為已合併狀態。</blockquote></p>
  <p>
    第 1 步. 切換到目標項目所在目錄，獲取並查看此合併請求的分支
    <pre>
    git fetch "%s" %s
    git checkout -b "%s" FETCH_HEAD</pre>
  </p>
  <p>
    第 2 步. 在本地查看更改，如使用<code>git log</code>等命令
  </p>
  <p>
    第 3 步. 合併分支並解決出現的任何衝突
    <pre>
    git fetch origin
    git checkout "%s"
    git merge --no-ff "%s"</pre>
  </p>
  <p>
    第 4 步. 將合併結果推送到GitLab
    <pre> git push origin "%s" </pre>
  </p>
</div>
EOD;

$lang->mr->noChanges = "目前在這個合併請求的源分支中沒有變化，請推送新的提交或使用不同的分支。";

$lang->mr->linkTask          = "關聯任務";
$lang->mr->unlinkTask        = "移除任務";
$lang->mr->linkedTasks       = '任務';
$lang->mr->unlinkedTasks     = '未關聯任務';
$lang->mr->confirmUnlinkTask = "您確認移除該任務嗎？";
$lang->mr->taskSummary       = "本頁共 <strong>%s</strong> 個任務";
