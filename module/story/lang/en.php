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
$lang->story->relation           = 'Relations';
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
$lang->story->storyChange        = 'Story Change';
$lang->story->upstreamDemand     = 'Upstream Demand';
$lang->story->split              = 'Decompose';
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
$lang->story->linkStory          = "Link Story";
$lang->story->unlinkStory        = "Do you confirm unlink this story?";
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
$lang->story->batchChangeParent  = "Batch Change Parent";
$lang->story->batchChangeGrade   = "Batch Change Grade";
$lang->story->changeParent       = "Change Parent";
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
$lang->story->loadAllStories     = 'All';
$lang->story->hasDividedTask     = 'has divided task';
$lang->story->hasDividedCase     = 'has divided case';
$lang->story->viewAllGrades      = 'View All Grades';
$lang->story->codeBranch         = 'Code branch';
$lang->story->unlinkBranch       = 'Unlink code branch';
$lang->story->branchName         = 'Branch Name';
$lang->story->branchFrom         = 'Create from';
$lang->story->codeRepo           = 'Code Library';
$lang->story->viewByType         = "View By %s";

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

$lang->story->closedStory      = "{$lang->SRCommon} %s is closed, and this operation has been filtered.";
$lang->story->batchToTaskTips  = "Only active {$lang->SRCommon} can be converted into tasks.";
$lang->story->successToTask    = "Converted to task.";
$lang->story->storyRound       = '%s time estimation';
$lang->story->float            = "『%s』should have positive number, or decimals.";
$lang->story->saveDraftSuccess = 'Save as draft succeeded.';

$lang->story->changeSyncTip    = "The modification of this story will be synchronized to the following twin requirements";
$lang->story->syncTip          = "The twin story are synchronized except for {$lang->productCommon}, branch, module, plan, and stage. After the twin relationship is dissolved, they are no longer synchronized.";
$lang->story->relievedTip      = "The twin relationship cannot be restored after dissolving, the content of the demand is no longer synchronized, whether to dissolving?";
$lang->story->assignSyncTip    = "Both twin stories modify the assignor synchronously";
$lang->story->closeSyncTip     = "Twin stories are closed synchronously";
$lang->story->activateSyncTip  = "Twin stories are activated synchronously";
$lang->story->relievedTwinsTip = "After {$lang->productCommon} adjustment, the twin relationship of this story will be automatically removed, and the story will no longer be synchronized. Do you want to save?";
$lang->story->batchEditTip     = "{$lang->SRCommon} %sis twin stories, and this operation has been filtered.";
$lang->story->planTip          = "{$lang->SRCommon} only supports single selection plan, other requirements can select multiple plans.";
$lang->story->batchEditError   = "All selected {$lang->SRCommon} can not be edited.";

$lang->story->id               = 'ID';
$lang->story->parent           = 'Parent';
$lang->story->isParent         = 'Is Parent';
$lang->story->grade            = 'Grade';
$lang->story->gradeName        = 'Grade Name';
$lang->story->path             = 'Path';
$lang->story->product          = $lang->productCommon;
$lang->story->project          = $lang->projectCommon;
$lang->story->execution        = "Execution";
$lang->story->branch           = "Branch/Platform";
$lang->story->module           = 'Module';
$lang->story->moduleAB         = 'Module';
$lang->story->roadmap          = 'Roadmap';
$lang->story->source           = 'From';
$lang->story->sourceNote       = 'Note';
$lang->story->fromBug          = 'From Bug';
$lang->story->title            = 'Title';
$lang->story->name             = "Name";
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
$lang->story->reviewer         = 'Reviewer';
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
$lang->story->childItem        = "Child Item";
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
$lang->story->linkPR           = 'Related PRs';
$lang->story->linkCommit       = 'Related Commits';
$lang->story->URS              = 'User requirements';
$lang->story->estimateUnit     = "(Unit: {$lang->story->hour})";
$lang->story->verifiedDate     = 'Verified Date';

