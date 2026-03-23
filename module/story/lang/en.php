<?php
/**
 * The story module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: en.php 5141 2013-07-15 05:57:15Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
global $config;
$lang->story->create            = "Create Story";

$lang->story->requirement       = zget($lang, 'URCommon', "Feature");
$lang->story->story             = zget($lang, 'SRCommon', "Story");
$lang->story->createStory       = 'Create ' . $lang->story->story;
$lang->story->createRequirement = 'Create ' . $lang->story->requirement;
$lang->story->affectedStories   = "Affected {$lang->story->story}";

$lang->story->browse             = "{$lang->SRCommon} List";
$lang->story->batchCreate        = "Batch Create";
$lang->story->change             = "Change";
$lang->story->changed            = 'Change';
$lang->story->assignTo           = 'Assign';
$lang->story->review             = 'Review';
$lang->story->submitReview       = "Submit Review";
$lang->story->recall             = 'Undo Review';
$lang->story->recallChange       = 'Undo Change';
$lang->story->recallAction       = 'Undo';
$lang->story->relation           = 'Linked Stories';
$lang->story->needReview         = 'Review Required';
$lang->story->batchReview        = 'Batch Review';
$lang->story->edit               = "Edit Story";
$lang->story->editDraft          = "Edit Draft";
$lang->story->batchEdit          = "Batch Edit";
$lang->story->subdivide          = 'Split';
$lang->story->subdivideSR        = $lang->SRCommon . 'Split';
$lang->story->link               = 'Link';
$lang->story->unlink             = 'Unlink';
$lang->story->track              = 'Traceability Matrix';
$lang->story->trackAB            = 'RTM';
$lang->story->processStoryChange = 'Confirm Story Changes';
$lang->story->storyChange        = 'Story Change';
$lang->story->upstreamDemand     = 'Upstream Story';
$lang->story->split              = 'Split';
$lang->story->close              = 'Close';
$lang->story->batchClose         = 'Batch Close';
$lang->story->activate           = 'Activate';
$lang->story->delete             = "Delete";
$lang->story->view               = "Story Details";
$lang->story->setting            = "Settings";
$lang->story->tasks              = "Linked Tasks";
$lang->story->bugs               = "Linked Bugs";
$lang->story->cases              = "Linked  Test Cases";
$lang->story->taskCount          = 'Tasks';
$lang->story->bugCount           = 'Bugs';
$lang->story->caseCount          = 'Test Cases';
$lang->story->taskCountAB        = 'Tasks';
$lang->story->bugCountAB         = 'Bugs';
$lang->story->caseCountAB        = 'Test Cases';
$lang->story->linkStory          = "Link Story";
$lang->story->unlinkStory        = "Are you sure you want to unlink the story?";
$lang->story->linkStoriesAB      = "Link {$lang->SRCommon}";
$lang->story->linkRequirementsAB = "Link {$lang->URCommon}";
$lang->story->export             = "Export Data";
$lang->story->zeroCase           = "Stories without test cases";
$lang->story->zeroTask           = "Only show stories without tasks";
$lang->story->reportChart        = "Report";
$lang->story->copyTitle          = "Same as Story Title";
$lang->story->batchChangePlan    = "Batch Change Plans";
$lang->story->batchChangeBranch  = "Batch Change Branches";
$lang->story->batchChangeStage   = "Batch Change Phases";
$lang->story->batchAssignTo      = "Batch Assign";
$lang->story->batchChangeModule  = "Batch Change Modules";
$lang->story->batchChangeParent  = "Batch Change Parent";
$lang->story->batchChangeGrade   = "Batch Change Hierarchy";
$lang->story->changeParent       = "Change Parent";
$lang->story->viewAll            = "Show All";
$lang->story->toTask             = 'Convert to Task';
$lang->story->batchToTask        = 'Batch Convert to Task';
$lang->story->convertRelations   = 'Conversion Relations';
$lang->story->undetermined       = 'TBD';
$lang->story->order              = 'Sort';
$lang->story->saveDraft          = 'Save as draft';
$lang->story->doNotSubmit        = 'Save without Submitting';
$lang->story->currentBranch      = 'Current Branch';
$lang->story->twins              = 'Twin Stories';
$lang->story->relieved           = 'Unlink';
$lang->story->relievedTwins      = 'Unlink Twin Stories';
$lang->story->loadAllStories     = 'All';
$lang->story->hasDividedTask     = 'Task Breakdown Completed';
$lang->story->hasDividedCase     = 'Test Cases Created';
$lang->story->viewAllGrades      = 'Show All Hierarchies';
$lang->story->codeBranch         = 'Code branch';
$lang->story->unlinkBranch       = 'Unlink code branch';
$lang->story->branchName         = 'Branch Name';
$lang->story->branchFrom         = 'Create from';
$lang->story->codeRepo           = 'Code Repository';
$lang->story->viewByType         = "View by %s";

$lang->story->editAction      = "Edit {$lang->SRCommon}";
$lang->story->changeAction    = "Change {$lang->SRCommon}";
$lang->story->assignAction    = "Assign {$lang->SRCommon}";
$lang->story->reviewAction    = "Review {$lang->SRCommon}";
$lang->story->subdivideAction = "Split {$lang->SRCommon}";
$lang->story->closeAction     = "Close {$lang->SRCommon}";
$lang->story->activateAction  = "Activate {$lang->SRCommon}";
$lang->story->deleteAction    = "Delete {$lang->SRCommon}";
$lang->story->exportAction    = "Export {$lang->SRCommon}";
$lang->story->reportAction    = "Report";

$lang->story->closedStory      = "{$lang->SRCommon} %s is closed, and this operation will be ignored.";
$lang->story->batchToTaskTips  = "Only active {$lang->SRCommon} can be converted into tasks.";
$lang->story->successToTask    = "Batch conversion to tasks completed.";
$lang->story->storyRound       = 'Round %s estimation';
$lang->story->float            = "[%s] should be a positive number and may include decimals.";
$lang->story->saveDraftSuccess = 'Saved as draft successfully.';

$lang->story->changeSyncTip    = "Changes to this story will be synchronized with the following twin stories.";
$lang->story->syncTip          = "All fields between twin stories are synchronized except for {Slang->productCommon}, branch, module, plan, and phase. Synchronization will stop once the twin relationship is removed.";
$lang->story->relievedTip      = "Once the twin relationship is removed, it cannot be restored and the stories will no longer be synchronized. Do you want to proceed with the removal?";
$lang->story->assignSyncTip    = "Assignees have been updated synchronously across all twin stories,";
$lang->story->closeSyncTip     = "All twin stories have been closed synchronously.";
$lang->story->activateSyncTip  = "All twin stories have been activated synchronously.";
$lang->story->relievedTwinsTip = "After {$lang->productCommon} is changed, this story will automatically be unlinked from its twin. The synchronization will stop. Do you want to save the changes?";
$lang->story->batchEditTip     = "{$lang->SRCommon} %s is a twin story. This operation will be ignored.";
$lang->story->planTip          = "{$lang->SRCommon} can be assigned to only one plan, while other stories can be assigned to multiple plans.";
$lang->story->batchEditError   = "All selected stories are non-editable. This operation will be ignored.";

$lang->story->id               = 'ID';
$lang->story->parent           = 'Parent Story';
$lang->story->isParent         = 'is Parent Story';
$lang->story->grade            = 'Hierarchy';
$lang->story->gradeName        = 'Hierarchy Name';
$lang->story->path             = 'Path';
$lang->story->product          = $lang->productCommon;
$lang->story->project          = $lang->projectCommon;
$lang->story->execution        = "Execution";
$lang->story->branch           = "Branch / Platform";
$lang->story->module           = 'Module';
$lang->story->moduleAB         = 'Module';
$lang->story->roadmap          = 'Roadmap';
$lang->story->source           = 'Source';
$lang->story->sourceNote       = 'Note';
$lang->story->fromBug          = 'Source Bug';
$lang->story->title            = "{$lang->SRCommon} Title";
$lang->story->name             = "Name";
$lang->story->type             = "Type";
$lang->story->category         = 'Category';
$lang->story->color            = 'Color';
$lang->story->toBug            = 'Convert to Bug';
$lang->story->spec             = 'Description';
$lang->story->assign           = 'Assigned to';
$lang->story->verify           = 'Acceptance Criteria';
$lang->story->pri              = 'Priority';
$lang->story->estimate         = "Estimation";
$lang->story->estimateAB       = 'Est.';
$lang->story->hour             = $lang->hourCommon;
$lang->story->consumed         = 'Cost';
$lang->story->status           = 'Status';
$lang->story->statusAB         = 'Status';
$lang->story->subStatus        = 'Substatus';
$lang->story->stage            = 'Phase';
$lang->story->stageAB          = 'Phase';
$lang->story->stagedBy         = 'Phase Creator';
$lang->story->mailto           = 'Mail to';
$lang->story->openedBy         = 'Creator';
$lang->story->openedByAB       = 'Creator';
$lang->story->openedDate       = 'Created on';
$lang->story->assignedTo       = 'Assigned to';
$lang->story->assignedToAB     = 'Assign';
$lang->story->assignedDate     = 'Assigned on';
$lang->story->lastEditedBy     = 'Last Edit';
$lang->story->lastEditedByAB   = 'Last Edited by';
$lang->story->lastEditedDate   = 'Last Edited on';
$lang->story->closedBy         = 'Closed by';
$lang->story->closedDate       = 'Closed on';
$lang->story->closedReason     = 'Close Reason';
$lang->story->rejectedReason   = 'Reject Reason';
$lang->story->changedBy        = 'Changed by';
$lang->story->changedDate      = 'Changed on';
$lang->story->reviewedBy       = 'Reviewed by';
$lang->story->reviewer         = 'Reviewer';
$lang->story->reviewers        = 'Reviewer';
$lang->story->reviewedDate     = 'Reviewed on';
$lang->story->activatedDate    = 'Activated on';
$lang->story->version          = 'Version';
$lang->story->feedbackBy       = 'Feedback Provider';
$lang->story->notifyEmail      = 'Email';
$lang->story->plan             = 'Linked Plan';
$lang->story->planAB           = 'Plan';
$lang->story->comment          = 'Comment';
$lang->story->children         = "Sub{$lang->SRCommon}";
$lang->story->childItem        = "Subitems";
$lang->story->childrenAB       = "Sub";
$lang->story->linkStories      = 'Linked Story';
$lang->story->linkRequirements = "Linked {$lang->URCommon}";
$lang->story->childStories     = 'Split Story';
$lang->story->duplicateStory   = 'Duplicated Story';
$lang->story->reviewResult     = 'Review Result';
$lang->story->reviewResultAB   = 'Review Result';
$lang->story->preVersion       = 'Previous Version';
$lang->story->keywords         = 'Keywords';
$lang->story->newStory         = 'Create Story';
$lang->story->colorTag         = 'Color';
$lang->story->files            = 'Files';
$lang->story->copy             = "Copy Story";
$lang->story->total            = "Total Stories";
$lang->story->draft            = 'Draft';
$lang->story->unclosed         = 'Open';
$lang->story->deleted          = 'Deleted';
$lang->story->released         = 'Released Stories';
$lang->story->URChanged        = 'Change Feature';
$lang->story->design           = 'Design';
$lang->story->case             = 'Test Cases';
$lang->story->bug              = 'Bugs';
$lang->story->repoCommit       = 'Commits';
$lang->story->one              = 'One';
$lang->story->field            = 'Sync Fields';
$lang->story->completeRate     = 'Completion Rate';
$lang->story->reviewed         = 'Reviewed';
$lang->story->toBeReviewed     = 'Awaiting Review';
$lang->story->isReviewed       = 'Is Reviewed';
$lang->story->linkMR           = 'Related MRs';
$lang->story->linkPR           = 'Related PRs';
$lang->story->linkCommit       = 'Related Commits';
$lang->story->URS              = 'Feature';
$lang->story->estimateUnit     = "(Unit: {$lang->story->hour})";
$lang->story->verifiedDate     = 'Accepted on';
$lang->story->root             = 'Root Story ID';
$lang->story->vision           = 'Interface';
$lang->story->fromStory        = 'Source Story';
$lang->story->fromVersion      = 'Source Version';
$lang->story->approvedDate     = 'Reviewed on';
$lang->story->releasedDate     = 'Released on';
$lang->story->parentVersion    = 'Parent Version';
$lang->story->demandVersion    = 'Story from Story Pool';
$lang->story->storyChanged     = 'Story Changed';
$lang->story->demand           = 'Stories from Story Pool';
$lang->story->unlinkReason     = 'Unlink Reason';
$lang->story->retractedReason  = 'Revoke Reason';

$lang->story->ditto       = 'Ditto';
$lang->story->dittoNotice = "This story is not linked to the same {$lang->productCommon} as the last one is.";

$lang->story->viewTypeList['tiled'] = 'List View';
$lang->story->viewTypeList['tree']  = 'Tree View';

if($config->enableER) $lang->story->typeList['epic']        = $lang->ERCommon;
if($config->URAndSR)  $lang->story->typeList['requirement'] = $lang->URCommon;
$lang->story->typeList['story'] = $lang->SRCommon;

$lang->story->needNotReviewList[0] = 'Review Required';
$lang->story->needNotReviewList[1] = 'No Review Required';

$lang->story->useList[0] = 'Enable';
$lang->story->useList[1] = 'Disable';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = 'Draft';
$lang->story->statusList['reviewing'] = 'Reviewing';
$lang->story->statusList['active']    = 'Activate';
$lang->story->statusList['changing']  = 'Changing';
$lang->story->statusList['closed']    = 'Closed';

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = 'Waiting';
$lang->story->stageList['planned']    = 'Planned';
$lang->story->stageList['projected']  = 'Initiated ';
$lang->story->stageList['designing']  = 'Designing';
$lang->story->stageList['designed']   = 'Design Completed';
$lang->story->stageList['developing'] = 'Developing';
$lang->story->stageList['developed']  = 'Dev Completed';
$lang->story->stageList['testing']    = 'Testing';
$lang->story->stageList['tested']     = 'Test Completed';
$lang->story->stageList['verified']   = 'Accepted';
$lang->story->stageList['rejected']   = 'Rejected';
$lang->story->stageList['delivering'] = 'Delivering';
$lang->story->stageList['delivered']  = 'Deliver Completed';
$lang->story->stageList['released']   = 'Released';
$lang->story->stageList['closed']     = 'Closed';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = 'Completed';
$lang->story->reasonList['subdivided'] = 'Decomposed';
$lang->story->reasonList['duplicate']  = 'Duplicate';
$lang->story->reasonList['postponed']  = 'Postponed';
$lang->story->reasonList['willnotdo']  = "Won't Do";
$lang->story->reasonList['cancel']     = 'Cancelled';
$lang->story->reasonList['bydesign']   = 'As Designed';
//$lang->story->reasonList['isbug']      = 'Bug!';

$lang->story->reviewResultList['']        = '';
$lang->story->reviewResultList['pass']    = 'Pass';
$lang->story->reviewResultList['revert']  = 'Revoke';
$lang->story->reviewResultList['clarify'] = 'To Be Clarified';
$lang->story->reviewResultList['reject']  = 'Reject';

$lang->story->reviewList[0] = 'No';
$lang->story->reviewList[1] = 'Yes';

$lang->story->sourceList['']           = '';
$lang->story->sourceList['customer']   = 'Customer';
$lang->story->sourceList['user']       = 'User';
$lang->story->sourceList['po']         = $lang->productCommon . ' Owner';
$lang->story->sourceList['market']     = 'Marketing';
$lang->story->sourceList['service']    = 'Customer Service';
$lang->story->sourceList['operation']  = 'Operations';
$lang->story->sourceList['support']    = 'Technical Support';
$lang->story->sourceList['competitor'] = 'Competitor';
$lang->story->sourceList['partner']    = 'Partner';
$lang->story->sourceList['dev']        = 'Dev Team';
$lang->story->sourceList['tester']     = 'Test Team';
$lang->story->sourceList['bug']        = 'Bug';
$lang->story->sourceList['forum']      = 'Forum';
$lang->story->sourceList['other']      = 'Others';

$lang->story->priList[0] = '';
$lang->story->priList[1] = '1';
$lang->story->priList[2] = '2';
$lang->story->priList[3] = '3';
$lang->story->priList[4] = '4';

$lang->story->changeList = array();
$lang->story->changeList['no']  = 'Cancel';
$lang->story->changeList['yes'] = 'Confirm';

$lang->story->legendBasicInfo      = 'Basic Info';
$lang->story->legendLifeTime       = 'Story Life ';
$lang->story->legendRelated        = 'Related Info';
$lang->story->legendMailto         = 'Mail to';
$lang->story->legendAttach         = 'Files';
$lang->story->legendProjectAndTask = $lang->executionCommon . 'and Task';
$lang->story->legendBugs           = 'Linked Bugs';
$lang->story->legendFromBug        = 'Source Bug';
$lang->story->legendCases          = 'Linked Test Cases';
$lang->story->legendBuilds         = 'Linked Builds';
$lang->story->legendReleases       = 'Linked Releases';
$lang->story->legendLinkStories    = 'Linked Stories';
$lang->story->legendChildStories   = 'Substories';
$lang->story->legendSpec           = 'Description';
$lang->story->legendVerify         = 'Acceptance Criteria';
$lang->story->legendMisc           = 'Misc.';
$lang->story->legendInformation    = 'Story Info';

$lang->story->lblChange   = 'Change';
$lang->story->lblReview   = 'Review';
$lang->story->lblActivate = 'Activate';
$lang->story->lblClose    = 'Close';
$lang->story->lblTBC      = 'Task/Bug/Test Case';

$lang->story->checkAffection       = 'Impact';
$lang->story->affectedProjects     = "Impacted {$lang->project->common}/{$lang->execution->common}";
$lang->story->affectedBugs         = 'Impacted Bugs';
$lang->story->affectedCases        = 'Impacted Test Cases';
$lang->story->affectedTwins        = 'Impacted Twin Stories';

$lang->story->specTemplate           = "As a <role>, I would like to <do something> in order to <goal>.";
$lang->story->needNotReview          = 'No Review Required';
$lang->story->childStoryTitle        = 'Contains %s substories, of which %s are completed.';
$lang->story->childTaskTitle         = 'Contains %s subtasks, of which %s are completed.';
$lang->story->successSaved           = "Story is saved!";
$lang->story->confirmDelete          = "Are you sure you want to delete this {$lang->SRCommon}?";
$lang->story->confirmRecall          = "Are you sure you want to revoke this {$lang->SRCommon}?";
$lang->story->errorEmptyChildStory   = "The field Split {$lang->SRCommon} cannot be empty.";
$lang->story->errorNotSubdivide      = "{$lang->SRCommon} in review, closed, or a substory cannot be split.";
$lang->story->errorMaxGradeSubdivide = "The hierarchy level of this story has reached the maximum level set in the system; stories of the same type cannot be split further.";
$lang->story->errorStepwiseSubdivide = "This story type does not allow cross-system splitting. This setting can be changed in the Admin.";
$lang->story->errorCannotSplit       = "This story has been split into substories of this type and cannot be split into stories of other types.";
$lang->story->errorParentSplitTask   = "Parent stories cannot be converted to tasks. This operation has been ignored.";
$lang->story->errorERURSplitTask     = "Parent storie, {$lang->ERCommon}, and {$lang->URCommon} cannot be converted to tasks. This operation has been ignored.";
$lang->story->errorEmptyReviewedBy   = "The field {$lang->story->reviewers} cannot be empty.";
$lang->story->errorEmptyStory        = "A story with the same title already exists, or the title is empty. Please check your input.";
$lang->story->mustChooseResult       = 'You must select a review result.';
$lang->story->mustChoosePreVersion   = 'You must select a version to revert.';
$lang->story->noEpic                 = "No {$lang->ERCommon} available yet.";
$lang->story->noStory                = "No {$lang->SRCommon} available yet.";
$lang->story->noRequirement          = "No {$lang->URCommon} available yet.";
$lang->story->ignoreChangeStage      = "{$lang->SRCommon} %s is in draft or closed status. This operation has been ignored.";
$lang->story->cannotDeleteParent     = "Parent {$lang->SRCommon} cannot be deleted.";
$lang->story->moveChildrenTips       = "Are you sure you want to change the linked product? After the change, all sub-stories will be updated accordingly.";
$lang->story->changeTips             = 'The Feature linked to this Story has changed. Click “Cance” to ignore, or “Change” to update the Story accordingly.';
$lang->story->estimateMustBeNumber   = 'The estimated value must be a number.';
$lang->story->estimateMustBePlus     = 'The estimated value cannot be negative.';
$lang->story->confirmChangeBranch    = $lang->SRCommon . '%s is linked to a plan in a previous branch. After changing the branch, the ' . $lang->SRCommon . ' will be removed from that branch plan. Do you want to continue edit  ' . $lang->SRCommon . '?';
$lang->story->confirmChangePlan      = $lang->SRCommon . '%s is linked to a branch in the previous plans. After changing the branch, the ' . $lang->SRCommon . ' will be removed from that plan. Do you want to continue edit  ' . $lang->SRCommon . '?';
$lang->story->errorDuplicateStory    = $lang->SRCommon . '%s does not exist.';
$lang->story->confirmRecallChange    = "After revoking the change, the story will revert to the version before the change. Are you sure you want to revoke it?";
$lang->story->confirmRecallReview    = "Are you sure you want to withdraw the review?";
$lang->story->noStoryToTask          = "{lang->SRCommon} not in activated status and the parent {$lang->SRCommon} cannot be converted into a task!";
$lang->story->ignoreClosedStory      = "{$lang->SRCommon} %s is in closed status. This operation has been ignored.";
$lang->story->changeProductTips      = "Are you sure you want to change the linked product? After change, all substories will be updated accordingly.";
$lang->story->gradeOverflow          = "The deepest sub-story has a hierarchy depth of %s. Syncing would change it to %s, exceeding the allowed hierarchy limit. Change not allowed.";
$lang->story->batchGradeOverflow     = "Changing the parent of %s would cause its children to exceed the allowed hierarchy depth. This change was ignored.";
$lang->story->batchGradeSameRoot     = '%s has a parent-child relationship and won’t be reassigned in the hierarchy.';
$lang->story->batchGradeGtParent     = 'The parent story of %s cannot be itself or a child story. This change was ignored.';
$lang->story->batchParentError       = "%s cannot have itself or any of its descendants as its parent. This change was ignored.";
$lang->story->errorNoGradeSplit      = "No story levels are available to split.";
$lang->story->errorRecordMinus       = '[%s] must not be negative.';

$lang->story->form = new stdclass();
$lang->story->form->area     = 'Scope';
$lang->story->form->desc     = 'What story is it? How about the acceptance criteria?';
$lang->story->form->resource = 'Who will allocate resources? How long does it take?';
$lang->story->form->file     = 'Click here to upload any files related to the story.';

$lang->story->action = new stdclass();
$lang->story->action->reviewed              = array('main' => '$date, recorded by <strong>$actor</strong>. The review result is <strong>$extra</strong>.', 'extra' => 'reviewResultList');
$lang->story->action->rejectreviewed        = array('main' => '$date, recorded by <strong>$actor</strong>. The review result is <strong>$extra</strong>. The reason is <strong>$reason</strong>.', 'extra' => 'reviewResultList', 'reason' => 'reasonList');
$lang->story->action->recalled              = array('main' => '$date, the review revoked by <strong>$actor</strong>.');
$lang->story->action->closed                = array('main' => '$date, closed by <strong>$actor</strong>. The reason is <strong>$extra</strong> $appendLink.', 'extra' => 'reasonList');
$lang->story->action->closedbysystem        = array('main' => '$date, the system automatically closed the parent story because all of its child stories were closed.');
$lang->story->action->closedbyparent        = array('main' => '$date, the system automatically closed the child story because its parent story were closed.');
$lang->story->action->reviewpassed          = array('main' => '$date, reviewed by the <strong>system</strong> and marked as <strong>Pass</strong>.');
$lang->story->action->reviewrejected        = array('main' => '$date, closed by the <strong>system</strong> with reason: <strong>Reject</strong>.');
$lang->story->action->reviewclarified       = array('main' => '$date, reviewed by the <strong>system</strong> and marked as<strong>To Be Clarified</strong.');
$lang->story->action->reviewreverted        = array('main' => '$date, reviewed by the <strong>system</strong> and marked as<strong>Revoke Change</strong.');
$lang->story->action->linked2plan           = array('main' => '$date, linked to Plan <strong>$extra</strong> by <strong>$actor</strong.');
$lang->story->action->unlinkedfromplan      = array('main' => '$date, unlinked from plan <strong>$extra</strong> by <strong>$actor</strong.');
$lang->story->action->linked2execution      = array('main' => '$date, linked to ' . $lang->executionCommon . ' <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->unlinkedfromexecution = array('main' => '$date, unlinked from ' . $lang->executionCommon . ' <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->linked2kanban         = array('main' => '$date, linked to Kanban <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->linked2project        = array('main' => '$date, linked to ' . $lang->projectCommon . ' <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->unlinkedfromproject   = array('main' => '$date, unlinked from ' .$lang->projectCommon . '<strong>$extra</strong>  by <strong>$actor</strong>.');
$lang->story->action->linked2build          = array('main' => '$date, linked  to Build <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->unlinkedfrombuild     = array('main' => '$date, unlinked from Build <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->linked2release        = array('main' => '$date, linked to Release <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->unlinkedfromrelease   = array('main' => '$date, unlinked from Release <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->linked2revision       = array('main' => '$date, linked to Commit <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->unlinkedfromrevision  = array('main' => '$date, unlinked from Commit <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->linkrelatedstory      = array('main' => '$date, linked to Story <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->subdividestory        = array('main' => '$date, splited to {$lang->SRCommon}   <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->unlinkrelatedstory    = array('main' => '$date, unlinked from Story <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->unlinkchildstory      = array('main' => '$date, unlinked from the splited {$lang->SRCommon} <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->recalledchange        = array('main' => '$date, changes revoked by <strong>$actor</strong>.');
$lang->story->action->synctwins             = array('main' => '$ddate, the system automatically updated this story because its twin story <strong>$extra</strong> was $operate.', 'operate' => 'operateList'
);
$lang->story->action->syncgrade             = array('main' => '$date, the system updated this story hierarchy level to <strong>$extra</strong> due to a change in its parent story.');
$lang->story->action->linked2roadmap        = array('main' => '$date, linked to Roadmap <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->unlinkedfromroadmap   = array('main' => '$date, unlinked from Roadmap <strong>$extra</strong> by <strong>$actor</strong>.');
$lang->story->action->changedbycharter      = array('main' => '$date, charter proposal <strong>$extra</strong> was approved by <strong>$actor</strong>. The story phase was automatically updated to Charter.');

/* Statistical statement. */
$lang->story->report = new stdclass();
$lang->story->report->common = 'Reports';
$lang->story->report->select = 'Select Report Type';
$lang->story->report->create = 'Create Report';
$lang->story->report->value  = 'Stories';

