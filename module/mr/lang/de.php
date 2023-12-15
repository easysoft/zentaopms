<?php
$lang->mr = new stdclass;
$lang->mr->common       = "Merge Request";
$lang->mr->server       = "Server";
$lang->mr->hostID       = "Server";
$lang->mr->view         = "Survey";
$lang->mr->viewAction   = "{$lang->mr->common} Details";
$lang->mr->create       = "Submit {$lang->mr->common}";
$lang->mr->apiCreate    = "Interface: Create";
$lang->mr->browse       = "Browse";
$lang->mr->browseAction = "{$lang->mr->common} List";
$lang->mr->list         = "List";
$lang->mr->edit         = "Edit";
$lang->mr->delete       = "Delete";
$lang->mr->accept       = "Accept";
$lang->mr->source       = 'source';
$lang->mr->target       = 'target';
$lang->mr->viewDiff     = 'View diff';
$lang->mr->diff         = 'View diff';
$lang->mr->viewInGit    = 'View in APP';
$lang->mr->link         = 'Link of stories,Bugs,tasks';
$lang->mr->createAction = '%s, <strong>%s</strong> submitted a <a href="%s">Merge Request</a>.';
$lang->mr->editAction   = '%s, <strong>%s</strong> edited <a href="%s">Merge Request</a>。';
$lang->mr->removeAction = '%s, <strong>%s</strong> deleted <a href="%s">Merge Request</a>。';
$lang->mr->submitType   = 'Submit type';

$lang->mr->linkList  = 'Link List of stories,Bugs,tasks';
$lang->mr->linkStory = 'Link Stories';
$lang->mr->linkBug   = 'Link Bugs';
$lang->mr->linkTask  = 'Link Tasks';
$lang->mr->unlink    = 'UnLink of stories,Bugs,tasks';
$lang->mr->addReview = 'Add Review';

$lang->mr->id          = 'ID';
$lang->mr->mriid       = "raw MR ID";
$lang->mr->title       = 'Name';
$lang->mr->status      = 'Status';
$lang->mr->author      = 'Author';
$lang->mr->createdDate = 'Created date';
$lang->mr->assignee    = 'Assignee';
$lang->mr->reviewer    = 'Reviewer';
$lang->mr->mergeStatus = 'Merge status';
$lang->mr->commits     = 'commits';
$lang->mr->changes     = 'changes';
$lang->mr->gitlabID    = 'GitLab';
$lang->mr->repoID      = 'Repo';
$lang->mr->jobID       = 'Pipeline job';

$lang->mr->canMerge  = "Can be merged";
$lang->mr->cantMerge = "Can not be merged";

$lang->mr->approval = 'Approval';
$lang->mr->approve  = 'Approve';
$lang->mr->reject   = 'Reject';
$lang->mr->close    = 'Close';
$lang->mr->reopen   = 'Reopen';

$lang->mr->reviewType     = 'Review Type';
$lang->mr->reviewTypeList = array();
$lang->mr->reviewTypeList['bug']  = 'Bug';
$lang->mr->reviewTypeList['task'] = 'Task';

$lang->mr->approvalResult     = 'Approval result';
$lang->mr->approvalResultList = array();
$lang->mr->approvalResultList['approve'] = 'Approve';
$lang->mr->approvalResultList['reject']  = 'Reject';

$lang->mr->needApproved       = 'This MR should be approved before merge';
$lang->mr->needCI             = 'Merge only after passing pipeline';
$lang->mr->removeSourceBranch = 'Delete source branch after merge';
$lang->mr->squash             = 'Squash commits';

$lang->mr->repeatedOperation = 'Do not repeat operations';

$lang->mr->approvalStatus     = 'Approve status';
$lang->mr->approvalStatusList = array();
$lang->mr->approvalStatusList['notReviewed'] = 'notReviewed';
$lang->mr->approvalStatusList['approved']    = 'Approved';
$lang->mr->approvalStatusList['rejected']    = 'Rejected';

$lang->mr->notApproved  = 'Rejected';
$lang->mr->assignedToMe = 'AssignedToMe';
$lang->mr->createdByMe  = 'CreatedByMe';

$lang->mr->statusList = array();
$lang->mr->statusList['all']    = 'all';
$lang->mr->statusList['opened'] = 'opened';
$lang->mr->statusList['merged'] = 'merged';
$lang->mr->statusList['closed'] = 'closed';

$lang->mr->mergeStatusList = array();
$lang->mr->mergeStatusList['unchecked']            = 'unchecked';
$lang->mr->mergeStatusList['checking']             = 'checking';
$lang->mr->mergeStatusList['can_be_merged']        = 'can be merged';
$lang->mr->mergeStatusList['cannot_be_merged']     = 'cannot be merged';
$lang->mr->mergeStatusList['cannot_merge_by_fail'] = 'Cannot be merged, check failed';

$lang->mr->description       = 'Description';
$lang->mr->confirmDelete     = 'Are you sure to delete this merge request?';
$lang->mr->sourceProject     = 'Source repository';
$lang->mr->sourceBranch      = 'Source branch';
$lang->mr->targetProject     = 'Target repository';
$lang->mr->targetBranch      = 'Target branch';
$lang->mr->noCompileJob      = 'No Pipeline Job';
$lang->mr->compileUnexecuted = 'Compile Unexecuted';