$lang->story->ditto       = 'Ditto';
$lang->story->dittoNotice = "This story is not linked to the same {$lang->productCommon} as the last one is!";

$lang->story->viewTypeList['tiled'] = 'Tiled';
$lang->story->viewTypeList['tree']  = 'Tree';

if($config->enableER) $lang->story->typeList['epic']        = $lang->ERCommon;
if($config->URAndSR)  $lang->story->typeList['requirement'] = $lang->URCommon;
$lang->story->typeList['story'] = $lang->SRCommon;

$lang->story->needNotReviewList[0] = 'Need Review';
$lang->story->needNotReviewList[1] = 'No Review';

$lang->story->useList[0] = 'Yes';
$lang->story->useList[1] = 'No';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = 'Draft';
$lang->story->statusList['reviewing'] = 'Reviewing';
$lang->story->statusList['active']    = 'Active';
$lang->story->statusList['changing']  = 'Changing';
$lang->story->statusList['closed']    = 'Closed';

$lang->story->stageList['']           = '';
$lang->story->stageList['wait']       = 'Waiting';
$lang->story->stageList['planned']    = 'Planned';
$lang->story->stageList['projected']  = 'Projected';
$lang->story->stageList['designing']  = 'Designing';
$lang->story->stageList['designed']   = 'Designed';
$lang->story->stageList['developing'] = 'Developing';
$lang->story->stageList['developed']  = 'Developed';
$lang->story->stageList['testing']    = 'Testing';
$lang->story->stageList['tested']     = 'Tested';
$lang->story->stageList['verified']   = 'Accepted';
$lang->story->stageList['rejected']   = 'Verify Rejected';
$lang->story->stageList['delivering'] = 'Delivering';
$lang->story->stageList['delivered']  = 'Delivered';
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
$lang->story->legendMailto         = 'Mailto';
$lang->story->legendAttach         = 'Files';
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