$lang->story->report->charts['storiesPerProduct']      = '$lang->productCommon . Stories';
$lang->story->report->charts['storiesPerModule']       = 'Stories in Module';
$lang->story->report->charts['storiesPerSource']       = 'By Story Source';
$lang->story->report->charts['storiesPerPlan']         = 'By Plan';
$lang->story->report->charts['storiesPerStatus']       = 'By Status';
$lang->story->report->charts['storiesPerStage']        = 'By Phase';
$lang->story->report->charts['storiesPerPri']          = 'By Priority';
$lang->story->report->charts['storiesPerEstimate']     = 'By Estimated Efforts';
$lang->story->report->charts['storiesPerOpenedBy']     = 'By Creator';
$lang->story->report->charts['storiesPerAssignedTo']   = 'By Assignee';
$lang->story->report->charts['storiesPerClosedReason'] = 'By Closure Reason';
$lang->story->report->charts['storiesPerChange']       = 'By Change Count';
$lang->story->report->charts['storiesPerGrade']        = 'By Hierarchy';

$lang->story->report->options = new stdclass();
$lang->story->report->options->graph  = new stdclass();
$lang->story->report->options->type   = 'pie';
$lang->story->report->options->width  = 500;
$lang->story->report->options->height = 140;

$lang->story->report->storiesPerProduct      = new stdclass();
$lang->story->report->storiesPerModule       = new stdclass();
$lang->story->report->storiesPerSource       = new stdclass();
$lang->story->report->storiesPerPlan         = new stdclass();
$lang->story->report->storiesPerStatus       = new stdclass();
$lang->story->report->storiesPerStage        = new stdclass();
$lang->story->report->storiesPerPri          = new stdclass();
$lang->story->report->storiesPerOpenedBy     = new stdclass();
$lang->story->report->storiesPerAssignedTo   = new stdclass();
$lang->story->report->storiesPerClosedReason = new stdclass();
$lang->story->report->storiesPerEstimate     = new stdclass();
$lang->story->report->storiesPerChange       = new stdclass();
$lang->story->report->storiesPerGrade        = new stdclass();

