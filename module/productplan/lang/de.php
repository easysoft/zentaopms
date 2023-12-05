<?php
/**
 * The productplan module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     productplan
 * @version     $Id: en.php 4659 2013-04-17 06:45:08Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->productplan->common     = $lang->productCommon . ' Plan';
$lang->productplan->browse     = "Durchsuchen";
$lang->productplan->index      = "Liste";
$lang->productplan->create     = "Erstellen";
$lang->productplan->edit       = "Bearbeiten";
$lang->productplan->delete     = "Löschen";
$lang->productplan->start      = "Start Plan";
$lang->productplan->finish     = "Finish Plan";
$lang->productplan->close      = "Close Plan";
$lang->productplan->activate   = "Activate Plan";
$lang->productplan->startAB    = "Start";
$lang->productplan->finishAB   = "Finish";
$lang->productplan->closeAB    = "Close";
$lang->productplan->activateAB = "Activate";
$lang->productplan->view       = "Details";
$lang->productplan->bugSummary = "<strong>%s</strong> Bugs auf dieser Seite";
$lang->productplan->basicInfo  = 'Basis Info';
$lang->productplan->batchEdit  = 'Mehrere bearbeiten';
$lang->productplan->project    = $lang->projectCommon;
$lang->productplan->plan       = 'Plan';
$lang->productplan->allAB      = 'All';
$lang->productplan->to         = 'To';
$lang->productplan->more       = 'More';
$lang->productplan->comment    = 'Comment';
$lang->productplan->storyPoint = 'Story Point';

$lang->productplan->batchEditAction   = 'Batch Edit Plan';
$lang->productplan->batchUnlink       = "Mehere Verknüpfungen aufheben";
$lang->productplan->batchClose        = "Batch Close";
$lang->productplan->batchChangeStatus = "Batch Change Status";
$lang->productplan->unlinkAB          = "Unlink";
$lang->productplan->linkStory         = "Story verknüpfen";
$lang->productplan->unlinkStory       = "Story verknüpgung aufheben";
$lang->productplan->unlinkStoryAB     = "Unlink";
$lang->productplan->batchUnlinkStory  = "Mehere Verknüpfungen aufheben";
$lang->productplan->linkedStories     = 'Verknüpfte Storys';
$lang->productplan->unlinkedStories   = 'Unverknüpfte Storys';
$lang->productplan->updateOrder       = 'Sortierung';
$lang->productplan->createChildren    = "Create Child Plans";
$lang->productplan->createExecution   = "Create {$lang->executionCommon}";
$lang->productplan->list              = 'List';
$lang->productplan->kanban            = 'Kanban';

$lang->productplan->linkBug          = "Bug Verknüpfen";
$lang->productplan->unlinkBug        = "Bug Verknpfung aufheben";
$lang->productplan->batchUnlinkBug   = "Mehrere Verknpfungen aufheben";
$lang->productplan->linkedBugs       = 'Verknüpfte Bugs';
$lang->productplan->unlinkedBugs     = 'Unverknüpfte Bugs';
$lang->productplan->unexpired        = 'Unexpired Plans';
$lang->productplan->noAssigned       = 'No Assigned';
$lang->productplan->all              = 'All Plans';
$lang->productplan->setDate          = "Set Start and end Date";
$lang->productplan->expired          = "Expired";
$lang->productplan->closedReason     = "Closed Reason";

$lang->productplan->confirmDelete      = "Möchten Sie diesen Plan löschen?";
$lang->productplan->confirmUnlinkStory = "Möchten Sie diese Story löschen?";
$lang->productplan->confirmUnlinkBug   = "Möchten Sie diesen Bug löschen?";
$lang->productplan->confirmStart       = "Do you want to start this plan?";
$lang->productplan->confirmFinish      = "Do you want to finish this plan?";
$lang->productplan->confirmClose       = "Do you want to close this plan?";
$lang->productplan->confirmActivate    = "Do you want to activate this plan?";
$lang->productplan->noPlan             = 'Kein Plan. ';
$lang->productplan->cannotDeleteParent = 'Cannot delete parent plan';
$lang->productplan->selectProjects     = "Please select the " . $lang->projectCommon;
$lang->productplan->projectNotEmpty    = $lang->projectCommon . ' cannot be empty.';
$lang->productplan->nextStep           = "Next step";
$lang->productplan->summary            = "Total: <strong>%s</strong>, Parents: <strong>%s</strong>, Children: <strong>%s</strong>，Independent: <strong>%s</strong>.";
$lang->productplan->checkedSummary     = "Seleted: <strong>%total%</strong>, Parents: <strong>%parent%</strong>, Children: <strong>%child%</strong>, Independent: <strong>%independent%</strong>.";
$lang->productplan->confirmChangePlan  = "After the branch of『%s』is unlinked, %s {$lang->SRCommon} and %s bugs under the branch will be removed from the plan at the same time, so still want to unassociate?";
$lang->productplan->confirmRemoveStory = "After the branch of『%s』is unlinked, %s {$lang->SRCommon} under the branch will be removed from the plan at the same time, so still want to unassociate?";
$lang->productplan->confirmRemoveBug   = "After the branch of『%s』is unlinked, %s bugs under the branch will be removed from the plan at the same time, so still want to unassociate?";

$lang->productplan->id         = 'ID';
$lang->productplan->product    = $lang->productCommon;
$lang->productplan->branch     = 'Platform/Branch';
$lang->productplan->title      = 'Titel';
$lang->productplan->desc       = 'Beschreibung';
$lang->productplan->begin      = 'Start';
$lang->productplan->end        = 'Ende';
$lang->productplan->status     = 'Status';
$lang->productplan->last       = 'Letzter Plan';
$lang->productplan->future     = 'Wartend';
$lang->productplan->stories    = 'Storys';
$lang->productplan->bugs       = 'Bugs';
$lang->productplan->hour       = $lang->hourCommon;
$lang->productplan->execution  = $lang->executionCommon;
$lang->productplan->parent     = "Parent Plan";
$lang->productplan->parentAB   = "Parent";
$lang->productplan->children   = "Child Plan";
$lang->productplan->childrenAB = "C";
$lang->productplan->order      = "Rank";
$lang->productplan->deleted    = "Deleted";
$lang->productplan->mailto     = "Mailto";
$lang->productplan->planStatus = "Status";

$lang->productplan->statusList['wait']   = 'Wait';
$lang->productplan->statusList['doing']  = 'Doing';
$lang->productplan->statusList['done']   = 'Done';
$lang->productplan->statusList['closed'] = 'Closed';

$lang->productplan->closedReasonList['done']   = 'Done';
$lang->productplan->closedReasonList['cancel'] = 'Cancel';

$lang->productplan->parentActionList['startedbychild']   = '<strong>starting </strong>the productplan sets the plan status as <strong>Doing</strong>.';
$lang->productplan->parentActionList['finishedbychild']  = '<strong>finishing </strong>all productplan sets the plan status as <strong>Done</strong>.';
$lang->productplan->parentActionList['closedbychild']    = '<strong>closing </strong>all productplan sets the plan status as <strong>Closed</strong>.';
$lang->productplan->parentActionList['activatedbychild'] = '<strong>activating </strong>the productplan sets the plan status as <strong>Doing</strong>.';
$lang->productplan->parentActionList['createchild']      = '<strong>creating</strong> a child productplan sets the plan status as <strong>Doing</strong>.';

$lang->productplan->endList[7]    = '1 Woche';
$lang->productplan->endList[14]   = '2 Wochen';
$lang->productplan->endList[31]   = '1 Monat';
$lang->productplan->endList[62]   = '2 Monate';
$lang->productplan->endList[93]   = '3 Monate';
$lang->productplan->endList[186]  = '6 Monate';
$lang->productplan->endList[365]  = '1 Jahr';

$lang->productplan->errorNoTitle         = 'ID %s Titel darf nicht leer sein.';
$lang->productplan->errorNoBegin         = 'ID %s Start darf nicht leer sein.';
$lang->productplan->errorNoEnd           = 'ID %s Ende darf nicht leer sein.';
$lang->productplan->beginGeEnd           = 'ID %s Start darf nicht größer als Ende sein.';
$lang->productplan->beginLessThanParent  = "The start date of the parent plan: %s, the start date cannot be less than the start date of the parent plan.";
$lang->productplan->endGreatThanParent   = "The completion date of the parent plan: %s, the completion date cannot be greater than the completion date of the parent plan.";
$lang->productplan->beginGreaterChild    = "The start date of the child plan: %s, the start date cannot be greater than the start date of the child plan.";
$lang->productplan->endLessThanChild     = "The completion date of the child plan: %s, the completion date cannot be less than the completion date of the child plan.";
$lang->productplan->noLinkedProject      = "The current {$lang->productCommon} has not been linked with a {$lang->projectCommon}. Please enter the list of the {$lang->productCommon} to link or create a {$lang->projectCommon}.";
$lang->productplan->enterProjectList     = "Enter the list of the {$lang->productCommon}";
$lang->productplan->beginGreaterChildTip = "The start date of the parent plan[%s]: %s, cannot be greater than the start date of the child plan: %s.";
$lang->productplan->endLessThanChildTip    = "The completion date of the parent plan[%s]: %s, cannot be less than the completion date of the child plan: %s.";
$lang->productplan->beginLessThanParentTip = "The start date of the child plan[%s]: %s, cannot be less than the start date of the parent plan: %s.";
$lang->productplan->endGreatThanParentTip  = "The completion date of the child plan[%s]: %s, cannot be greater than the completion date of the parent plan: %s.";
$lang->productplan->diffBranchesTip      = "The @branch@『%s』 of parent plan is not linked with the child plan. @branch@'s stories and bugs whill be removed from the plan. Do you want to save?";
$lang->productplan->deleteBranchTip      = "@branch@『%s』are linked with sub plans and cannot be modified.";

$lang->productplan->featureBar['browse']['all']    = 'Alle';
$lang->productplan->featureBar['browse']['undone'] = 'Undone';
$lang->productplan->featureBar['browse']['wait']   = 'Waiting';
$lang->productplan->featureBar['browse']['doing']  = 'Doing';
$lang->productplan->featureBar['browse']['done']   = 'Done';
$lang->productplan->featureBar['browse']['closed'] = 'Closed';

$lang->productplan->orderList['begin_desc'] = 'Begin Descend';
$lang->productplan->orderList['begin_asc']  = 'Begin Ascend';
$lang->productplan->orderList['title_desc'] = 'Title Descend';
$lang->productplan->orderList['title_asc']  = 'Title Ascend';

$lang->productplan->action = new stdclass();
$lang->productplan->action->changebychild = array('main' => '$date, $extra', 'extra' => 'parentActionList');
