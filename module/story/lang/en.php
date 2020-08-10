<?php
/**
 * The story module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     story
 * @version     $Id: en.php 5141 2013-07-15 05:57:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->story->create          = "Create Story";
$lang->story->batchCreate     = "Batch Create";
$lang->story->change          = "Change";
$lang->story->changeAction    = "Change Story";
$lang->story->changed         = 'Change';
$lang->story->assignTo        = 'Assign';
$lang->story->assignAction    = 'Assign Story';
$lang->story->review          = 'Review';
$lang->story->reviewAction    = 'Review Story';
$lang->story->needReview      = 'Need Review';
$lang->story->batchReview     = 'Batch Review';
$lang->story->edit            = "Edit Story";
$lang->story->batchEdit       = "Batch Edit";
$lang->story->subdivide       = 'Decompose';
$lang->story->subdivideAction = 'Decompose Story';
$lang->story->splitRequirent  = 'Decompose';
$lang->story->close           = 'Close';
$lang->story->closeAction     = 'Close Story';
$lang->story->batchClose      = 'Batch Close';
$lang->story->activate        = 'Activate';
$lang->story->activateAction  = 'Activate Story';
$lang->story->delete          = "Delete";
$lang->story->deleteAction    = "Delete Story";
$lang->story->view            = "Story Detail";
$lang->story->setting         = "Settings";
$lang->story->tasks           = "Linked Tasks";
$lang->story->bugs            = "Linked Bugs";
$lang->story->cases           = "Linked Cases";
$lang->story->taskCount       = 'Tasks';
$lang->story->bugCount        = 'Bugs';
$lang->story->caseCount       = 'Cases';
$lang->story->taskCountAB     = 'T';
$lang->story->bugCountAB      = 'B';
$lang->story->caseCountAB     = 'C';
$lang->story->linkStory       = 'Link Story';
$lang->story->unlinkStory     = 'UnLinked';
$lang->story->export          = "Export Data";
$lang->story->exportAction    = "Export Story";
$lang->story->zeroCase        = "Stories without cases";
$lang->story->zeroTask        = "Only list stories without tasks";
$lang->story->reportChart     = "Report";
$lang->story->reportAction    = "Story Report";
$lang->story->copyTitle       = "Copy Title";
$lang->story->batchChangePlan   = "Batch Change Plans";
$lang->story->batchChangeBranch = "Batch Change Branches";
$lang->story->batchChangeStage  = "Batch Change Phases";
$lang->story->batchAssignTo     = "Batch Assign";
$lang->story->batchChangeModule = "Batch Change Modules";
$lang->story->viewAll           = "See All";
$lang->story->skipStory         = '%s is a parent story. It cannot be closed.';
$lang->story->closedStory       = 'Story %s is closed and will not be closed.';

$lang->story->common         = 'Story';
$lang->story->id             = 'ID';
$lang->story->parent         = 'Parent';
$lang->story->product        = $lang->productCommon;
$lang->story->branch         = "Branch/Platform";
$lang->story->module         = 'Module';
$lang->story->moduleAB       = 'Module';
$lang->story->source         = 'From';
$lang->story->sourceNote     = 'Note';
$lang->story->fromBug        = 'From Bug';
$lang->story->title          = 'Title';
$lang->story->type           = 'Type';
$lang->story->color          = 'Color';
$lang->story->toBug          = 'ToBug';
$lang->story->spec           = 'Description';
$lang->story->assign         = 'Assign';
$lang->story->verify         = 'Acceptance';
$lang->story->pri            = 'Priority';
$lang->story->estimate       = "Estimates {$lang->hourCommon}";
$lang->story->estimateAB     = 'Est.' . $lang->hourCommon == 'hour' ? '(h)' : '(SP)';
$lang->story->hour           = $lang->hourCommon;
$lang->story->status         = 'Status';
$lang->story->subStatus      = 'Sub Status';
$lang->story->stage          = 'Phase';
$lang->story->stageAB        = 'Phase';
$lang->story->stagedBy       = 'SetBy';
$lang->story->mailto         = 'Mailto';
$lang->story->openedBy       = 'CreatedBy';
$lang->story->openedDate     = 'CreatedDate';
$lang->story->assignedTo     = 'AssignTo';
$lang->story->assignedDate   = 'AssignedDate';
$lang->story->lastEditedBy   = 'EditedBy';
$lang->story->lastEditedDate = 'EditedDate';
$lang->story->closedBy       = 'ClosedBy';
$lang->story->closedDate     = 'ClosedDate';
$lang->story->closedReason   = 'Reason';
$lang->story->rejectedReason = 'Reject Reason';
$lang->story->reviewedBy     = 'ReviewedBy';
$lang->story->reviewedDate   = 'ReviewedDate';
$lang->story->version        = 'Version';
$lang->story->plan           = 'Linked Plan';
$lang->story->planAB         = 'Plan';
$lang->story->comment        = 'Comment';
$lang->story->children       = "Child {$lang->storyCommon}";
$lang->story->childrenAB     = "C";
$lang->story->linkStories    = 'Linked Stories';
$lang->story->childStories   = 'Decomposed Stories';
$lang->story->duplicateStory = 'Duplicated Story ID';
$lang->story->reviewResult   = 'Review Result';
$lang->story->preVersion     = 'Last Version';
$lang->story->keywords       = 'Tags';
$lang->story->newStory       = 'Continue adding';
$lang->story->colorTag       = 'Color';
$lang->story->files          = 'Files';
$lang->story->copy           = "Copy Story";
$lang->story->total          = "Total Stories";
$lang->story->allStories     = 'All Stories';
$lang->story->unclosed       = 'Unclosed';
$lang->story->deleted        = 'Deleted';
$lang->story->released       = 'Released Stories';

$lang->story->ditto       = 'Ditto';
$lang->story->dittoNotice = 'This story is not linked to the same product as the last one is!';

$lang->story->needNotReviewList[0] = 'Need Review';
$lang->story->needNotReviewList[1] = 'Need Not Review';

$lang->story->useList[0] = 'Yes';
$lang->story->useList[1] = 'No';

$lang->story->statusList['']          = '';
$lang->story->statusList['draft']     = 'Draft';
$lang->story->statusList['active']    = 'Active';
$lang->story->statusList['closed']    = 'Closed';
$lang->story->statusList['changed']   = 'Changed';

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

$lang->story->legendBasicInfo      = 'Basic Info';
$lang->story->legendLifeTime       = 'Story Life ';
$lang->story->legendRelated        = 'Related Info';
$lang->story->legendMailto         = 'Mailto';
$lang->story->legendAttatch        = 'Files';
$lang->story->legendProjectAndTask = $lang->projectCommon . ' And Task';
$lang->story->legendBugs           = 'Linked Bugs';
$lang->story->legendFromBug        = 'From Bug';
$lang->story->legendCases          = 'Linked Cases';
$lang->story->legendLinkStories    = 'Linked Stories';
$lang->story->legendChildStories   = 'Child Stories';
$lang->story->legendSpec           = 'Description';
$lang->story->legendVerify         = 'Acceptance';
$lang->story->legendMisc           = 'Misc.';

$lang->story->lblChange            = 'Change';
$lang->story->lblReview            = 'Review';
$lang->story->lblActivate          = 'Activate';
$lang->story->lblClose             = 'Close';
$lang->story->lblTBC               = 'Task/Bug/Case';

$lang->story->checkAffection       = 'Influence';
$lang->story->affectedProjects     = '' . $lang->projectCommon . 's';
$lang->story->affectedBugs         = 'Bugs';
$lang->story->affectedCases        = 'Cases';

$lang->story->specTemplate          = "As a < type of user >, I want < some goal > so that < some reason >.";
$lang->story->needNotReview         = 'No Review Required';
$lang->story->successSaved          = "Story is saved!";
$lang->story->confirmDelete         = "Do you want to delete this story?";
$lang->story->errorEmptyChildStory  = '『Decomposed Stories』canot be blank.';
$lang->story->errorNotSubdivide     = "If the status is not active, or the stage is not wait, or a sub story, it cannot be subdivided.";
$lang->story->mustChooseResult      = 'Select Result';
$lang->story->mustChoosePreVersion  = 'Select a version to revert to.';
$lang->story->noStory               = 'No stories yet. ';
$lang->story->ignoreChangeStage     = 'Story %s is in Draft or Closed status. Please review it..';
$lang->story->cannotDeleteParent    = "Can not delete parent {$lang->storyCommon}";
$lang->story->moveChildrenTips      = "Its Child {$lang->storyCommon} will be moved to the selected product when editing the linked product of Parent {$lang->storyCommon}.";

$lang->story->form = new stdclass();
$lang->story->form->area      = 'Scope';
$lang->story->form->desc      = 'What story is it? What is the acceptance?';
$lang->story->form->resource  = 'Who will allocate resources? How long does it take?';
$lang->story->form->file      = 'If any file that is linked to a story, please click Here to upload it.';

$lang->story->action = new stdclass();
$lang->story->action->reviewed            = array('main' => '$date, recorded by <strong>$actor</strong>. The result is <strong>$extra</strong>.', 'extra' => 'reviewResultList');
$lang->story->action->closed              = array('main' => '$date, closed by <strong>$actor</strong>. The reasion is <strong>$extra</strong> $appendLink.', 'extra' => 'reasonList');
$lang->story->action->linked2plan         = array('main' => '$date, linked by <strong>$actor</strong> to Plan <strong>$extra</strong>');
$lang->story->action->unlinkedfromplan    = array('main' => '$date, unlinked by <strong>$actor</strong> from Plan <strong>$extra</strong>.');
$lang->story->action->linked2project      = array('main' => '$date, linked by <strong>$actor</strong> to ' . $lang->projectCommon . ' <strong>$extra</strong>.');
$lang->story->action->unlinkedfromproject = array('main' => '$date, unlinked by <strong>$actor</strong> from ' . $lang->projectCommon . ' <strong>$extra</strong>.');
$lang->story->action->linked2build        = array('main' => '$date, linked by <strong>$actor</strong> to Build <strong>$extra</strong>');
$lang->story->action->unlinkedfrombuild   = array('main' => '$date, unlinked by <strong>$actor</strong> from Build <strong>$extra</strong>.');
$lang->story->action->linked2release      = array('main' => '$date, linked by <strong>$actor</strong> to Release <strong>$extra</strong>');
$lang->story->action->unlinkedfromrelease = array('main' => '$date, unlinked by <strong>$actor</strong> from Release <strong>$extra</strong>.');
$lang->story->action->linkrelatedstory    = array('main' => '$date, linked by <strong>$actor</strong> to Story <strong>$extra</strong>.');
$lang->story->action->subdividestory      = array('main' => '$date, decomposed by <strong>$actor</strong> to Story <strong>$extra</strong>.');
$lang->story->action->unlinkrelatedstory  = array('main' => '$date, unlinked by <strong>$actor</strong> from Story <strong>$extra</strong>.');
$lang->story->action->unlinkchildstory    = array('main' => '$date, unlinked by <strong>$actor</strong> Decomposed Story <strong>$extra</strong>.');

/* Statistical statement. */
$lang->story->report = new stdclass();
$lang->story->report->common = 'Report';
$lang->story->report->select = 'Select Report Type';
$lang->story->report->create = 'Create Report';
$lang->story->report->value  = 'Reports';