$lang->story->report->storiesPerProduct->item      = $lang->productCommon;
$lang->story->report->storiesPerModule->item       = 'Module';
$lang->story->report->storiesPerSource->item       = 'Source';
$lang->story->report->storiesPerPlan->item         = 'Plan';
$lang->story->report->storiesPerStatus->item       = 'Status';
$lang->story->report->storiesPerStage->item        = 'Phase';
$lang->story->report->storiesPerPri->item          = 'Priority';
$lang->story->report->storiesPerOpenedBy->item     = 'Creator';
$lang->story->report->storiesPerAssignedTo->item   = 'Assigned to';
$lang->story->report->storiesPerClosedReason->item = 'Reason';
$lang->story->report->storiesPerEstimate->item     = 'Estimated Efforts';
$lang->story->report->storiesPerChange->item       = 'Change Count';
$lang->story->report->storiesPerGrade->item        = 'Hierarchy';

$lang->story->report->storiesPerProduct->graph      = new stdclass();
$lang->story->report->storiesPerModule->graph       = new stdclass();
$lang->story->report->storiesPerSource->graph       = new stdclass();
$lang->story->report->storiesPerPlan->graph         = new stdclass();
$lang->story->report->storiesPerStatus->graph       = new stdclass();
$lang->story->report->storiesPerStage->graph        = new stdclass();
$lang->story->report->storiesPerPri->graph          = new stdclass();
$lang->story->report->storiesPerOpenedBy->graph     = new stdclass();
$lang->story->report->storiesPerAssignedTo->graph   = new stdclass();
$lang->story->report->storiesPerClosedReason->graph = new stdclass();
$lang->story->report->storiesPerEstimate->graph     = new stdclass();
$lang->story->report->storiesPerChange->graph       = new stdclass();
$lang->story->report->storiesPerGrade->graph        = new stdclass();

