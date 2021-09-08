<?php
$lang->mr = new stdclass;
$lang->mr->common       = "Merge Request";
$lang->mr->create       = "Create";
$lang->mr->browse       = "Browse";
$lang->mr->list         = "List";
$lang->mr->edit         = "Edit";
$lang->mr->delete       = "Delete";
$lang->mr->view         = "View";
$lang->mr->accept       = "Accept";
$lang->mr->source       = 'source';
$lang->mr->target       = 'target';
$lang->mr->viewDiff     = 'View diff';
$lang->mr->viewInGitlab = 'View in GitLab';

$lang->mr->id          = 'ID';
$lang->mr->mriid       = "raw MR ID";
$lang->mr->title        = 'Name';
$lang->mr->status      = 'Status';
$lang->mr->author      = 'Author';
$lang->mr->assignee    = 'Assignee';
$lang->mr->reviewer    = 'Reviewer';
$lang->mr->mergeStatus = 'Merge status';
$lang->mr->commits     = 'commits';
$lang->mr->changes     = 'changes';

$lang->mr->statusList = array();
$lang->mr->statusList['opened'] = 'opened';
$lang->mr->statusList['closed'] = 'closed';
$lang->mr->statusList['merged'] = 'merged';

$lang->mr->mergeStatusList = array();
$lang->mr->mergeStatusList['checking']         = 'checking';
$lang->mr->mergeStatusList['can_be_merged']    = 'can be merged';
$lang->mr->mergeStatusList['cannot_be_merged'] = 'cannot be merged';

$lang->mr->description   = 'Description';
$lang->mr->confirmDelete = 'Are you sure to delete this merge request?';
$lang->mr->sourceProject = 'Source project';
$lang->mr->sourceBranch  = 'Source branch';
$lang->mr->targetProject = 'Target project';
$lang->mr->targetBranch  = 'Target branch';

$lang->mr->usersTips = 'Tip: If you cannot choose the assignee and reviewer, please go to the GitLab page to bind the user first.';
$lang->mr->notFound  = "Merge Request does not exist!";

$lang->mr->apiError = new stdclass;
$lang->mr->apiError->createMR = "Failed to create a merge request through API. Reason: %s";
$lang->mr->apiError->sudo     = "Unable to operate with the GitLab account bound to the current user. Reason: %s";

$lang->mr->createFailedFromAPI = "Failed to create Merge Request.";
$lang->mr->accessGitlabFailed  = "Unable to connect to the GitLab server.";

$lang->mr->from = "from";
$lang->mr->to   = "to";
$lang->mr->at   = "at";

$lang->mr->pipeline        = "Pipeline";
$lang->mr->pipelineSuccess = "Success";
$lang->mr->pipelineFailed  = "Failed";
$lang->mr->pipelineCancled = "Canceled";
$lang->mr->pipelineUnknown = "Unknown";

$lang->mr->pipelineStatus = array();
$lang->mr->pipelineStatus['success']  = "success";
$lang->mr->pipelineStatus['failed']   = "failed";
$lang->mr->pipelineStatus['canceled'] = "canceled";

$lang->mr->MRHasConflicts = "Merge Request has a conflict";
$lang->mr->hasConflicts   = "There are merge conflicts or wait for push";
$lang->mr->hasNoConflict  = "Can merge";
$lang->mr->mergeByManual  = "This merge request can be merged manually, please refer to";
$lang->mr->commandLine    = "Merge Request command";
$lang->mr->acceptMR       = "Accept Merge request ";
$lang->mr->mergeFailed    = "Unable to merge request, please check the merge request status";
$lang->mr->mergeSuccess   = "Merge Request Successfully";

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
<div class='detail-title'>Check out, review and merge locally</div>
<div class='detail-content'>
  <p><strong>Note: This merge request status will be changed after you merge locally and you will need to delete this merge request or submit new code.</strong></p>
  <p>
    step 1. Fetch and check out the branch for this merge request
    <pre>
    git fetch "%s" %s
    git checkout -b "%s" FETCH_HEAD</pre>
  </p>
  <p>
    step 2. Review the changes locally
  </p>
  <p>
    step 3. Merge the branch and fix any conflicts that come up
    <pre>
    git fetch origin
    git checkout "%s"
    git merge --no-ff "%s"</pre>
  </p>
  <p>
    step 4. Push the result of the merge to GitLab
    <pre> git push origin "%s" </pre>
  </p>
</div>
EOD;