$lang->story->report->charts['storysPerProduct']        = 'Group by ' . $lang->productCommon . ' Story';
$lang->story->report->charts['storysPerModule']         = 'Group by Module Story';
$lang->story->report->charts['storysPerSource']         = 'Group by Story Source';
$lang->story->report->charts['storysPerPlan']           = 'Group by Plan';
$lang->story->report->charts['storysPerStatus']         = 'Group by Status';
$lang->story->report->charts['storysPerStage']          = 'Group by Phase';
$lang->story->report->charts['storysPerPri']            = 'Group by Priority';
$lang->story->report->charts['storysPerEstimate']       = 'Group by Estimates';
$lang->story->report->charts['storysPerOpenedBy']       = 'Group by CreatedBy';
$lang->story->report->charts['storysPerAssignedTo']     = 'Group by AssignedTo';
$lang->story->report->charts['storysPerClosedReason']   = 'Group by Closed Reason';
$lang->story->report->charts['storysPerChange']         = 'Group by Changed Story';

$lang->story->report->options = new stdclass();
$lang->story->report->options->graph   = new stdclass();
$lang->story->report->options->type    = 'pie';
$lang->story->report->options->width   = 500;
$lang->story->report->options->height  = 140;

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
$lang->story->notice->closed = 'Story that you select is closed!';