$lang->story->report->storiesPerProduct->graph->xAxisName      = $lang->productCommon;
$lang->story->report->storiesPerModule->graph->xAxisName       = 'Module';
$lang->story->report->storiesPerSource->graph->xAxisName       = 'Source';
$lang->story->report->storiesPerPlan->graph->xAxisName         = 'Plan';
$lang->story->report->storiesPerStatus->graph->xAxisName       = 'Status';
$lang->story->report->storiesPerStage->graph->xAxisName        = 'Phase';
$lang->story->report->storiesPerPri->graph->xAxisName          = 'Priority';
$lang->story->report->storiesPerOpenedBy->graph->xAxisName     = 'Creator';
$lang->story->report->storiesPerAssignedTo->graph->xAxisName   = 'Assignee';
$lang->story->report->storiesPerClosedReason->graph->xAxisName = 'Close Reason';
$lang->story->report->storiesPerEstimate->graph->xAxisName     = 'Estimated Effort';
$lang->story->report->storiesPerChange->graph->xAxisName       = 'Change Count';
$lang->story->report->storiesPerGrade->graph->xAxisName        = 'Hierarchy';

$lang->story->placeholder = new stdclass();
$lang->story->placeholder->estimate = $lang->story->hour;

$lang->story->chosen = new stdClass();
$lang->story->chosen->reviewedBy = 'Select reviewers';

