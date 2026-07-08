<?php
$lang->ppm->common            = 'Review Requests';
$lang->ppm->server            = "Server";
$lang->ppm->hostID            = "Server";
$lang->ppm->view              = "Survey";
$lang->ppm->viewAction        = "{$lang->ppm->common} Details";
$lang->ppm->create            = "Submit Merge Request";
$lang->ppm->mirrorRepoTip     = 'The current repository is a mirror repository. Re-import it in "readable, writable, manageable" mode to enable code review.';
$lang->ppm->hasMirrorRepoTip = 'The current repository is a mirror repository, which does not support code review. Please do not create merge requests.';
$lang->ppm->apiCreate         = "Interface: Create";
$lang->ppm->browse            = "Browse";
$lang->ppm->browseAction      = "{$lang->ppm->common} List";
$lang->ppm->list              = "List";
$lang->ppm->edit              = "Edit {$lang->ppm->common}";
$lang->ppm->delete            = "Delete {$lang->ppm->common}";
$lang->ppm->accept            = "Accept";
$lang->ppm->source            = 'source';
$lang->ppm->target            = 'target';
$lang->ppm->viewDiff          = 'View diff';
$lang->ppm->diff              = 'View diff';
$lang->ppm->viewInGit         = 'View in APP';
$lang->ppm->link              = 'Link of stories,Bugs,tasks';
$lang->ppm->createAction      = '%s, <strong>%s</strong> submitted a <a href="%s">Merge Request</a>.';
$lang->ppm->editAction        = '%s, <strong>%s</strong> edited <a href="%s">Merge Request</a>。';
$lang->ppm->removeAction      = '%s, <strong>%s</strong> deleted <a href="%s">Merge Request</a>。';
$lang->ppm->submitType        = 'Submit type';
$lang->ppm->linkedObject      = 'Linked items';
$lang->ppm->object            = 'Object';
$lang->ppm->mergeInfo         = 'Merge View';
$lang->ppm->locateView        = 'View';
$lang->ppm->codeConflict      = 'Conflict';
$lang->ppm->hasConflict       = 'Check Conflict';
$lang->ppm->request           = 'Request';
$lang->ppm->AIReview          = 'AI Review';
$lang->ppm->AICodeScore       = 'Score';
$lang->ppm->AISevereIssue     = 'Severe Issue';
$lang->ppm->AIOrdinaryIssue   = 'Ordinary Issue';
$lang->ppm->manualReview      = 'Manual Review';
$lang->ppm->approvalReviewer  = 'Number Of Approvers';
$lang->ppm->doneReviewer      = 'Number Of Approved';
$lang->ppm->codeScan          = 'Code Scan';
$lang->ppm->scanSevereIssue   = 'Severe Issue';
$lang->ppm->scanOrdinaryIssue = 'Ordinary Issue';
$lang->ppm->scanPassRate      = 'Access Pass Rate';
$lang->ppm->runResult         = 'Result';
$lang->ppm->basicInfo         = 'Basic Info';
$lang->ppm->sourceBranch      = 'Source Branch';
$lang->ppm->targetBranch      = 'Target Branch';
$lang->ppm->filePath          = 'File Path';
$lang->ppm->conflictFiles     = 'Conflict Files';
$lang->ppm->changeFiles       = 'Change Files';
$lang->ppm->issueList         = 'Issue List';
$lang->ppm->add               = 'Add';
$lang->ppm->addReviewer       = 'Add Reviewer';
$lang->ppm->reviewStatus      = 'Review Status';
$lang->ppm->review            = 'Review';
$lang->ppm->decision          = 'Review Decision';
$lang->ppm->opinion           = 'Review Opinion';
$lang->ppm->merge             = 'Merge' . $lang->ppm->common;
$lang->ppm->assignedTo        = 'Assigned To';

$lang->ppm->opinionPlaceholder = 'Please input your opinion';

$lang->ppm->action = new stdclass();
$lang->ppm->action->synced   = '$date, <strong>$actor</strong> synced this Merge Request.';
$lang->ppm->action->imported = '$date, <strong>$actor</strong> imported this Merge Request.';

$lang->ppm->linkList   = 'Link List of stories,Bugs,tasks';
$lang->ppm->linkStory  = 'Link Stories';
$lang->ppm->linkBug    = 'Link Bugs';
$lang->ppm->linkTask   = 'Link Tasks';
$lang->ppm->unlinkTask = 'Unlink Tasks';
$lang->ppm->unlink     = 'UnLink of stories,Bugs,tasks';
$lang->ppm->addReview  = 'Add Review';

