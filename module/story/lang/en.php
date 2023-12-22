<?php
/**
 * The story module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: en.php 5141 2013-07-15 05:57:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
global $config;
$lang->story->create            = "Create Story";

$lang->story->requirement       = 'UR';
$lang->story->story             = zget($lang, 'SRCommon', "Story");
$lang->story->createStory       = 'Create ' . $lang->story->story;
$lang->story->createRequirement = 'Create ' . $lang->story->requirement;
$lang->story->affectedStories   = "Affected {$lang->story->story}";

$lang->story->batchCreate        = "Batch Create";
$lang->story->change             = "Change";
$lang->story->changed            = 'Change';
$lang->story->assignTo           = 'Assign';
$lang->story->review             = 'Review';
$lang->story->submitReview       = "Submit Review";
$lang->story->recall             = 'Revoke';
$lang->story->recallChange       = 'Undo Changes';
$lang->story->recallAction       = 'Undo';
$lang->story->needReview         = 'Need Review';
$lang->story->batchReview        = 'Batch Review';
$lang->story->edit               = "Edit Story";
$lang->story->editDraft          = "Edit Draft";
$lang->story->batchEdit          = "Batch Edit";
$lang->story->subdivide          = 'Decompose';
$lang->story->subdivideSR        = $lang->SRCommon . 'Decompose';
$lang->story->link               = 'Link';
$lang->story->unlink             = 'Unlink';
$lang->story->track              = 'Track';
$lang->story->trackAB            = 'Track';
$lang->story->processStoryChange = 'Confirm Story Change';
$lang->story->splitRequirent     = 'Decompose';
$lang->story->close              = 'Close';
$lang->story->batchClose         = 'Batch Close';
$lang->story->activate           = 'Activate';
$lang->story->delete             = "Delete";
$lang->story->view               = "Story Detail";
$lang->story->setting            = "Settings";
$lang->story->tasks              = "Linked Tasks";
$lang->story->bugs               = "Linked Bugs";
$lang->story->cases              = "Linked Cases";
$lang->story->taskCount          = 'Tasks';
$lang->story->bugCount           = 'Bugs';
$lang->story->caseCount          = 'Cases';
$lang->story->taskCountAB        = 'T';
$lang->story->bugCountAB         = 'B';
$lang->story->caseCountAB        = 'C';
$lang->story->linkStory          = 'Link Requirement';
$lang->story->unlinkStory        = 'UnLinked';
$lang->story->linkStoriesAB      = "Link {$lang->SRCommon}";
$lang->story->linkRequirementsAB = "Link {$lang->URCommon}";
$lang->story->export             = "Export Data";
$lang->story->zeroCase           = "Stories without cases";
$lang->story->zeroTask           = "Only list stories without tasks";
$lang->story->reportChart        = "Report";
$lang->story->copyTitle          = "Copy Title";
$lang->story->batchChangePlan    = "Batch Change Plans";
$lang->story->batchChangeBranch  = "Batch Change Branches";
$lang->story->batchChangeStage   = "Batch Change Phases";
$lang->story->batchAssignTo      = "Batch Assign";
$lang->story->batchChangeModule  = "Batch Change Modules";
$lang->story->viewAll            = "See All";
$lang->story->toTask             = 'Convert to Task';
$lang->story->batchToTask        = 'Batch Convert to Task';
$lang->story->convertRelations   = 'Convert Relations';
$lang->story->undetermined       = 'undetermined';
$lang->story->order              = 'Order';
$lang->story->saveDraft          = 'Save as draft';
$lang->story->doNotSubmit        = 'Do Not Submit';
$lang->story->currentBranch      = 'Current Branch';
$lang->story->twins              = 'Twins story';
$lang->story->relieved           = 'Relieved';
$lang->story->relievedTwins      = 'Relieved Twins';
$lang->story->loadAllStories     = 'Load all stories';
$lang->story->hasDividedTask     = 'has divided task';

$lang->story->editAction      = "Edit {$lang->SRCommon}";
$lang->story->changeAction    = "Change {$lang->SRCommon}";
$lang->story->assignAction    = "Assign {$lang->SRCommon}";
$lang->story->reviewAction    = "Review {$lang->SRCommon}";
$lang->story->subdivideAction = "Subdivide {$lang->SRCommon}";
$lang->story->closeAction     = "Close {$lang->SRCommon}";
$lang->story->activateAction  = "Activate {$lang->SRCommon}";
$lang->story->deleteAction    = "Delete {$lang->SRCommon}";
$lang->story->exportAction    = "Export {$lang->SRCommon}";
$lang->story->reportAction    = "Report";

$lang->story->skipStory        = '%s is a parent story. It cannot be closed.';
$lang->story->closedStory      = 'Story %s is closed and will not be closed.';
$lang->story->batchToTaskTips  = "The closed {$lang->SRCommon} will not be converted into tasks.";
$lang->story->successToTask    = "Converted to task.";
$lang->story->storyRound       = '%s time estimation';
$lang->story->float            = "『%s』should have positive number, or decimals.";
$lang->story->saveDraftSuccess = 'Save as draft succeeded.';

$lang->story->changeSyncTip       = "The modification of this story will be synchronized to the following twin requirements";
$lang->story->syncTip             = "The twin story are synchronized except for {$lang->productCommon}, branch, module, plan, and stage. After the twin relationship is dissolved, they are no longer synchronized.";
$lang->story->relievedTip         = "The twin relationship cannot be restored after dissolving, the content of the demand is no longer synchronized, whether to dissolving?";
$lang->story->assignSyncTip       = "Both twin stories modify the assignor synchronously";
$lang->story->closeSyncTip        = "Twin stories are closed synchronously";
$lang->story->activateSyncTip     = "Twin stories are activated synchronously";
$lang->story->relievedTwinsTip    = "After {$lang->productCommon} adjustment, the twin relationship of this story will be automatically removed, and the story will no longer be synchronized. Do you want to save?";
$lang->story->batchEditTip        = "{$lang->SRCommon} %sis twin stories, and this operation has been filtered.";

$lang->story->id               = 'ID';
$lang->story->parent           = 'Parent';
$lang->story->product          = $lang->productCommon;
$lang->story->project          = $lang->projectCommon;
$lang->story->branch           = "Branch/Platform";
$lang->story->module           = 'Module';
$lang->story->moduleAB         = 'Module';
$lang->story->roadmap          = 'Roadmap';
$lang->story->source           = 'From';
$lang->story->sourceNote       = 'Note';
$lang->story->fromBug          = 'From Bug';
$lang->story->title            = 'Title';
$lang->story->type             = "Story/Requirement";
$lang->story->category         = 'Category';
$lang->story->color            = 'Color';
$lang->story->toBug            = 'ToBug';
$lang->story->spec             = 'Description';
$lang->story->assign           = 'Assign';
$lang->story->verify           = 'Acceptance';
$lang->story->pri              = 'Priority';
$lang->story->estimate         = "Estimates";
$lang->story->estimateAB       = 'Est';
$lang->story->hour             = $lang->hourCommon;
$lang->story->consumed         = 'Consumed';
$lang->story->status           = 'Status';
$lang->story->statusAB         = 'Status';
$lang->story->subStatus        = 'Sub Status';
$lang->story->stage            = 'Phase';
$lang->story->stageAB          = 'Phase';
$lang->story->stagedBy         = 'SetBy';
$lang->story->mailto           = 'Mailto';
$lang->story->openedBy         = 'Created By';
$lang->story->openedByAB       = 'Created';
$lang->story->openedDate       = 'Created Date';
$lang->story->assignedTo       = 'AssignTo';
$lang->story->assignedToAB     = 'Assign';
$lang->story->assignedDate     = 'AssignedDate';
$lang->story->lastEditedBy     = 'EditedBy';
$lang->story->lastEditedByAB   = 'LasteditedBy';
$lang->story->lastEditedDate   = 'EditedDate';
$lang->story->closedBy         = 'ClosedBy';
$lang->story->closedDate       = 'ClosedDate';
$lang->story->closedReason     = 'Reason';
$lang->story->rejectedReason   = 'Reject Reason';
$lang->story->changedBy        = 'ChangedBy';
$lang->story->changedDate      = 'ChangedDate';
$lang->story->reviewedBy       = 'ReviewedBy';
$lang->story->reviewer         = 'Reviewers';
$lang->story->reviewers        = 'Reviewers';
$lang->story->reviewedDate     = 'ReviewedDate';
$lang->story->activatedDate    = 'Activated Date';
$lang->story->version          = 'Version';
$lang->story->feedbackBy       = 'From Name';
$lang->story->notifyEmail      = 'From Email';
$lang->story->plan             = 'Linked Plan';
$lang->story->planAB           = 'Plan';
$lang->story->comment          = 'Comment';
$lang->story->children         = "Child {$lang->SRCommon}";
$lang->story->childrenAB       = "C";
$lang->story->linkStories      = 'Linked Story';
$lang->story->linkRequirements = "Linked {$lang->URCommon}";
$lang->story->childStories     = 'Decomposed Story';
$lang->story->duplicateStory   = 'Duplicated Story';
$lang->story->reviewResult     = 'Review Result';
$lang->story->reviewResultAB   = 'Assessment results';
$lang->story->preVersion       = 'Last Version';
$lang->story->keywords         = 'Tags';
$lang->story->newStory         = 'Continue adding';
$lang->story->colorTag         = 'Color';
$lang->story->files            = 'Files';
$lang->story->copy             = "Copy Story";
$lang->story->total            = "Total Stories";
$lang->story->draft            = 'Draft';
$lang->story->unclosed         = 'Unclosed';
$lang->story->deleted          = 'Deleted';
$lang->story->released         = 'Released Stories';
$lang->story->URChanged        = 'Requirement Changed';
$lang->story->design           = 'Design';
$lang->story->case             = 'Cases';
$lang->story->bug              = 'Bugs';
$lang->story->repoCommit       = 'Commits';
$lang->story->one              = 'One';
$lang->story->field            = 'Sync Field';
$lang->story->completeRate     = 'Completion Rate';
$lang->story->reviewed         = 'Reviewed';
$lang->story->toBeReviewed     = 'To Be Reviewed';
$lang->story->linkMR           = 'Related MRs';
$lang->story->linkCommit       = 'Related Commits';

$lang->story->ditto       = 'Ditto';
$lang->story->dittoNotice = "This story is not linked to the same {$lang->productCommon} as the last one is!";

$lang->story->needNotReviewList[0] = 'Need Review';
$lang->story->needNotReviewList[1] = 'No Review';

$lang->story->useList[0] = 'Yes';
$lang->story->useList[1] = 'No';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = 'Draft';
$lang->story->statusList['reviewing'] = 'Reviewing';
$lang->story->statusList['active']    = 'Active';
$lang->story->statusList['closed']    = 'Closed';
$lang->story->statusList['changing']  = 'Changing';

if($config->systemMode == 'PLM')
{
    $lang->story->statusList['launched']   = 'Launched';
    $lang->story->statusList['developing'] = 'Developing';
}

$lang->story->stageList = array();
$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = 'Waiting';
$lang->story->stageList['planned']    = 'Planned';
$lang->story->stageList['projected']  = 'Projected';
$lang->story->stageList['developing'] = 'Developing';
$lang->story->stageList['developed']  = 'Developed';
$lang->story->stageList['testing']    = 'Testing';
$lang->story->stageList['tested']     = 'Tested';
$lang->story->stageList['verified']   = 'Accepted';
$lang->story->stageList['released']   = 'Released';
$lang->story->stageList['closed']     = 'Closed';

$lang->story->reasonList['']           = '';
$lang->story->reasonList['done']       = 'Already Done';
$lang->story->reasonList['subdivided'] = 'Decomposed';
$lang->story->reasonList['duplicate']  = 'Duplicate';
$lang->story->reasonList['postponed']  = 'Postponed';
$lang->story->reasonList['willnotdo']  = "Won't Do";
$lang->story->reasonList['cancel']     = 'Cancelled';
$lang->story->reasonList['bydesign']   = 'As Designed';
//$lang->story->reasonList['isbug']      = 'Bug!';

$lang->story->reviewResultList['']        = '';
$lang->story->reviewResultList['pass']    = 'Pass';
$lang->story->reviewResultList['revert']  = 'Revert';
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
$lang->story->sourceList['tester']     = 'QA Team';
$lang->story->sourceList['bug']        = 'Bug';
$lang->story->sourceList['forum']      = 'Forum';
$lang->story->sourceList['other']      = 'Others';

$lang->story->priList[]  = '';
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
$lang->story->legendMailto         = 'Mailto';
$lang->story->legendAttatch        = 'Files';
$lang->story->legendProjectAndTask = $lang->executionCommon . ' And Task';
$lang->story->legendBugs           = 'Linked Bugs';
$lang->story->legendFromBug        = 'From Bug';
$lang->story->legendCases          = 'Linked Cases';
$lang->story->legendBuilds         = 'Linked Builds';
$lang->story->legendReleases       = 'Linked Releases';
$lang->story->legendLinkStories    = 'Linked Stories';
$lang->story->legendChildStories   = 'Child Stories';
$lang->story->legendSpec           = 'Description';
$lang->story->legendVerify         = 'Acceptance';
$lang->story->legendMisc           = 'Misc.';
$lang->story->legendInformation    = 'Story Information';

$lang->story->lblChange   = 'Change';
$lang->story->lblReview   = 'Review';
$lang->story->lblActivate = 'Activate';
$lang->story->lblClose    = 'Close';
$lang->story->lblTBC      = 'Task/Bug/Case';

$lang->story->checkAffection       = 'Influence';
$lang->story->affectedProjects     = "{$lang->project->common}s/{$lang->execution->common}s";
$lang->story->affectedBugs         = 'Bugs';
$lang->story->affectedCases        = 'Cases';
$lang->story->affectedTwins        = 'Twins';

$lang->story->specTemplate         = "As a < type of user >, I want < some goal > so that < some reason >.";
$lang->story->needNotReview        = 'No Review';
$lang->story->successSaved         = "Story is saved!";
$lang->story->confirmDelete        = "Do you want to delete this story?";
$lang->story->confirmRecall        = "Do you want to recall this story?";
$lang->story->errorEmptyChildStory = '『Decomposed Stories』canot be blank.';
$lang->story->errorNotSubdivide    = "If the status is not active, or the stage is not wait, or a sub story, it cannot be subdivided.";
$lang->story->errorEmptyReviewedBy = "『ReviewedBy』canot be blank.";
$lang->story->mustChooseResult     = 'Select Result';
$lang->story->mustChoosePreVersion = 'Select a version to revert to.';
$lang->story->noStory              = 'No stories yet. ';
$lang->story->noRequirement        = 'No requirements yet. ';
$lang->story->noRelatedRequirement = "No related requirements.";
$lang->story->ignoreChangeStage    = 'The status of %s is Draft or Closed. This operation has been filtered.';
$lang->story->cannotDeleteParent   = "Can not delete parent {$lang->SRCommon}";
$lang->story->moveChildrenTips     = "Its Child {$lang->SRCommon} will be moved to the selected {$lang->productCommon} when editing the linked {$lang->productCommon} of Parent {$lang->SRCommon}.";
$lang->story->changeTips           = 'The story associated with the requirements to change, click "Cancel" ignore this change, click "Confirm" to change the story.';
$lang->story->estimateMustBeNumber = 'Estimate value must be number.';
$lang->story->estimateMustBePlus   = 'Estimated value cannot be negative';
$lang->story->confirmChangeBranch  = $lang->SRCommon . ' %s is linked to the plan of its linked branch. If the branch is edited, ' . $lang->SRCommon . ' will be removed from the plan of its linked branch. Do you want to continue edit ' . $lang->SRCommon . '?';
$lang->story->confirmChangePlan    = $lang->SRCommon . ' %s is linked to the branch of its plan. If the branch is edited, ' . $lang->SRCommon . ' will be removed from the plan. Do you want to continue edit branch ?';
$lang->story->errorDuplicateStory  = $lang->SRCommon . '%s not exist';
$lang->story->confirmRecallChange  = "After undo the change, the story content will revert to the version before the change. Are you sure you want to undo?";
$lang->story->confirmRecallReview  = "Are you sure you want to withdraw the review?";
$lang->story->noStoryToTask        = "Only the activated {$lang->SRCommon} can be converted into a task!";
$lang->story->ignoreClosedStory    = "{$lang->SRCommon} %s status is closed, and the operation has been filtered.";

$lang->story->form = new stdclass();
$lang->story->form->area     = 'Scope';
$lang->story->form->desc     = 'What story is it? What is the acceptance?';
$lang->story->form->resource = 'Who will allocate resources? How long does it take?';
$lang->story->form->file     = 'If any file that is linked to a story, please click Here to upload it.';

$lang->story->action = new stdclass();
$lang->story->action->reviewed              = array('main' => '$date, recorded by <strong>$actor</strong>. The result is <strong>$extra</strong>.', 'extra' => 'reviewResultList');
$lang->story->action->rejectreviewed        = array('main' => '$date, recorded by <strong>$actor</strong>. The result is <strong>$extra</strong>. The reason is <strong>$reason</strong>.', 'extra' => 'reviewResultList', 'reason' => 'reasonList');
$lang->story->action->recalled              = array('main' => '$date, recalled by <strong>$actor</strong>.');
$lang->story->action->closed                = array('main' => '$date, closed by <strong>$actor</strong>. The reason is <strong>$extra</strong> $appendLink.', 'extra' => 'reasonList');
$lang->story->action->closedbysystem        = array('main' => '$date, The system determines that the parent story is automatically closed because all child stories are closed.');
$lang->story->action->reviewpassed          = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>Pass</strong>.');
$lang->story->action->reviewrejected        = array('main' => '$date, closed by <strong>System</strong>. The reason is <strong>Rejection</strong>.');
$lang->story->action->reviewclarified       = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>To Be Clarified</strong>. Please re-initiate the review after edit.');
$lang->story->action->reviewreverted        = array('main' => '$date, determined by the <strong>System</strong>. The result is <strong>Undo Change</strong>.');
$lang->story->action->linked2plan           = array('main' => '$date, linked by <strong>$actor</strong> to Plan <strong>$extra</strong>');
$lang->story->action->unlinkedfromplan      = array('main' => '$date, unlinked by <strong>$actor</strong> from Plan <strong>$extra</strong>.');
$lang->story->action->linked2execution      = array('main' => '$date, linked by <strong>$actor</strong> to ' . $lang->executionCommon . ' <strong>$extra</strong>.');
$lang->story->action->unlinkedfromexecution = array('main' => '$date, unlinked by <strong>$actor</strong> from ' . $lang->executionCommon . ' <strong>$extra</strong>.');
$lang->story->action->linked2kanban         = array('main' => '$date, linked by <strong>$actor</strong> to Kanban <strong>$extra</strong>.');
$lang->story->action->linked2project        = array('main' => '$date, linked by <strong>$actor</strong> ' . "to {$lang->projectCommon}" . ' <strong>$extra</strong>.');
$lang->story->action->unlinkedfromproject   = array('main' => '$date, unlinked by <strong>$actor</strong> ' . "from {$lang->projectCommon}" . ' <strong>$extra</strong>.');
$lang->story->action->linked2build          = array('main' => '$date, linked by <strong>$actor</strong> to Build <strong>$extra</strong>');
$lang->story->action->unlinkedfrombuild     = array('main' => '$date, unlinked by <strong>$actor</strong> from Build <strong>$extra</strong>.');
$lang->story->action->linked2release        = array('main' => '$date, linked by <strong>$actor</strong> to Release <strong>$extra</strong>');
$lang->story->action->unlinkedfromrelease   = array('main' => '$date, unlinked by <strong>$actor</strong> from Release <strong>$extra</strong>.');
$lang->story->action->linked2revision       = array('main' => '$date, linked by <strong>$actor</strong> to Revision <strong>$extra</strong>');
$lang->story->action->unlinkedfromrevision  = array('main' => '$date, unlinked by <strong>$actor</strong> to Revision <strong>$extra</strong>');
$lang->story->action->linkrelatedstory      = array('main' => '$date, linked by <strong>$actor</strong> to Story <strong>$extra</strong>.');
$lang->story->action->subdividestory        = array('main' => '$date, decomposed by <strong>$actor</strong> to Story <strong>$extra</strong>.');
$lang->story->action->unlinkrelatedstory    = array('main' => '$date, unlinked by <strong>$actor</strong> from Story <strong>$extra</strong>.');
$lang->story->action->unlinkchildstory      = array('main' => '$date, unlinked by <strong>$actor</strong> Decomposed Story <strong>$extra</strong>.');
$lang->story->action->recalledchange        = array('main' => '$date, Undo changes by <strong>\$actor</strong>.');
$lang->story->action->synctwins             = array('main' => "\$date, the system judges that this story is adjusted synchronously due to the \$operate of twin story <strong>\$extra</strong>.", 'operate' => 'operateList');
$lang->story->action->linked2roadmap        = array('main' => '$date, linked by <strong>$actor</strong> to Roadmap <strong>$extra</strong>');
$lang->story->action->unlinkedfromroadmap   = array('main' => '$date, unlinked by <strong>$actor</strong> from Roadmap <strong>$extra</strong>.');
$lang->story->action->changedbycharter      = array('main' => '$date, launched by <strong>$actor</strong> for charter <strong>$extra</strong>, Synchronously adjust the story status to launched.');

/* Statistical statement. */
$lang->story->report = new stdclass();
$lang->story->report->common = 'Report';
$lang->story->report->select = 'Select Report Type';
$lang->story->report->create = 'Create Report';
$lang->story->report->value  = 'Reports';