$lang->story->notice = new stdClass();
$lang->story->notice->closed           = "{$lang->SRCommon} you selected has already been closed!";
$lang->story->notice->reviewerNotEmpty = "This {$lang->SRCommon}  requires a review. Reviewers cannot be empty.";
$lang->story->notice->changePlan       = 'You can link the story to only one plan. Please update it before saving.';
$lang->story->notice->notDeleted       = 'You cannot remove reviewers who have already submitted their review results.';

$lang->story->convertToTask = new stdClass();
$lang->story->convertToTask->fieldList = array();
$lang->story->convertToTask->fieldList['module']     = 'Module';
$lang->story->convertToTask->fieldList['spec']       = "Description";
$lang->story->convertToTask->fieldList['pri']        = 'Priority';
$lang->story->convertToTask->fieldList['mailto']     = 'Mail to';
$lang->story->convertToTask->fieldList['assignedTo'] = 'Assigned to';

$lang->story->categoryList['feature']     = 'Feature';
$lang->story->categoryList['interface']   = 'API';
$lang->story->categoryList['performance'] = 'Performance';
$lang->story->categoryList['safe']        = 'Safety';
$lang->story->categoryList['experience']  = 'User Experience';
$lang->story->categoryList['improve']     = 'Improvement';
$lang->story->categoryList['other']       = 'Others';