$lang->ppm->id          = 'ID';
$lang->ppm->mriid       = "raw MR ID";
$lang->ppm->title       = 'Name';
$lang->ppm->status      = 'Status';
$lang->ppm->author      = 'Author';
$lang->ppm->createdDate = 'Created date';
$lang->ppm->assignee    = 'Assignee';
$lang->ppm->reviewer    = 'Reviewer';
$lang->ppm->mergeStatus = 'Merge status';
$lang->ppm->commits     = 'commits';
$lang->ppm->changes     = 'changes';
$lang->ppm->gitlabID    = 'GitLab';
$lang->ppm->repoID      = 'Repo';
$lang->ppm->jobID       = 'Pipeline job';
$lang->ppm->commitLogs  = 'Commit Logs';
$lang->ppm->execJob     = 'Execute';
$lang->ppm->execJobTip  = 'Execute the pipeline job manually';
$lang->ppm->repo        = 'Repo';

$lang->ppm->canMerge  = "Can be merged";
$lang->ppm->cantMerge = "Can not be merged";

$lang->ppm->approval = 'Approval';
$lang->ppm->approve  = 'Approve';
$lang->ppm->reject   = 'Reject';
$lang->ppm->close    = 'Close' . $lang->ppm->common;
$lang->ppm->reopen   = 'Reopen' . $lang->ppm->common;

$lang->ppm->reviewType     = 'Review Type';
$lang->ppm->reviewTypeList = array();
$lang->ppm->reviewTypeList['bug']  = 'Bug';
$lang->ppm->reviewTypeList['task'] = 'Task';

$lang->ppm->approvalResult     = 'Approval result';
$lang->ppm->approvalResultList = array();
$lang->ppm->approvalResultList['approved'] = 'Approve';
$lang->ppm->approvalResultList['rejected'] = 'Reject';

$lang->ppm->needApproved       = 'This MR should be approved before merge';
$lang->ppm->needCI             = 'Merge only after passing pipeline';
$lang->ppm->removeSourceBranch = 'Delete source branch after merge';
$lang->ppm->squash             = 'Squash commits';
$lang->ppm->triggeredCI        = 'The pipeline job is triggered due to the target branch or pipeline job changed.';
$lang->ppm->acceptTip          = 'Please approve this MR before merge';
$lang->ppm->conflictsTip       = 'This merge request has conflicts, cannot be merged';
$lang->ppm->noChangesTip       = 'Source branch and target branch have no changes, cannot be merged';
$lang->ppm->compileTip         = 'This merge request pipeline build is not successful, cannot be merged';
$lang->ppm->notifyTip          = 'This merge request has conflicts or no changes, cannot be merged';
$lang->ppm->branchUpdateTip    = 'Branch has been updated, execute pipeline';
$lang->ppm->draftTips          = 'Merge request is in draft status, cannot be merged.';

$lang->ppm->repeatedOperation = 'Do not repeat operations';

$lang->ppm->approvalStatus     = 'Approve status';
$lang->ppm->approvalStatusList = array();
$lang->ppm->approvalStatusList['pending']    = 'notReviewed';
$lang->ppm->approvalStatusList['inProgress'] = 'inProgress';
$lang->ppm->approvalStatusList['approved']   = 'Approved';
$lang->ppm->approvalStatusList['rejected']   = 'Rejected';

$lang->ppm->notApproved  = 'Rejected';
$lang->ppm->assignedToMe = 'AssignedToMe';
$lang->ppm->createdByMe  = 'CreatedByMe';

$lang->ppm->statusList = array();
$lang->ppm->statusList['all']    = 'all';
$lang->ppm->statusList['opened'] = 'opened';
$lang->ppm->statusList['merged'] = 'merged';
$lang->ppm->statusList['closed'] = 'closed';

$lang->ppm->mergeStatusList = array();
$lang->ppm->mergeStatusList['unchecked']            = 'unchecked';
$lang->ppm->mergeStatusList['checking']             = 'checking';
$lang->ppm->mergeStatusList['can_be_merged']        = 'can be merged';
$lang->ppm->mergeStatusList['cannot_be_merged']     = 'cannot be merged';
$lang->ppm->mergeStatusList['cannot_merge_by_fail'] = 'Cannot be merged, check failed';

