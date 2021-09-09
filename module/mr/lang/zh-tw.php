<?php
$lang->mr = new stdclass;
$lang->mr->common       = "合併請求";
$lang->mr->create       = "創建{$lang->mr->common}";
$lang->mr->browse       = "瀏覽{$lang->mr->common}";
$lang->mr->list         = $lang->mr->browse;
$lang->mr->edit         = "編輯{$lang->mr->common}";
$lang->mr->delete       = "刪除{$lang->mr->common}";
$lang->mr->view         = "{$lang->mr->common}詳情";
$lang->mr->accept       = "合併請求";
$lang->mr->source       = '源項目分支';
$lang->mr->target       = '目標項目分支';
$lang->mr->viewDiff     = '比對代碼';
$lang->mr->viewInGitlab = '在GitLab查看';

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

$lang->mr->statusList = array();
$lang->mr->statusList['opened'] = '開放中';
$lang->mr->statusList['closed'] = '已關閉';
$lang->mr->statusList['merged'] = '已合併';

$lang->mr->mergeStatusList = array();
$lang->mr->mergeStatusList['checking']         = '檢查中';
$lang->mr->mergeStatusList['can_be_merged']    = '可合併';
$lang->mr->mergeStatusList['cannot_be_merged'] = '不可自動合併';

$lang->mr->description   = '描述';
$lang->mr->confirmDelete = '確認刪除該合併請求嗎？';
$lang->mr->sourceProject = '源項目';
$lang->mr->sourceBranch  = '源分支';
$lang->mr->targetProject = '目標項目';
$lang->mr->targetBranch  = '目標分支';

$lang->mr->usersTips = '提示：如果無法選擇指派人，請先前往GitLab頁面綁定用戶。';
$lang->mr->notFound  = "此{$lang->mr->common}不存在。";

$lang->mr->apiError = new stdclass;
$lang->mr->apiError->createMR = "通過API創建合併請求失敗，失敗原因：%s";
$lang->mr->apiError->sudo     = "無法以當前用戶綁定的GitLab賬戶進行操作，失敗原因：%s";

$lang->mr->createFailedFromAPI = "創建合併請求失敗。";
$lang->mr->accessGitlabFailed  = "當前無法連接到GitLab伺服器。";

$lang->mr->from = "從";
$lang->mr->to   = "合併到";
$lang->mr->at   = "于";

$lang->mr->pipeline        = "流水綫";
$lang->mr->pipelineSuccess = "已通過";
$lang->mr->pipelineFailed  = "未通過";
$lang->mr->pipelineCancled = "已取消";
$lang->mr->pipelineUnknown = "未知";

$lang->mr->pipelineStatus = array();
$lang->mr->pipelineStatus['success']  = "已通過";
$lang->mr->pipelineStatus['failed']   = "未通過";
$lang->mr->pipelineStatus['canceled'] = "已取消";

$lang->mr->MRHasConflicts = "是否存在衝突";
$lang->mr->hasConflicts   = "存在衝突或等待提交";
$lang->mr->hasNoConflict  = "可以合併";
$lang->mr->mergeByManual  = "此合併請求可以手動合併，請使用以下";
$lang->mr->commandLine    = "合併命令";
$lang->mr->acceptMR       = "合併";
$lang->mr->mergeFailed    = "無法合併，請核對合併請求狀態";
$lang->mr->mergeSuccess   = "已成功合併";

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
  <p><strong>注意：您在本地合併後此合併請求將變為不可合併狀態，需要刪除此合併請求或者提交新的代碼。</strong></p>
  <p>
    第 1 步. 獲取並查看此合併請求的分支
    <pre>
    git fetch "%s" %s
    git checkout -b "%s" FETCH_HEAD</pre>
  </p>
  <p>
    第 2 步. 在本地查看更改
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