$lang->story->report->charts['storysPerProduct']      = 'Group by ' . $lang->productCommon . ' Story';
$lang->story->report->charts['storysPerModule']       = 'Group by Module Story';
$lang->story->report->charts['storysPerSource']       = 'Group by Story Source';
$lang->story->report->charts['storysPerPlan']         = 'Group by Plan';
$lang->story->report->charts['storysPerStatus']       = 'Group by Status';
$lang->story->report->charts['storysPerStage']        = 'Group by Phase';
$lang->story->report->charts['storysPerPri']          = 'Group by Priority';
$lang->story->report->charts['storysPerEstimate']     = 'Group by Estimates';
$lang->story->report->charts['storysPerOpenedBy']     = 'Group by CreatedBy';
$lang->story->report->charts['storysPerAssignedTo']   = 'Group by AssignedTo';
$lang->story->report->charts['storysPerClosedReason'] = 'Group by Closed Reason';
$lang->story->report->charts['storysPerChange']       = 'Group by Changed Story';

$lang->story->report->options = new stdclass();
$lang->story->report->options->graph  = new stdclass();
$lang->story->report->options->type   = 'pie';
$lang->story->report->options->width  = 500;
$lang->story->report->options->height = 140;

$lang->story->report->storysPerProduct      = new stdclass();
$lang->story->report->storysPerModule       = new stdclass();
$lang->story->report->storysPerSource       = new stdclass();
$lang->story->report->storysPerPlan         = new stdclass();
$lang->story->report->storysPerStatus       = new stdclass();
$lang->story->report->storysPerStage        = new stdclass();
$lang->story->report->storysPerPri          = new stdclass();
$lang->story->report->storysPerOpenedBy     = new stdclass();
$lang->story->report->storysPerAssignedTo   = new stdclass();
$lang->story->report->storysPerClosedReason = new stdclass();
$lang->story->report->storysPerEstimate     = new stdclass();
$lang->story->report->storysPerChange       = new stdclass();