$lang->ppm->description       = 'Description';
$lang->ppm->confirmDelete     = 'Are you sure to delete this merge request?';
$lang->ppm->sourceProject     = 'Source repository';
$lang->ppm->sourceBranch      = 'Source branch';
$lang->ppm->targetProject     = 'Target repository';
$lang->ppm->targetBranch      = 'Target branch';
$lang->ppm->noCompileJob      = 'No Pipeline Job';
$lang->ppm->compileUnexecuted = 'Compile Unexecuted';
$lang->ppm->compileID         = 'Compile ID';
$lang->ppm->compileStatus     = 'Compile Status';

$lang->ppm->notFound          = "Merge Request does not exist!";
$lang->ppm->toCreatedMessage  = "The merge request you submitted：<a href='%s'>%s</a>, the pipeline task succeeded.";
$lang->ppm->toReviewerMessage = "You have one merge request <a href='%s'>%s</a> waiting.";
$lang->ppm->failMessage       = "Your merge request <a href='%s'>%s</a> failed. Please check its execution result. ";
$lang->ppm->storySummary      = "Total <strong>%s</strong> {$lang->SRCommon} on this page.";

$lang->ppm->apiError = new stdclass;
$lang->ppm->apiError->createMR      = "Failed to create a merge request through API. Reason: %s";
$lang->ppm->apiError->sudo          = "Unable to operate with the GitLab account bound to the current user. Reason: %s";
$lang->ppm->apiError->emptyResponse = "The object requested by the API does not exist or failed.";
$lang->ppm->apiError->notFound      = "The object requested by the API does not exist, it may be deleted in API Server.";

$lang->ppm->createFailedFromAPI  = "Failed to create Merge Request.";
$lang->ppm->hasSameOpenedMR      = "There are duplicate and unclosed merge requests: ID%u";
$lang->ppm->accessGitlabFailed   = "Unable to connect to the GitLab server.";
$lang->ppm->reopenSuccess        = "The merge request was reopened.";
$lang->ppm->closeSuccess         = "Merge request closed.";
$lang->ppm->unsupportedFeature   = "Unsupported feature.";
$lang->ppm->checkSourceBranch    = 'The source branch can be merged into the target branch type: %s';
$lang->ppm->checkTargetBranch    = 'The target branch allows merging of the following source branch types: %s';
$lang->ppm->checkConflicts       = 'Code conflicts have been detected. Please resolve the conflicts locally before submitting the merge request.';
$lang->ppm->checkReviewers       = 'The reviewers must contain %s';
$lang->ppm->sourceBranchNotExist = 'The source branch does not exist.';
$lang->ppm->targetBranchNotExist = 'The target branch does not exist.';

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

$lang->ppm->errorLang[1]  = 'The source project branch cannot be the same as the target project branch';
$lang->ppm->errorLang[2]  = 'Another open merge request already exists for this source branch: ID%u';
$lang->ppm->errorLang[3]  = "Unauthorized";
$lang->ppm->errorLang[4]  = 'Permission denied';
$lang->ppm->errorLang[5]  = 'Another open merge request already exists for this source branch';
$lang->ppm->errorLang[6]  = 'The source project branch cannot be the same as the target project branch';
$lang->ppm->errorLang[7]  = "user doesn't have access to repo";
$lang->ppm->errorLang[8]  = 'The source branch and target branch cannot be merged';
$lang->ppm->errorLang[9]  = 'A duplicate merge request already exists';
$lang->ppm->errorLang[10] = 'Server error';
$lang->ppm->errorLang[11] = 'The source branch does not contain any new commits';

$lang->ppm->from = "from";
$lang->ppm->to   = "to";
$lang->ppm->at   = "at";

$lang->ppm->pipeline         = "Pipeline";
$lang->ppm->pipelineSuccess  = "Success";
$lang->ppm->pipelineFailed   = "Failed";
$lang->ppm->pipelineCanceled = "Canceled";
$lang->ppm->pipelineUnknown  = "Unknown";

$lang->ppm->pipelineStatus = array();
$lang->ppm->pipelineStatus['success']  = "success";
$lang->ppm->pipelineStatus['failed']   = "failed";
$lang->ppm->pipelineStatus['canceled'] = "canceled";

$lang->ppm->MRHasConflicts = "Merge Request has a conflict";
$lang->ppm->hasConflicts   = "There are merge conflicts";
$lang->ppm->hasNoChanges   = "Branch have no changes";
$lang->ppm->hasNoConflict  = "Can merge";
$lang->ppm->acceptMR       = "Accept Merge request ";
$lang->ppm->mergeFailed    = "Unable to merge request, please check the merge request status";
$lang->ppm->mergeSuccess   = "Merge Request Successfully";
$lang->ppm->refreshSuccess = 'Refresh successfully';

$lang->ppm->todomessage = "project was assigned to you";
$lang->ppm->squashHelp  = 'Corresponding git parameters: --squash';

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

