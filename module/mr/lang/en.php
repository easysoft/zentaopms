<?php
$lang->mr->common       = "Merge Request";
$lang->mr->create       = "create";
$lang->mr->browse       = "browse";
$lang->mr->list         = "list";
$lang->mr->edit         = "edit";
$lang->mr->delete       = "delete";
$lang->mr->view         = "view";
$lang->mr->source       = 'source';
$lang->mr->target       = 'target';
$lang->mr->viewDiff     = 'view diff';
$lang->mr->viewInGitlab = 'view GitLab';

$lang->mr->id          = 'ID';
$lang->mr->mriid       = "MR原始ID";
$lang->mr->name        = 'name';
$lang->mr->status      = 'status';
$lang->mr->author      = 'author';
$lang->mr->assignee    = 'assignee';
$lang->mr->reviewer    = 'reviewer';
$lang->mr->mergeStatus = 'merge status';
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

$lang->mr->description   = 'description';
$lang->mr->confirmDelete = 'Are you sure to delete this merge request?';
$lang->mr->sourceProject = 'source project';
$lang->mr->sourceBranch  = 'source branch';
$lang->mr->targetProject = 'target project';
$lang->mr->targetBranch  = 'target branch';

$lang->mr->usersTips = 'Tip: If you cannot choose the designator and reviewer, please go to the GitLab page to bind the user first.';
$lang->mr->notFound  = "Merge Reqest does not exist!";

$lang->mr->apiError = new stdclass;
$lang->mr->apiError->createMR = "Failed to create a merge request through API. Reason for failure:%s";
$lang->mr->apiError->sudo     = "Unable to operate with the GitLab account bound to the current user, the reason for the failure:%s";

$lang->mr->createFailedFromAPI = "Failed to create Merge Request.";
$lang->mr->accessGitlabFailed  = "Unable to connect to the GitLab server.";

$lang->mr->from = "from";
$lang->mr->to   = "to";
$lang->mr->at   = "at";

$lang->mr->pipeline        = "Pipline";
$lang->mr->pipelineSuccess = "Success";
$lang->mr->pipelineFailed  = "Failed";
$lang->mr->pipelineCancled = "Cancled";
$lang->mr->pipelineUnknown = "Unknown";

$lang->mr->pipelineStatus = array();
$lang->mr->pipelineStatus['success']  = "success";
$lang->mr->pipelineStatus['failed']   = "failed";
$lang->mr->pipelineStatus['canceled'] = "canceled";

$lang->mr->MRHasConflicts = "Merge Request has a conflict";
$lang->mr->hasConflicts   = "Merge Request conflict";
$lang->mr->hasNoConflict  = "Can merge requests";
$lang->mr->mergeByManual  = "This merge request can be merged manually, please refer to";
$lang->mr->commandLine    = "Merge Request command";
$lang->mr->acceptMR       = "Accept Merge request ";
$lang->mr->mergeFailed    = "Unable to merge request, please check the merge request status";
$lang->mr->mergeSuccess   = "Merge Request Successfully";

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
<div class='detail-title'>Check, Review and Merge manually locally</div>
<div class='detail-content'>
  <p>
    step 1. Get and view the branches of this merge request.
    <pre>
    git fetch "%s" %s
    git checkout -b "%s" FETCH_HEAD</pre>
  </p>
  <p>
    step 2. View changes locally
  </p>
  <p>
    step 3. Merge branches and resolve any conflicts that arise
    <pre>
    git fetch origin
    git checkout "%s"
    git merge --no-ff "%s"</pre>
  </p>
  <p>
    step 4. Push the merge  to GitLab
    <pre> git push origin "%s" </pre>
  </p>
</div>
EOD;