$lang->story->report->storysPerProduct->item      = $lang->productCommon;
$lang->story->report->storysPerModule->item       = 'Module';
$lang->story->report->storysPerSource->item       = 'Source';
$lang->story->report->storysPerPlan->item         = 'Plan';
$lang->story->report->storysPerStatus->item       = 'Status';
$lang->story->report->storysPerStage->item        = 'Phase';
$lang->story->report->storysPerPri->item          = 'Priority';
$lang->story->report->storysPerOpenedBy->item     = 'OpenedBy';
$lang->story->report->storysPerAssignedTo->item   = 'AssignedTo';
$lang->story->report->storysPerClosedReason->item = 'Reason';
$lang->story->report->storysPerEstimate->item     = 'Estimates';
$lang->story->report->storysPerChange->item       = 'Changed Story';

$lang->story->report->storysPerProduct->graph      = new stdclass();
$lang->story->report->storysPerModule->graph       = new stdclass();
$lang->story->report->storysPerSource->graph       = new stdclass();
$lang->story->report->storysPerPlan->graph         = new stdclass();
$lang->story->report->storysPerStatus->graph       = new stdclass();
$lang->story->report->storysPerStage->graph        = new stdclass();
$lang->story->report->storysPerPri->graph          = new stdclass();
$lang->story->report->storysPerOpenedBy->graph     = new stdclass();
$lang->story->report->storysPerAssignedTo->graph   = new stdclass();
$lang->story->report->storysPerClosedReason->graph = new stdclass();
$lang->story->report->storysPerEstimate->graph     = new stdclass();
$lang->story->report->storysPerChange->graph       = new stdclass();