$lang->story->specTemplate           = "As a < type of user >, I want < some goal > so that < some reason >.";
$lang->story->needNotReview          = 'No Review';
$lang->story->childStoryTitle        = 'Contains %s sub-requirements, of which %s have been finished';
$lang->story->childTaskTitle         = 'Contains %s tasks, of which %s have been finished';
$lang->story->successSaved           = "Story is saved!";
$lang->story->confirmDelete          = "Do you want to delete this story?";
$lang->story->confirmRecall          = "Do you want to recall this story?";
$lang->story->errorEmptyChildStory   = '『Decomposed Stories』canot be blank.';
$lang->story->errorNotSubdivide      = "If the status is reviewing/closed, or a sub story, it cannot be subdivided.";
$lang->story->errorMaxGradeSubdivide = "The current story's grade exceeds the system setting, so it cannot be subdivided same type story.";
$lang->story->errorStepwiseSubdivide = "This requirement type does not allow cross-system splitting. This setting can be changed in the admin.";
$lang->story->errorCannotSplit       = "This requirement has been split into sub-requirements of this type and cannot be split into requirements of other types.";
$lang->story->errorParentSplitTask   = "Parent requirements cannot be transferred to tasks, this operation has been filtered.";
$lang->story->errorERURSplitTask     = "Parent requirements,{$lang->ERCommon} and {$lang->URCommon}cannot be transferred to tasks, this operation has been filtered.";
$lang->story->errorEmptyReviewedBy   = "『{$lang->story->reviewers}』canot be blank.";
$lang->story->errorEmptyStory        = "There has same title story or null title story, please check it.";
$lang->story->mustChooseResult       = 'Select Result';
$lang->story->mustChoosePreVersion   = 'Select a version to revert to.';
$lang->story->noEpic                 = "No Epics.";
$lang->story->noStory                = 'No stories yet. ';
$lang->story->noRequirement          = 'No requirements yet. ';
$lang->story->ignoreChangeStage      = 'The status of %s is Draft or Closed. This operation has been filtered.';
$lang->story->cannotDeleteParent     = "Can not delete parent {$lang->SRCommon}";
$lang->story->moveChildrenTips       = "Are you sure to modify the product? After modification, all sub-level requirements of the requirement will also be changed accordingly.";
$lang->story->changeTips             = 'The story associated with the requirements to change, click "Cancel" ignore this change, click "Confirm" to change the story.';
$lang->story->estimateMustBeNumber   = 'Estimate value must be number.';
$lang->story->estimateMustBePlus     = 'Estimated value cannot be negative';
$lang->story->confirmChangeBranch    = $lang->SRCommon . ' %s is linked to the plan of its linked branch. If the branch is edited, ' . $lang->SRCommon . ' will be removed from the plan of its linked branch. Do you want to continue edit ' . $lang->SRCommon . '?';
$lang->story->confirmChangePlan      = $lang->SRCommon . ' %s is linked to the branch of its plan. If the branch is edited, ' . $lang->SRCommon . ' will be removed from the plan. Do you want to continue edit branch ?';
$lang->story->errorDuplicateStory    = $lang->SRCommon . '%s not exist';
$lang->story->confirmRecallChange    = "After undo the change, the story content will revert to the version before the change. Are you sure you want to undo?";
$lang->story->confirmRecallReview    = "Are you sure you want to withdraw the review?";
$lang->story->noStoryToTask          = "The status is not activated, and the parent {$lang->SRCommon} cannot be converted into a task!";
$lang->story->ignoreClosedStory      = "{$lang->SRCommon} %s status is closed, and the operation has been filtered.";
$lang->story->changeProductTips      = "Are you sure you want to modify the product? After modification, all sub-level requirements of the requirement will also be changed accordingly.";
$lang->story->gradeOverflow          = "The system detects that the maximum level of sub-requirements under this requirement is %s. After synchronization modification, it is %s. It exceeds the level range set by the system and cannot be modified.";
$lang->story->batchGradeOverflow     = "After the parent requirement of %s is modified, the level of its child requirement will exceed the level range set by the system, and this modification has ignored it.";
$lang->story->batchGradeSameRoot     = 'The requirements %s has a parent-child relationship and the requirement grade will not be modified.';
$lang->story->batchGradeGtParent     = 'The grade of %s requirements cannot be higher than its parent requirement, and it has been ignored in this modification.';
$lang->story->batchParentError       = "The parent requirement of %s requirement cannot be itself or its child requirement, which has been ignored in this modification.";
$lang->story->errorNoGradeSplit      = "There has no grade to split.";

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
$lang->story->action->closedbyparent        = array('main' => '$date, The system determines that the child story is automatically closed because its parent story is closed.');
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
$lang->story->action->syncgrade             = array('main' => "\$date, the system judges that this story's parent grade changed，this story grade synchronously changed to <strong>\$extra</strong>.");
$lang->story->action->linked2roadmap        = array('main' => '$date, linked by <strong>$actor</strong> to Roadmap <strong>$extra</strong>');
$lang->story->action->unlinkedfromroadmap   = array('main' => '$date, unlinked by <strong>$actor</strong> from Roadmap <strong>$extra</strong>.');
$lang->story->action->changedbycharter      = array('main' => '$date, launched by <strong>$actor</strong> for charter <strong>$extra</strong>, Synchronously adjust the story stage to In Charter.');

/* Statistical statement. */
$lang->story->report = new stdclass();
$lang->story->report->common = 'Report';
$lang->story->report->select = 'Select Report Type';
$lang->story->report->create = 'Create Report';
$lang->story->report->value  = 'Reports';