$lang->mr->notFound          = "Merge Request does not exist!";
$lang->mr->toCreatedMessage  = "The merge request you submitted：<a href='%s'>%s</a>, the pipeline task succeeded.";
$lang->mr->toReviewerMessage = "You have one merge request <a href='%s'>%s</a> waiting.";
$lang->mr->failMessage       = "Your merge request <a href='%s'>%s</a> failed. Please check its execution result. ";
$lang->mr->storySummary      = "Total <strong>%s</strong> {$lang->SRCommon} on this page.";

$lang->mr->apiError = new stdclass;
$lang->mr->apiError->createMR = "Failed to create a merge request through API. Reason: %s";
$lang->mr->apiError->sudo     = "Unable to operate with the GitLab account bound to the current user. Reason: %s";

$lang->mr->createFailedFromAPI = "Failed to create Merge Request.";
$lang->mr->hasSameOpenedMR     = "There are duplicate and unclosed merge requests: ID%u";
$lang->mr->accessGitlabFailed  = "Unable to connect to the GitLab server.";
$lang->mr->reopenSuccess       = "The merge request was reopened.";
$lang->mr->closeSuccess        = "Merge request closed.";

$lang->mr->apiErrorMap[1] = "You can't use same project/branch for source and target";
$lang->mr->apiErrorMap[2] = "/Another open merge request already exists for this source branch: !([0-9]+)/";
$lang->mr->apiErrorMap[3] = "401 Unauthorized";
$lang->mr->apiErrorMap[4] = "403 Forbidden";
$lang->mr->apiErrorMap[5] = "/(pull request already exists for these targets).*/";
$lang->mr->apiErrorMap[6] = "Invalid PullRequest: There are no changes between the head and the base";
$lang->mr->apiErrorMap[7] = "/(user doesn't have access to repo).*/";
$lang->mr->apiErrorMap[8] = "/(git apply).*/";

$lang->mr->errorLang[1] = 'The source project branch cannot be the same as the target project branch';
$lang->mr->errorLang[2] = 'Another open merge request already exists for this source branch: ID%u';
$lang->mr->errorLang[3] = "Unauthorized";
$lang->mr->errorLang[4] = 'Permission denied';
$lang->mr->errorLang[5] = 'Another open merge request already exists for this source branch';
$lang->mr->errorLang[6] = 'The source project branch cannot be the same as the target project branch';
$lang->mr->errorLang[7] = "user doesn't have access to repo";
$lang->mr->errorLang[8] = 'The source branch and target branch cannot be merged';

$lang->mr->from = "from";
$lang->mr->to   = "to";
$lang->mr->at   = "at";

$lang->mr->pipeline         = "Pipeline";
$lang->mr->pipelineSuccess  = "Success";
$lang->mr->pipelineFailed   = "Failed";
$lang->mr->pipelineCanceled = "Canceled";
$lang->mr->pipelineUnknown  = "Unknown";

$lang->mr->pipelineStatus = array();
$lang->mr->pipelineStatus['success']  = "success";
$lang->mr->pipelineStatus['failed']   = "failed";
$lang->mr->pipelineStatus['canceled'] = "canceled";

$lang->mr->MRHasConflicts = "Merge Request has a conflict";
$lang->mr->hasConflicts   = "There are merge conflicts or wait for push";
$lang->mr->hasNoConflict  = "Can merge";
$lang->mr->acceptMR       = "Accept Merge request ";
$lang->mr->mergeFailed    = "Unable to merge request, please check the merge request status";
$lang->mr->mergeSuccess   = "Merge Request Successfully";

$lang->mr->todomessage = "project was assigned to you";

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
  <p><blockquote>Note: This merge request status will be changed automatically after you merged locally.</blockquote></p>
  <p>
    step 1. Change directory to target project. Fetch and check out the branch for this merge request
    <pre>
    git fetch "%s" %s
    git checkout -b "%s" FETCH_HEAD</pre>
  </p>
  <p>
    step 2. Review the changes locally. You can use <code>git log</code> to view the changes
  </p>
  <p>
    step 3. Merge the branch and fix any conflicts that come up
    <pre>
    git fetch origin
    git checkout "%s"
    git merge --no-ff "%s"</pre>
  </p>
  <p>
    step 4. Push the result of the merge to Git
    <pre>
    git push origin "%s" </pre>
  </p>
</div>
EOD;

$lang->mr->noChanges = "Currently there are no changes in this merge request's source branch. Please push new commits or use a different branch.";

$lang->mr->linkTask          = "Link task";
$lang->mr->unlinkTask        = "Remove task";
$lang->mr->linkedTasks       = 'Task';
$lang->mr->unlinkedTasks     = 'Task not linked';
$lang->mr->confirmUnlinkTask = "Are you sure to remove this task?";
$lang->mr->taskSummary       = "There are <strong>%s</strong> tasks on this page";
$lang->mr->notDelbranch      = "The source branch cannot be deleted when it is a protected branch";
$lang->mr->addForApp         = "There are no projects under this server, do you want to go to add?";

$lang->mr->featureBar['browse']['all']      = $lang->mr->statusList['all'];
$lang->mr->featureBar['browse']['opened']   = $lang->mr->statusList['opened'];
$lang->mr->featureBar['browse']['merged']   = $lang->mr->statusList['merged'];
$lang->mr->featureBar['browse']['closed']   = $lang->mr->statusList['closed'];
$lang->mr->featureBar['browse']['assignee'] = $lang->mr->assignedToMe;
$lang->mr->featureBar['browse']['creator']  = $lang->mr->createdByMe;