$lang->story->report->storysPerProduct->graph->xAxisName      = $lang->productCommon;
$lang->story->report->storysPerModule->graph->xAxisName       = 'Module';
$lang->story->report->storysPerSource->graph->xAxisName       = 'Source';
$lang->story->report->storysPerPlan->graph->xAxisName         = 'Plan';
$lang->story->report->storysPerStatus->graph->xAxisName       = 'Status';
$lang->story->report->storysPerStage->graph->xAxisName        = 'Phase';
$lang->story->report->storysPerPri->graph->xAxisName          = 'Priority';
$lang->story->report->storysPerOpenedBy->graph->xAxisName     = 'CreatedBy';
$lang->story->report->storysPerAssignedTo->graph->xAxisName   = 'AssignedTo';
$lang->story->report->storysPerClosedReason->graph->xAxisName = 'Close Reason';
$lang->story->report->storysPerEstimate->graph->xAxisName     = 'Estimates ';
$lang->story->report->storysPerChange->graph->xAxisName       = 'Change Times';

$lang->story->placeholder = new stdclass();
$lang->story->placeholder->estimate = $lang->story->hour;

$lang->story->chosen = new stdClass();
$lang->story->chosen->reviewedBy = 'Choose ReviewedBy';

$lang->story->notice = new stdClass();
$lang->story->notice->closed           = 'Story that you select is closed!';
$lang->story->notice->reviewerNotEmpty = 'This requirement needs to be reviewed, and the reviewedby is required.';
$lang->story->notice->changePlan       = 'The plan can be changed to only one item.';