$lang->story->report->charts['storiesPerProduct']      = 'Group by ' . $lang->productCommon . ' Story';
$lang->story->report->charts['storiesPerModule']       = 'Group by Module Story';
$lang->story->report->charts['storiesPerSource']       = 'Group by Story Source';
$lang->story->report->charts['storiesPerPlan']         = 'Group by Plan';
$lang->story->report->charts['storiesPerStatus']       = 'Group by Status';
$lang->story->report->charts['storiesPerStage']        = 'Group by Phase';
$lang->story->report->charts['storiesPerPri']          = 'Group by Priority';
$lang->story->report->charts['storiesPerEstimate']     = 'Group by Estimates';
$lang->story->report->charts['storiesPerOpenedBy']     = 'Group by CreatedBy';
$lang->story->report->charts['storiesPerAssignedTo']   = 'Group by AssignedTo';
$lang->story->report->charts['storiesPerClosedReason'] = 'Group by Closed Reason';
$lang->story->report->charts['storiesPerChange']       = 'Group by Changed Story';
$lang->story->report->charts['storiesPerGrade']        = 'Group by Story Grade';

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
$lang->story->report->storiesPerOpenedBy->item     = 'OpenedBy';
$lang->story->report->storiesPerAssignedTo->item   = 'AssignedTo';
$lang->story->report->storiesPerClosedReason->item = 'Reason';
$lang->story->report->storiesPerEstimate->item     = 'Estimates';
$lang->story->report->storiesPerChange->item       = 'Changed Story';
$lang->story->report->storiesPerGrade->item        = 'Grade';

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
$lang->story->report->storiesPerOpenedBy->graph->xAxisName     = 'CreatedBy';
$lang->story->report->storiesPerAssignedTo->graph->xAxisName   = 'AssignedTo';
$lang->story->report->storiesPerClosedReason->graph->xAxisName = 'Close Reason';
$lang->story->report->storiesPerEstimate->graph->xAxisName     = 'Estimates ';
$lang->story->report->storiesPerChange->graph->xAxisName       = 'Change Times';
$lang->story->report->storiesPerGrade->graph->xAxisName        = 'Change Times';

$lang->story->placeholder = new stdclass();
$lang->story->placeholder->estimate = $lang->story->hour;

$lang->story->chosen = new stdClass();
$lang->story->chosen->reviewedBy = 'Choose ReviewedBy';

$lang->story->notice = new stdClass();
$lang->story->notice->closed           = "{$lang->SRCommon} that you select is closed!";
$lang->story->notice->reviewerNotEmpty = "This {$lang->SRCommon} needs to be reviewed, and the reviewedby is required.";
$lang->story->notice->changePlan       = 'The plan can be changed to only one item.';
$lang->story->notice->notDeleted       = 'People who have been reviewed cannot be deleted.';

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
$lang->story->subDivideTip['notWait']    = 'The Story has been %s and cannot be subdivided.';
$lang->story->subDivideTip['notActive']  = "Reviewing and closed story cannot be subdivided.";
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

$lang->story->addBranch      = 'Add %s';
$lang->story->deleteBranch   = 'Delete %s';
$lang->story->notice->branch = 'Each branch will establish a requirement. The requirements are twins. The twins requirements are synchronized except for the product, branch, module, plan, and stage fields. You can manually remove the twins relationship later';

$lang->story->relievedTwinsRelation     = 'Relieved twins relationship';
$lang->story->relievedTwinsRelationTips = 'After the twins relationship is terminated, it cannot be restored and the shutdown of the requirement is no longer synchronized.';
$lang->story->changeRelievedTwinsTips   = 'After the twins relationship is terminated, the twin stories are no longer synchronized.';
$lang->story->cannotRejectTips          = '"%s" are changed stories, cannot be reviewed as rejected, this operation has been filtered.';

$lang->story->trackOrderByList['id']       = 'Order by ID';
$lang->story->trackOrderByList['pri']      = 'Order by priority';
$lang->story->trackOrderByList['status']   = 'Order by status';
$lang->story->trackOrderByList['stage']    = 'Order by stage';
$lang->story->trackOrderByList['category'] = 'Order by category';

$lang->story->trackSortList['asc']  = ' Ascending';
$lang->story->trackSortList['desc'] = ' Descending';

$lang->story->error = new stdclass();
$lang->story->error->length = "Length exceeds %d characters, cannot be saved. Please modify it and try again.";