$lang->ppm->noChanges = "Currently there are no changes in this merge request's source branch. Please push new commits or use a different branch.";

$lang->ppm->linkTask          = "Link task";
$lang->ppm->unlinkTask        = "Remove task";
$lang->ppm->linkedTasks       = 'Task';
$lang->ppm->unlinkedTasks     = 'Task not linked';
$lang->ppm->confirmUnlinkTask = "Are you sure to remove this task?";
$lang->ppm->taskSummary       = "There are <strong>%s</strong> tasks on this page";
$lang->ppm->notDelbranch      = "The source branch cannot be deleted when it is a protected branch";
$lang->ppm->addForApp         = "There are no projects under this server, do you want to go to add?";
$lang->ppm->checkSuccess      = 'The inspection has been passed, and this branch is allowed to be merged';
$lang->ppm->checkFailed       = 'The check failed, and this branch cannot be merged';
$lang->ppm->MRHistory         = "This merge was created by <strong>%s</strong> on <strong>%s</strong>，merging <label class='label primary size-sm px-2 cursor-pointer' data-on='click' data-call='copy' data-params='event'>%s<icon class='icon-copy ml-1'/></label> <strong>%s</strong> commits，Merge into <label class='label primary size-sm px-2 cursor-pointer' data-on='click' data-call='copy' data-params='event'>%s<icon class='icon-copy ml-1'/></label> 。";

$lang->ppm->checkStatusList = array();
$lang->ppm->checkStatusList['fail']    = 'Not passed';
$lang->ppm->checkStatusList['success'] = 'Passed';
$lang->ppm->checkStatusList['wait']    = 'To be confirmed';

$lang->ppm->hasConflictList['yes'] = 'Yes';
$lang->ppm->hasConflictList['no']  = 'No';

$lang->ppm->featureBar['browse']['all']      = $lang->ppm->statusList['all'];
$lang->ppm->featureBar['browse']['opened']   = $lang->ppm->statusList['opened'];
$lang->ppm->featureBar['browse']['merged']   = $lang->ppm->statusList['merged'];
$lang->ppm->featureBar['browse']['closed']   = $lang->ppm->statusList['closed'];
$lang->ppm->featureBar['browse']['creator']  = $lang->ppm->createdByMe;

$lang->ppm->bug = new stdclass();
$lang->ppm->bug->title    = 'Title';
$lang->ppm->bug->source   = 'Source';
$lang->ppm->bug->type     = 'Type';
$lang->ppm->bug->file     = 'File';
$lang->ppm->bug->severity = 'Severity';
$lang->ppm->bug->status   = 'Type';

$lang->ppm->mergeTypeInfoList = array();
$lang->ppm->mergeTypeInfoList['merge']  = 'All commits on this branch will be added to the base branch via a merge commit.';
$lang->ppm->mergeTypeInfoList['squash'] = 'All commits on this branch will be merged into a single commit and added to the base branch.';
$lang->ppm->mergeTypeInfoList['rebase'] = 'All commits on this branch will be rebased and added to the base branch.';
$lang->ppm->mergeTypeInfoList['fast']   = 'All commits on this branch will be added directly to the base branch without generating merge commits, and rebasing may be required.';

$lang->ppm->notice = new stdclass();
$lang->ppm->notice->confirmClose                 = 'Are you sure to close this merge request?';
$lang->ppm->notice->confirmReopen                = 'Are you sure to reopen this merge request?';
$lang->ppm->notice->fastNotice                   = 'The target branch already has new commits, cannot be merged quickly';
$lang->ppm->notice->sameBranch                   = 'Source branch and target branch cannot be the same';
$lang->ppm->notice->userNotAllowMerge            = 'Only the following users are allowed to merge: %s';
$lang->ppm->notice->userNotAllowCreate           = 'Only the following users are allowed to create: %s';
$lang->ppm->notice->hasUnresolvedIssues          = 'There are unresolved issues, please resolve them first.';
$lang->ppm->notice->hasUnresolvedSpecifiedIssues = 'There are unresolved %s type issues, please resolve them first.';

$lang->ppm->featureBar['view']['all']   = 'All';
$lang->ppm->featureBar['view']['story'] = 'Story';
$lang->ppm->featureBar['view']['task']  = 'Task';
$lang->ppm->featureBar['view']['bug']   = 'Bug';

$lang->ppm->issueSourceList = array();
$lang->ppm->issueSourceList['code']  = 'Code';
$lang->ppm->issueSourceList['scan']  = 'Scan';