$lang->story->convertToTask = new stdClass();
$lang->story->convertToTask->fieldList = array();
$lang->story->convertToTask->fieldList['module']     = 'Module';
$lang->story->convertToTask->fieldList['spec']       = "Description";
$lang->story->convertToTask->fieldList['pri']        = 'Priority';
$lang->story->convertToTask->fieldList['mailto']     = 'Mailto';
$lang->story->convertToTask->fieldList['assignedTo'] = 'AssignTo';

$lang->story->categoryList['feature']     = 'Feature';
$lang->story->categoryList['interface']   = 'Interface';
$lang->story->categoryList['performance'] = 'Performance';
$lang->story->categoryList['safe']        = 'Safe';
$lang->story->categoryList['experience']  = 'Experience';
$lang->story->categoryList['improve']     = 'Improve';
$lang->story->categoryList['other']       = 'Other';

$lang->story->changeTip = 'Only active can be changed.';

$lang->story->reviewTip = array();
$lang->story->reviewTip['active']      = 'The Story is already active,no review requirements.';
$lang->story->reviewTip['notReviewer'] = 'You are not the reviewer of this Story and cannot perform review operations.';
$lang->story->reviewTip['reviewed']    = 'Reviewed';

$lang->story->recallTip = array();
$lang->story->recallTip['actived'] = 'The Story has not initiated a review process and no undo action is required.';