$lang->story->frozenTip  = "After the stories are baselined, %s is not allowed.";
$lang->story->frozenTips = "Story %s has been frozen and will not be %s.";

$lang->story->changeTip = 'You can only request changes to active stories.';

$lang->story->reviewTip = array();
$lang->story->reviewTip['active']      = 'This story is already active. No review needed.';
$lang->story->reviewTip['notReviewer'] = 'You’re not a reviewer for this story and can’t perform a review.';
$lang->story->reviewTip['reviewed']    = 'You’ve already reviewed this story.';
$lang->story->reviewTip['noPriv']      = 'You don’t have permission to submit a review.';

$lang->story->recallTip = array();
$lang->story->recallTip['actived'] = 'No review was started for this story, so there’s nothing to revoke.';

$lang->story->subDivideTip = array();
$lang->story->subDivideTip['notWait']    = 'The story has been  %s and cannot be split.';
$lang->story->subDivideTip['notActive']  = "You cannot split stories that are under review or closed.";
$lang->story->subDivideTip['twinsSplit'] = 'Twin stories cannot be split.';

$lang->story->featureBar['browse']['all']       = $lang->all;
$lang->story->featureBar['browse']['unclosed']  = $lang->story->unclosed;
$lang->story->featureBar['browse']['draft']     = $lang->story->statusList['draft'];
$lang->story->featureBar['browse']['reviewing'] = $lang->story->statusList['reviewing'];

