<?php
/**
 * The productplan module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: en.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
$lang->productplan->common     = $lang->productCommon . ' Plan';
$lang->productplan->browse     = "Plan List";
$lang->productplan->index      = "Plan List";
$lang->productplan->create     = "Create Plan";
$lang->productplan->edit       = "Edit Plan";
$lang->productplan->delete     = "Delete Plan";
$lang->productplan->start      = "Start Plan";
$lang->productplan->finish     = "Complete Plan";
$lang->productplan->close      = "Close Plan";
$lang->productplan->activate   = "Activate Plan";
$lang->productplan->startAB    = "Start";
$lang->productplan->finishAB   = "Complete";
$lang->productplan->closeAB    = "Close";
$lang->productplan->activateAB = "Activate";
$lang->productplan->view       = "Details";
$lang->productplan->bugSummary = "<strong>%s</strong> Bugs on this page.";
$lang->productplan->basicInfo  = 'Basic Info';
$lang->productplan->batchEdit  = 'Batch Edit';
$lang->productplan->project    = $lang->projectCommon;
$lang->productplan->plan       = 'Plan';
$lang->productplan->allAB      = 'All';
$lang->productplan->to         = 'To';
$lang->productplan->more       = 'More';
$lang->productplan->comment    = 'Comment';
$lang->productplan->storyPoint = 'Story Point';

$lang->productplan->batchEditAction   = 'Batch Edit Plans';
$lang->productplan->batchUnlink       = "Batch Unlink";
$lang->productplan->batchClose        = "Batch Close";
$lang->productplan->batchChangeStatus = "Batch Change Status";
$lang->productplan->unlinkAB          = "Unlink";
$lang->productplan->linkStory         = "Link Story";
$lang->productplan->unlinkStory       = "Unlink Story";
$lang->productplan->unlinkStoryAB     = "Unlink";
$lang->productplan->batchUnlinkStory  = "Batch Unlink";
$lang->productplan->linkedStories     = 'Linked Stories';
$lang->productplan->unlinkedStories   = 'Linked Stories';
$lang->productplan->updateOrder       = 'Sort';
$lang->productplan->createChildren    = "Create Subplans";
$lang->productplan->createExecution   = "Create {$lang->execution->common}";
$lang->productplan->list              = 'List';
$lang->productplan->kanban            = 'Kanban';

$lang->productplan->linkBug          = "Link Bug";
$lang->productplan->unlinkBug        = "Unlink Bug";
$lang->productplan->batchUnlinkBug   = "Batch Unlink Bugs";
$lang->productplan->linkedBugs       = 'Linked Bugs';
$lang->productplan->unlinkedBugs     = 'Unlinked Bugs';
$lang->productplan->unexpired        = 'Unexpired';
$lang->productplan->noAssigned       = 'Unassigned';
$lang->productplan->all              = 'All Plans';
$lang->productplan->setDate          = "Plan Duration";
$lang->productplan->expired          = "Expired";
$lang->productplan->closedReason     = "Closed Reason";

$lang->productplan->confirmDelete      = "Are you sure you want to delete this plan?";
$lang->productplan->confirmUnlinkStory = "Are you sure you want to unlink this story?";
$lang->productplan->confirmUnlinkBug   = "Are you sure you want to unlink this bug?";
$lang->productplan->confirmStart       = "Are you sure you want to start this plan?";
$lang->productplan->confirmFinish      = "Are you sure you want to complete this plan?";
$lang->productplan->confirmClose       = "Are you sure you want to close this plan?";
$lang->productplan->confirmActivate    = "Are you sure you want to activate this plan?";
$lang->productplan->noPlan             = 'No plans yet.';
$lang->productplan->cannotDeleteParent = 'Parent plans can not be deleted.';
$lang->productplan->selectProjects     = 'Please select the ' . $lang->projectCommon;
$lang->productplan->projectNotEmpty    = $lang->projectCommon . ' should not be empty.';
$lang->productplan->nextStep           = "Next";
$lang->productplan->summary            = "<strong>%s</strong> plans on this page: <strong>%s</strong> parent, <strong>%s</strong> sub, <strong>%s</strong> independent.";
$lang->productplan->checkedSummary     = "Selected <strong>%total%</strong> plans: <strong>%parent%</strong> parent, <strong>%child%</strong> sub, <strong>%independent%</strong> independent.";
$lang->productplan->storySummary       = "This page contains <strong>%s</strong> {$lang->ERCommon}, <strong>%s</strong> {$lang->URCommon}, and <strong>%s</strong> {$lang->SRCommon}, with an estimated <strong>%s</strong> {$lang->hourCommon} and a test coverage rate of <strong>%s</strong>.";
$lang->productplan->confirmChangePlan  = "After unlinking branch『%s』, its %s stories and %s bugs will be removed from the plan as well. Do you want to proceed?";
$lang->productplan->confirmRemoveStory = "After unlinking branch『%s』, its %s stories will be removed from the plan as well. Do you want to proceed?";
$lang->productplan->confirmRemoveBug   = "After unlinking branch『%s』, its %s  bugs will be removed from the plan as well. Do you want to proceed?";

$lang->productplan->id           = 'ID';
$lang->productplan->product      = $lang->productCommon;
$lang->productplan->branch       = 'Platform / Branch';
$lang->productplan->title        = 'Title';
$lang->productplan->desc         = 'Description';
$lang->productplan->begin        = 'Start';
$lang->productplan->end          = 'End';
$lang->productplan->status       = 'Status';
$lang->productplan->last         = 'Last Plan';
$lang->productplan->future       = 'TBD';
$lang->productplan->stories      = 'Stories';
$lang->productplan->bugs         = 'Bugs';
$lang->productplan->hour         = $lang->hourCommon;
$lang->productplan->execution    = $lang->execution->common;
$lang->productplan->parent       = "Parent Plan";
$lang->productplan->parentAB     = "Parent";
$lang->productplan->children     = "Subplan";
$lang->productplan->childrenAB   = "Sub";
$lang->productplan->order        = "Sort";
$lang->productplan->deleted      = "Deleted";
$lang->productplan->mailto       = "Mail to";
$lang->productplan->planStatus   = "Status";
$lang->productplan->storyTitle   = "Title";
$lang->productplan->createdBy    = 'Creator';
$lang->productplan->createdDate  = 'Created on';
$lang->productplan->finishedDate = 'Completed on';
$lang->productplan->closedDate   = 'Cloased on';

$lang->productplan->statusList['wait']   = 'Waiting';
$lang->productplan->statusList['doing']  = 'Doing';
$lang->productplan->statusList['done']   = 'Done';
$lang->productplan->statusList['closed'] = 'Closed';

$lang->productplan->closedReasonList['done']   = 'Completed';
$lang->productplan->closedReasonList['cancel'] = 'Cancel';

$lang->productplan->parentActionList['startedbychild']   = 'Plan status automatically set to <strong>Doing</strong> as a subplan has <strong>started</strong>.';
$lang->productplan->parentActionList['finishedbychild']  = 'Plan status automatically set to <strong>Done</strong> as all subplans has been <strong>completed</strong>.';
$lang->productplan->parentActionList['closedbychild']    = 'Plan status automatically set to <strong>Closed</strong> as all subplans has been <strong>closed</strong>.';
$lang->productplan->parentActionList['activatedbychild'] = 'Plan status automatically set to <strong>Doing</strong> as a subplan has <strong>activated</strong>.';
$lang->productplan->parentActionList['createchild']      = 'Plan status automatically set to <strong>Doing</strong> as a subplan has been <strong>created</strong>.';

$lang->productplan->endList[7]    = '1 Week';
$lang->productplan->endList[14]   = '2 Weeks';
$lang->productplan->endList[31]   = '1 Month';
$lang->productplan->endList[62]   = '2 Months';
$lang->productplan->endList[93]   = '3 Months';
$lang->productplan->endList[186]  = '6 Months';
$lang->productplan->endList[365]  = '1 Year';

$lang->productplan->errorNoTitle           = 'ID %s title should not be empty.';
$lang->productplan->errorNoBegin           = 'ID %s start time should not be empty';
$lang->productplan->errorNoEnd             = 'ID %s end time should not be empty.';
$lang->productplan->beginGeEnd             = 'The start time of ID %s cannot be later than the end time.';
$lang->productplan->beginLessThanParent    = "Parent plan start date: %s. The start date cannot be earlier than the parent plans start date.";
$lang->productplan->endGreatThanParent     = "Parent plan end date: %s. The end date cannot be later than the parent plans end date.";
$lang->productplan->beginGreaterChild      = "Subplan start date: %s. The start date cannot be later than the subplans start date.";
$lang->productplan->endLessThanChild       = "Subplan end date: %s. The end date cannot be earlier than the subplans end date.";
$lang->productplan->noLinkedProject        = "The current {$lang->productCommon} is not yet linked to any {$lang->projectCommon}. Please go to the {$lang->productCommon} and {$lang->projectCommon} list to link or create a new {$lang->projectCommon}.";
$lang->productplan->enterProjectList       = "Go to the {$lang->projectCommon} list under {$lang->productCommon}.";
$lang->productplan->beginGreaterChildTip   = "Parent plan [%s] start date: %s. It cannot be later than the subplan start date: %s.";
$lang->productplan->endLessThanChildTip    = "Parent plan [%s] end date: %s. It cannot be earlier than the subplan end date: %s.";
$lang->productplan->beginLessThanParentTip = "Subplan [%s] start date: %s. It cannot be earlier than the parent plan start date: %s.";
$lang->productplan->endGreatThanParentTip  = "Subplan [%s] end date: %s. It cannot be later than the parent plan end date: %s.";
$lang->productplan->diffBranchesTip        = "The @branch@ [%s] of the parent plan is not linked to any subplan. Related stories and bugs under this @branch@ will be removed from the plan automatically. Do you want to save?";
$lang->productplan->deleteBranchTip        = "The @branch@ [%s] is linked to a subplan and cannot be changed.";

$lang->productplan->featureBar['browse']['all']    = 'All';
$lang->productplan->featureBar['browse']['undone'] = 'Uncompleted';
$lang->productplan->featureBar['browse']['wait']   = 'Waiting';
$lang->productplan->featureBar['browse']['doing']  = 'Doing';
$lang->productplan->featureBar['browse']['done']   = 'Done';
$lang->productplan->featureBar['browse']['closed'] = 'Closed';

$lang->productplan->orderList['begin_desc'] = 'Start Date Descending';
$lang->productplan->orderList['begin_asc']  = 'Start Date Ascending';
$lang->productplan->orderList['title_desc'] = 'Title Descending';
$lang->productplan->orderList['title_asc']  = 'Title Ascending';

$lang->productplan->action = new stdclass();
$lang->productplan->action->changebychild = array('main' => '$date, $extra', 'extra' => 'parentActionList');