$lang->story->subDivideTip = array();
$lang->story->subDivideTip['subStory']   = 'The Sub-stories cannot be subdivided.';
$lang->story->subDivideTip['notWait']    = 'The Story has been %s and cannot be subdivided.';
$lang->story->subDivideTip['notActive']  = "The %s is not active and cannot be subdivided.";
$lang->story->subDivideTip['twinsSplit'] = 'The Twins Story cannot be subdivided.';

$lang->story->featureBar['browse']['all']       = $lang->all;
$lang->story->featureBar['browse']['unclosed']  = $lang->story->unclosed;
$lang->story->featureBar['browse']['draft']     = $lang->story->statusList['draft'];
$lang->story->featureBar['browse']['reviewing'] = $lang->story->statusList['reviewing'];

$lang->story->operateList = array();
$lang->story->operateList['assigned']       = 'assigned';
$lang->story->operateList['closed']         = 'closed';
$lang->story->operateList['activated']      = 'activated';
$lang->story->operateList['changed']        = 'changed';
$lang->story->operateList['reviewed']       = 'reviewed';
$lang->story->operateList['edited']         = 'edited';
$lang->story->operateList['submitreview']   = 'submit review';
$lang->story->operateList['recalledchange'] = 'recalled change';
$lang->story->operateList['recalled']       = 'recalled review';