$lang->story->operateList = array();
$lang->story->operateList['assigned']       = 'Assign';
$lang->story->operateList['closed']         = 'Close';
$lang->story->operateList['activated']      = 'Activate';
$lang->story->operateList['changed']        = 'Change';
$lang->story->operateList['reviewed']       = 'Review';
$lang->story->operateList['edited']         = 'Edit';
$lang->story->operateList['submitreview']   = 'submit review';
$lang->story->operateList['recalledchange'] = 'Revoke Change';
$lang->story->operateList['recalled']       = 'Revoke Review';

$lang->story->addBranch      = 'Add %s';
$lang->story->deleteBranch   = 'Delete %s';
$lang->story->notice->branch = "A separate story is created for each branch, and these stories are linked as twins. All fields are synchronized between twin stories, except for {$lang->productCommon}, Branch, Module, Plan, and Phase. You can manually unlink the twin relationship at any time.";

$lang->story->relievedTwinsRelation     = 'Unlink Twin Relationship';
$lang->story->relievedTwinsRelationTips = 'Once unlinked, the twin relationship cannot be restored, and closing one story will no longer affect synchronously.';
$lang->story->changeRelievedTwinsTips   = 'Once unlinked, the twin relationship cannot be restored, and changes to one story will no longer affect synchronously.';
$lang->story->cannotRejectTips          = '“%s” has been modified and cannot be rejected in review. this action has been ignored.';

$lang->story->trackOrderByList['id']       = 'Sort by ID';
$lang->story->trackOrderByList['pri']      = 'Sort by Priority';
$lang->story->trackOrderByList['status']   = 'Sort by Status';
$lang->story->trackOrderByList['stage']    = 'Sort by Phase';
$lang->story->trackOrderByList['category'] = 'Sort by Type';

$lang->story->trackSortList['asc']  = 'Ascending';
$lang->story->trackSortList['desc'] = 'Descending';

$lang->story->error = new stdclass();
$lang->story->error->length = "The input exceeds %d characters and cannot be saved. Please shorten it and try again.";
