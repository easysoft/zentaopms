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
$lang->productplan->browse     = "Liste des Plans";
$lang->productplan->index      = "Liste";
$lang->productplan->create     = "Créer Plan";
$lang->productplan->edit       = "Editer Plan";
$lang->productplan->delete     = "Supprimer Plan";
$lang->productplan->start      = "Start Plan";
$lang->productplan->finish     = "Finish Plan";
$lang->productplan->close      = "Close Plan";
$lang->productplan->activate   = "Activate Plan";
$lang->productplan->startAB    = "Start";
$lang->productplan->finishAB   = "Finish";
$lang->productplan->closeAB    = "Close";
$lang->productplan->activateAB = "Activate";
$lang->productplan->view       = "Détail Plan";
$lang->productplan->bugSummary = "Total <strong>%s</strong> Bugs sur cette page.";
$lang->productplan->basicInfo  = 'Infos de Base';
$lang->productplan->batchEdit  = 'Edition par Lot';
$lang->productplan->project    = $lang->projectCommon;
$lang->productplan->plan       = 'Plan';
$lang->productplan->allAB      = 'All';
$lang->productplan->to         = 'To';
$lang->productplan->more       = 'More';
$lang->productplan->comment    = 'Comment';
$lang->productplan->storyPoint = 'Story Point';

$lang->productplan->batchEditAction   = 'Batch Edit Plan';
$lang->productplan->batchUnlink       = "Retirer par lot";
$lang->productplan->batchClose        = "Batch Close";
$lang->productplan->batchChangeStatus = "Batch Change Status";
$lang->productplan->unlinkAB          = "Unlink";
$lang->productplan->linkStory         = "Planifier Story";
$lang->productplan->unlinkStory       = "Retirer Story";
$lang->productplan->unlinkStoryAB     = "Retirer";
$lang->productplan->batchUnlinkStory  = "Retirer par Lot";
$lang->productplan->linkedStories     = 'Stories Planifiées';
$lang->productplan->unlinkedStories   = 'Stories non Planifiées';
$lang->productplan->updateOrder       = 'Ordre';
$lang->productplan->createChildren    = "Créer Sous-Plans";
$lang->productplan->createExecution   = "Create {$lang->executionCommon}";
$lang->productplan->list              = 'List';
$lang->productplan->kanban            = 'Kanban';

$lang->productplan->linkBug          = "Planifier Bug";
$lang->productplan->unlinkBug        = "Retirer Bug";
$lang->productplan->batchUnlinkBug   = "Retirer Bugs par Lot";
$lang->productplan->linkedBugs       = 'Bugs Planifiés';
$lang->productplan->unlinkedBugs     = 'Bugs non Planifiés';
$lang->productplan->unexpired        = 'Plans non échus';
$lang->productplan->noAssigned       = 'No Assigned';
$lang->productplan->all              = 'Tous les Plans';
$lang->productplan->setDate          = "Set Start and end Date";
$lang->productplan->expired          = "Expired";
$lang->productplan->closedReason     = "Closed Reason";

$lang->productplan->confirmDelete      = "Voulez-vous supprimer ce plan ?";
$lang->productplan->confirmUnlinkStory = "Voulez-vous détacher cette Story du Plan ?";
$lang->productplan->confirmUnlinkBug   = "Voulez-vous retirer ce bug du plan ?";
$lang->productplan->confirmStart       = "Do you want to start this plan?";
$lang->productplan->confirmFinish      = "Do you want to finish this plan?";
$lang->productplan->confirmClose       = "Do you want to close this plan?";
$lang->productplan->confirmActivate    = "Do you want to activate this plan?";
$lang->productplan->noPlan             = "Aucun plan pour l'instant. ";
$lang->productplan->cannotDeleteParent = 'Impossible de supprimer le plan parent';
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
$lang->productplan->branch     = 'Plateforme/Branche';
$lang->productplan->title      = 'Titre';
$lang->productplan->desc       = 'Description';
$lang->productplan->begin      = 'Début';
$lang->productplan->end        = 'Fin';
$lang->productplan->status     = 'Status';
$lang->productplan->last       = 'Dernier Plan';
$lang->productplan->future     = 'A Définir';
$lang->productplan->stories    = 'Story';
$lang->productplan->bugs       = 'Bug';
$lang->productplan->hour       = $lang->hourCommon;
$lang->productplan->execution  = $lang->executionCommon;
$lang->productplan->parent     = "Plan Parent";
$lang->productplan->parentAB   = "Parent";
$lang->productplan->children   = "Sous-Plan";
$lang->productplan->childrenAB = "C";
$lang->productplan->order      = "Order";
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

$lang->productplan->endList[7]    = '1 Semaine';
$lang->productplan->endList[14]   = '2 Semaines';
$lang->productplan->endList[31]   = '1 Mois';
$lang->productplan->endList[62]   = '2 Mois';
$lang->productplan->endList[93]   = '3 Mois';
$lang->productplan->endList[186]  = '6 Mois';
$lang->productplan->endList[365]  = '1 Année';

$lang->productplan->errorNoTitle         = 'ID %s titre ne doit pas être à blanc.';
$lang->productplan->errorNoBegin         = "ID %s l'heure de début devrait être renseignée.";
$lang->productplan->errorNoEnd           = "ID %s l'heure de fin devrait être renseignée.";
$lang->productplan->beginGeEnd           = "ID %s l'heure de début ne doit pas être >= à l'heure de fin.";
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
$lang->productplan->diffBranchesTip      = "The @branch@『%s』of parent plan is not linked with the child plan. @branch@'s stories and bugs whill be removed from the plan. Do you want to save?";
$lang->productplan->deleteBranchTip      = "@branch@『%s』are linked with sub plans and cannot be modified.";

$lang->productplan->featureBar['browse']['all']    = 'Tous';
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