$lang->requirement->common             = $lang->URCommon;
$lang->requirement->create             = 'Create Requirement';
$lang->requirement->batchCreate        = "Batch Create";
$lang->requirement->editAction         = "Edit {$lang->URCommon}";
$lang->requirement->changeAction       = "Change {$lang->URCommon}";
$lang->requirement->assignAction       = "Assign {$lang->URCommon}";
$lang->requirement->reviewAction       = "Review {$lang->URCommon}";
$lang->requirement->subdivideAction    = "Subdivide {$lang->URCommon}";
$lang->requirement->closeAction        = "Close {$lang->URCommon}";
$lang->requirement->activateAction     = "Activate {$lang->URCommon}";
$lang->requirement->deleteAction       = "Delete {$lang->URCommon}";
$lang->requirement->exportAction       = "Export {$lang->URCommon}";
$lang->requirement->reportAction       = "Report";
$lang->requirement->recall             = $lang->story->recallAction;
$lang->requirement->batchReview        = 'Batch Review';
$lang->requirement->batchEdit          = "Batch Edit";
$lang->requirement->batchClose         = 'Batch Close';
$lang->requirement->view               = 'Requirement Detail';
$lang->requirement->linkRequirementsAB = "Link {$lang->URCommon}";
$lang->requirement->batchChangeBranch  = "Batch Change Branches";
$lang->requirement->batchAssignTo      = "Batch Assign";
$lang->requirement->batchChangeModule  = "Batch Change Modules";
$lang->requirement->submitReview       = $lang->story->submitReview;
$lang->requirement->linkStory          = 'Link Story';

$lang->story->addBranch      = 'Add %s';
$lang->story->deleteBranch   = 'Delete %s';
$lang->story->notice->branch = 'Each branch will establish a requirement. The requirements are twins. The twins requirements are synchronized except for the product, branch, module, plan, and stage fields. You can manually remove the twins relationship later';

$lang->story->relievedTwinsRelation     = 'Relieved twins relationship';
$lang->story->relievedTwinsRelationTips = 'After the twins relationship is terminated, it cannot be restored and the shutdown of the requirement is no longer synchronized.';
$lang->story->changeRelievedTwinsTips   = 'After the twins relationship is terminated, the twin stories are no longer synchronized.';
$lang->story->storyUnlinkRoadmap        = 'This story was launched and then removed from the roadmap, and needs to be launched again before it can be viewed in the IPD rnd management page.';
