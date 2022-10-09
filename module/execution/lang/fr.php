<?php
/**
 * The execution module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: en.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
/* Fields. */
$lang->execution->allExecutions       = 'Tous les ' . $lang->execution->common . 's';
$lang->execution->allExecutionAB      = 'Execution List';
$lang->execution->id                  = $lang->executionCommon . ' ID';
$lang->execution->type                = $lang->executionCommon . 'Type';
$lang->execution->name                = $lang->executionCommon . 'Name';
$lang->execution->code                = $lang->executionCommon . 'Code';
$lang->execution->projectName         = 'Project';
$lang->execution->project             = 'Project';
$lang->execution->execId              = "{$lang->execution->common} ID";
$lang->execution->execName            = 'Execution Name';
$lang->execution->execCode            = 'Execution Code';
$lang->execution->execType            = 'Execution Type';
$lang->execution->lifetime            = 'Project Cycle';
$lang->execution->attribute           = 'Stage Type';
$lang->execution->percent             = 'Workload %';
$lang->execution->milestone           = 'Milestone';
$lang->execution->parent              = 'Project';
$lang->execution->path                = 'Path';
$lang->execution->grade               = 'Grade';
$lang->execution->output              = 'Output';
$lang->execution->version             = 'Version';
$lang->execution->parentVersion       = 'Parent Version';
$lang->execution->planDuration        = 'Plan Duration';
$lang->execution->realDuration        = 'Real Duration';
$lang->execution->openedVersion       = 'Opened Version';
$lang->execution->lastEditedBy        = 'Last EditedBy';
$lang->execution->lastEditedDate      = 'Last EditedDate';
$lang->execution->suspendedDate       = 'Suspended Date';
$lang->execution->vision              = 'Vision';
$lang->execution->displayCards        = 'Max cards per column';
$lang->execution->fluidBoard          = 'Column Width';
$lang->execution->stage               = 'Etape';
$lang->execution->pri                 = 'Priorité';
$lang->execution->openedBy            = 'Ouvert par';
$lang->execution->openedDate          = "Date d'ouverture";
$lang->execution->closedBy            = 'Fermé par';
$lang->execution->closedDate          = 'Date de fermeture';
$lang->execution->canceledBy          = 'Annulé par';
$lang->execution->canceledDate        = "Date d'annulation";
$lang->execution->begin               = 'Début';
$lang->execution->end                 = 'Fin';
$lang->execution->dateRange           = 'Durée';
$lang->execution->realBeganAB         = 'Actual Begin';
$lang->execution->realEndAB           = 'Actual End';
$lang->execution->teamCount           = 'nombre de personnes';
$lang->execution->realBegan           = 'Début effectif';
$lang->execution->realEnd             = 'Clôture effective';
$lang->execution->to                  = 'à';
$lang->execution->days                = 'Budget Jours';
$lang->execution->day                 = ' Jours';
$lang->execution->workHour            = ' Heures';
$lang->execution->workHourUnit        = 'H';
$lang->execution->totalHours          = 'Budget (Heure)';
$lang->execution->totalDays           = 'Budget (Jour)';
$lang->execution->status              = $lang->executionCommon . 'Status';
$lang->execution->execStatus          = 'Status';
$lang->execution->subStatus           = 'Sub Status';
$lang->execution->desc                = $lang->executionCommon . 'Description';
$lang->execution->execDesc            = 'Description';
$lang->execution->owner               = 'Propriétaire';
$lang->execution->PO                  = "Propriétaire {$lang->executionCommon}";
$lang->execution->PM                  = "Directeur {$lang->executionCommon}";
$lang->execution->execPM              = "Directeur Execution";
$lang->execution->QD                  = 'Test Manager';
$lang->execution->RD                  = 'Release Manager';
$lang->execution->release             = 'Release';
$lang->execution->acl                 = "Contrôle d'accès";
$lang->execution->auth                = 'Privileges';
$lang->execution->teamname            = "Nom de l'équipe";
$lang->execution->updateOrder         = 'Rank';
$lang->execution->order               = "Rang du {$lang->executionCommon}";
$lang->execution->orderAB             = "Rang";
$lang->execution->products            = "{$lang->productCommon} liés";
$lang->execution->whitelist           = 'Liste Blanche';
$lang->execution->addWhitelist        = 'Add Whitelist';
$lang->execution->unbindWhitelist     = 'Remove Whitelist';
$lang->execution->totalEstimate       = 'Estimé';
$lang->execution->totalConsumed       = 'Coût';
$lang->execution->totalLeft           = 'Reste';
$lang->execution->progress            = ' Progrès';
$lang->execution->hours               = 'Estimé: %s, Coût: %s, Reste: %s.';
$lang->execution->viewBug             = 'Bugs';
$lang->execution->noProduct           = "Aucun {$lang->productCommon} pour l'instant.";
$lang->execution->createStory         = "Créer une Story";
$lang->execution->storyTitle          = "Story Name";
$lang->execution->storyView           = "Story Detail";
$lang->execution->all                 = "Tous les {$lang->executionCommon}s";
$lang->execution->undone              = 'Non Terminé';
$lang->execution->unclosed            = 'Non Fermées';
$lang->execution->closedExecution     = 'Closed Execution';
$lang->execution->typeDesc            = "Aucune {$lang->SRCommon}, bug, build, test, ou graphe d'atterrissage n'est disponible";
$lang->execution->mine                = 'A Moi: ';
$lang->execution->involved            = 'Mine';
$lang->execution->other               = 'Autres';
$lang->execution->deleted             = 'Supprimé';
$lang->execution->delayed             = 'Delayed';
$lang->execution->product             = $lang->execution->products;
$lang->execution->readjustTime        = "Ajuster Début et Fin du {$lang->executionCommon}";
$lang->execution->readjustTask        = 'Ajuster Début et Fin de la Tâche';
$lang->execution->effort              = 'Effort';
$lang->execution->storyEstimate       = 'Story Estimate';
$lang->execution->newEstimate         = 'New Estimate';
$lang->execution->reestimate          = 'Reestimate';
$lang->execution->selectRound         = 'Select Round';
$lang->execution->average             = 'Average';
$lang->execution->relatedMember       = 'Equipe';
$lang->execution->watermark           = 'Exporté par ZenTao';
$lang->execution->burnXUnit           = '(Date)';
$lang->execution->burnYUnit           = '(Hours)';
$lang->execution->count               = '(Count)';
$lang->execution->waitTasks           = 'Waiting Tasks';
$lang->execution->viewByUser          = 'Par Utilisateur';
$lang->execution->oneProduct          = "Only one stage can be linked {$lang->productCommon}";
$lang->execution->noLinkProduct       = "Stage not linked {$lang->productCommon}";
$lang->execution->recent              = 'Recent visits: ';
$lang->execution->copyNoExecution     = 'There are no ' . $lang->executionCommon . 'available to copy.';
$lang->execution->noTeam              = 'No team members at the moment';
$lang->execution->or                  = ' or ';
$lang->execution->selectProject       = 'Please select project';
$lang->execution->unfoldClosed        = 'Unfold Closed';
$lang->execution->editName            = 'Edit Name';
$lang->execution->setWIP              = 'WIP Settings';
$lang->execution->sortColumn          = 'Kanban Card Sorting';
$lang->execution->batchCreateStory    = "Batch create {$lang->SRCommon}";
$lang->execution->batchCreateTask     = 'Batch create task';
$lang->execution->kanbanNoLinkProduct = "Kanban not linked {$lang->productCommon}";
$lang->execution->myTask              = "My Task";
$lang->execution->list                = "{$lang->executionCommon} List";
$lang->execution->allProject          = 'Tous';

/* Fields of zt_team. */
$lang->execution->root     = 'Root';
$lang->execution->estimate = 'estimate';
$lang->execution->consumed = 'consumed';
$lang->execution->left     = 'Left';

if($this->config->systemMode == 'new')     $lang->execution->copyTeamTip = "select Project/{$lang->execution->common} to copy its members";
if($this->config->systemMode == 'classic') $lang->execution->copyTeamTip = "select Project/{$lang->execution->common} to copy its members";

$lang->execution->start    = 'Démarrer';
$lang->execution->activate = 'Activer';
$lang->execution->putoff   = 'Ajourner';
$lang->execution->suspend  = 'Suspendre';
$lang->execution->close    = 'Fermer';
$lang->execution->export   = 'Exporter';
$lang->execution->next     = "Suivant";

$lang->execution->endList[7]   = '1 Semaine';
$lang->execution->endList[14]  = '2 Semaines';
$lang->execution->endList[31]  = '1 Mois';
$lang->execution->endList[62]  = '2 Mois';
$lang->execution->endList[93]  = '3 Mois';
$lang->execution->endList[186] = '6 Mois';
$lang->execution->endList[365] = '1 Année';

$lang->execution->lifeTimeList['short'] = "Short-Term";
$lang->execution->lifeTimeList['long']  = "Long-Term";
$lang->execution->lifeTimeList['ops']   = "DevOps";

$lang->execution->cfdTypeList['story'] = "View by {$lang->SRCommon}";
$lang->execution->cfdTypeList['task']  = "View by task";
$lang->execution->cfdTypeList['bug']   = "View By bug";

$lang->team->account    = 'Utilisateur';
$lang->team->role       = 'Rôle';
$lang->team->roleAB     = 'Mon Rôle';
$lang->team->join       = 'Ajouté';
$lang->team->hours      = 'Heure/jour';
$lang->team->days       = 'Jour';
$lang->team->totalHours = 'Total Heures';

$lang->team->limited            = 'Restrictions';
$lang->team->limitedList['yes'] = 'Oui';
$lang->team->limitedList['no']  = 'Non';

$lang->execution->basicInfo = 'Informations de base';
$lang->execution->otherInfo = 'Autres Informations';

/* Field value list. */
$lang->execution->statusList['wait']      = 'En attente';
$lang->execution->statusList['doing']     = 'En cours';
$lang->execution->statusList['suspended'] = 'Suspendu';
$lang->execution->statusList['closed']    = 'Fermé';

global $config;
if($config->systemMode == 'new')
{
    $lang->execution->aclList['private'] = 'Private (for team members and project stakeholders)';
    $lang->execution->aclList['open']    = 'Inherited Execution ACL (for who can access the current project)';
}
else
{
    $lang->execution->aclList['private'] = 'Private (for team members and project stakeholders)';
    $lang->execution->aclList['open']    = 'Inherited Execution ACL (for who can access the current project)';
}

$lang->execution->kanbanAclList['private'] = 'Private';
$lang->execution->kanbanAclList['open']    = 'Inherited Project';

$lang->execution->storyPoint = 'Story Point';

$lang->execution->burnByList['left']       = 'View by remaining hours';
$lang->execution->burnByList['estimate']   = "View by plan hours";
$lang->execution->burnByList['storyPoint'] = 'View by story point';

/* Method list. */
$lang->execution->index               = "Accueil {$lang->executionCommon}";
$lang->execution->task                = 'Liste Tâches';
$lang->execution->groupTask           = 'Vision Groupée';
$lang->execution->story               = 'Liste Stories';
$lang->execution->qa                  = 'QA';
$lang->execution->bug                 = 'Liste Bugs';
$lang->execution->testcase            = 'Testcase List';
$lang->execution->dynamic             = 'Historique';
$lang->execution->latestDynamic       = 'Historique';
$lang->execution->build               = 'Liste Builds';
$lang->execution->testtask            = 'Recette';
$lang->execution->burn                = ' Atterrissage';
$lang->execution->computeBurn         = 'Calculer';
$lang->execution->CFD                 = 'Cumulative Flow diagrams';
$lang->execution->computeCFD          = 'Compute Cumulative Flow diagrams';
$lang->execution->burnData            = "Données d'atterrissage";
$lang->execution->fixFirst            = 'Fixer 1er-Jour Estimation';
$lang->execution->team                = 'Membres';
$lang->execution->doc                 = 'Documents';
$lang->execution->doclib              = 'Répertoire de Documents';
$lang->execution->manageProducts      = 'Liaisons du ' . $lang->executionCommon . ' avec les ' . $lang->productCommon . 's';
$lang->execution->linkStory           = 'Stories liées';
$lang->execution->linkStoryByPlan     = 'Stories liées par Plan';
$lang->execution->linkPlan            = 'Plans liés';
$lang->execution->unlinkStoryTasks    = 'Dissocier';
$lang->execution->linkedProducts      = "{$lang->productCommon}s liés à ce {$lang->executionCommon}";
$lang->execution->unlinkedProducts    = "{$lang->productCommon}s dissociés de ce {$lang->executionCommon}";
$lang->execution->view                = "Détail du Execution";
$lang->execution->startAction         = "Start Execution";
$lang->execution->activateAction      = "Activer le Execution";
$lang->execution->delayAction         = "Ajourner le Execution";
$lang->execution->suspendAction       = "Suspendre le Execution";
$lang->execution->closeAction         = "Fermer le Execution";
$lang->execution->testtaskAction      = "Recettes du Execution";
$lang->execution->teamAction          = "Membres du Execution";
$lang->execution->kanbanAction        = "Kaban Execution";
$lang->execution->printKanbanAction   = "Imprimer le Kanban";
$lang->execution->treeAction          = "Arborescence Execution";
$lang->execution->exportAction        = "Exporter Execution";
$lang->execution->computeBurnAction   = "Calculer Atterrissage";
$lang->execution->create              = "Créer {$lang->executionCommon}";
$lang->execution->createExec          = "Create Execution";
$lang->execution->createAction        = "Create {$lang->execution->common}";
$lang->execution->copyExec            = "Copy Execution";
$lang->execution->copy                = "Copier {$lang->executionCommon}";
$lang->execution->delete              = "Supprimer {$lang->executionCommon}";
$lang->execution->deleteAB            = "Delete Execution";
$lang->execution->browse              = "Liste du {$lang->executionCommon}";
$lang->execution->edit                = "Editer {$lang->executionCommon}";
$lang->execution->editAction          = "Edit Execution";
$lang->execution->batchEdit           = "Edition par lot";
$lang->execution->batchEditAction     = "Batch Edit";
$lang->execution->manageMembers       = 'Organiser Equipe';
$lang->execution->unlinkMember        = 'Retirer le membre';
$lang->execution->unlinkStory         = 'Dissocier Story';
$lang->execution->unlinkStoryAB       = 'Dissocier';
$lang->execution->batchUnlinkStory    = 'Dissocier Stories par lot';
$lang->execution->importTask          = 'Transfert Tâche';
$lang->execution->importPlanStories   = 'Lier Stories Par Plan';
$lang->execution->importBug           = 'Importer Bug';
$lang->execution->tree                = 'Arboressence';
$lang->execution->treeTask            = 'Seulement les Tâches';
$lang->execution->treeStory           = 'Seulement les Stories';
$lang->execution->treeOnlyTask        = 'Seulement les Tâches';
$lang->execution->treeOnlyStory       = 'Seulement les Stories';
$lang->execution->storyKanban         = 'Story Kanban';
$lang->execution->storySort           = 'Rang Story';
$lang->execution->importPlanStory     = $lang->executionCommon . ' est créé!\nVoulez-vous importer des stories qui ont été ajoutées au Plan ?';
$lang->execution->importEditPlanStory = $lang->executionCommon . ' is edited!\nDo you want to import stories that have been linked to the plan? The stories in the draft will be automatically filtered out when imported.';
$lang->execution->needLinkProducts    = 'The execution has not been linked with any product, and the related functions cannot be used. Please link the product first and try again.';
$lang->execution->iteration           = 'Itérations';
$lang->execution->iterationInfo       = '%s Itérations';
$lang->execution->viewAll             = 'Voir Tout';
$lang->execution->testreport          = 'Test Report';
$lang->execution->taskKanban          = 'Task Kanban';
$lang->execution->RDKanban            = 'Research & Development Kanban';

/* Group browsing. */
$lang->execution->allTasks     = 'Toutes';
$lang->execution->assignedToMe = 'à Moi';
$lang->execution->myInvolved   = "Ma part";
$lang->execution->assignedByMe = 'Assignée par moi';

$lang->execution->statusSelects['']             = 'Plus...';
$lang->execution->statusSelects['wait']         = 'En Attente';
$lang->execution->statusSelects['doing']        = 'En Cours';
$lang->execution->statusSelects['undone']       = 'Non terminées';
$lang->execution->statusSelects['finishedbyme'] = 'Terminées par moi';
$lang->execution->statusSelects['done']         = 'Faites';
$lang->execution->statusSelects['closed']       = 'Fermées';
$lang->execution->statusSelects['cancel']       = 'Annulées';
$lang->execution->statusSelects['delayed']      = 'Delayed';

$lang->execution->groups['']           = 'Vision groupée';
$lang->execution->groups['story']      = 'Grouper par Story';
$lang->execution->groups['status']     = 'Grouper par Statut';
$lang->execution->groups['pri']        = 'Grouper par Priorité';
$lang->execution->groups['assignedTo'] = 'Grouper par Assignation';
$lang->execution->groups['finishedBy'] = 'Grouper par Finisseur';
$lang->execution->groups['closedBy']   = 'Grouper par Clôtureur';
$lang->execution->groups['type']       = 'Grouper par Type';

$lang->execution->groupFilter['story']['all']         = 'Toutes';
$lang->execution->groupFilter['story']['linked']      = 'Tâches lies à des stories';
$lang->execution->groupFilter['pri']['all']           = 'Toutes';
$lang->execution->groupFilter['pri']['noset']         = 'Non Spécifiée';
$lang->execution->groupFilter['assignedTo']['undone'] = 'Non Terminées';
$lang->execution->groupFilter['assignedTo']['all']    = 'Toutes';

$lang->execution->byQuery = 'Recherche';

/* Query condition list. */
$lang->execution->allExecution      = "Tous les {$lang->executionCommon}s";
$lang->execution->aboveAllProduct   = "Tous les {$lang->productCommon}s dépendants";
$lang->execution->aboveAllExecution = "Tous les {$lang->executionCommon}s dépendants";

/* Page prompt. */
$lang->execution->linkStoryByPlanTips  = "Cette action va lier toutes les stories incluses dans le plan à ce {$lang->executionCommon}.";
$lang->execution->batchCreateStoryTips = "Please select the product that needs to be created in batches";
$lang->execution->selectExecution      = "Sélectionner {$lang->executionCommon}";
$lang->execution->beginAndEnd          = 'Durée';
$lang->execution->lblStats             = 'Efforts';
$lang->execution->stats                = 'Disponible: <strong>%s</strong>(h). Estimé: <strong>%s</strong>(h). Coût: <strong>%s</strong>(h). Reste: <strong>%s</strong>(h).';
$lang->execution->taskSummary          = "Total des tâches de cette page :<strong>%s</strong>. A Faire: <strong>%s</strong>. En cours: <strong>%s</strong>. &nbsp;&nbsp;&nbsp; Estimé: <strong>%s</strong>(h). Coût: <strong>%s</strong>(h). Reste: <strong>%s</strong>(h).";
$lang->execution->pageSummary          = "Total des tâches de cette page: <strong>%total%</strong>. A Faire: <strong>%wait%</strong>. En cours: <strong>%doing%</strong>. &nbsp;&nbsp;&nbsp; Estimé: <strong>%estimate%</strong>(h). Coût: <strong>%consumed%</strong>(h). Reste: <strong>%left%</strong>(h).";
$lang->execution->checkedSummary       = "Sélectionné: <strong>%total%</strong>. A Faire: <strong>%wait%</strong>. En cours: <strong>%doing%</strong>. &nbsp;&nbsp;&nbsp; Estimé: <strong>%estimate%</strong>(h). Coût: <strong>%consumed%</strong>(h). Reste: <strong>%left%</strong>(h).";
$lang->execution->executionSummary     = "Total executions: <strong>%s</strong>.";
$lang->execution->pageExecSummary      = "Total executions: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.";
$lang->execution->checkedExecSummary   = "Selected: <strong>%total%</strong>. Waiting: <strong>%wait%</strong>. Doing: <strong>%doing%</strong>.";
$lang->execution->memberHoursAB        = "%s a <strong>%s</ strong> heures.";
$lang->execution->memberHours          = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s Heures Disponibles</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->countSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Tâches</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">En Cours</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">A Faire</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->execution->timeSummary          = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Estimé</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Coût</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">Reste</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->groupSummaryAB       = "<div>Tâches <strong>%s ：</strong><span class='text-muted'>A Faire</span> %s &nbsp; <span class='text-muted'>En Cours</span> %s</div><div>Estimé <strong>%s ：</strong><span class='text-muted'>Coût</span> %s &nbsp; <span class='text-muted'>Reste</span> %s</div>";
$lang->execution->wbs                  = "Créer Tâche";
$lang->execution->batchWBS             = "Créer Tâche en lot";
$lang->execution->howToUpdateBurn      = "<a href='https://api.zentao.pm/goto.php?item=burndown' target='_blank' title='Comment mettre à jour le Graphe d´atterrissage ?' class='btn btn-link'>Mise à jour <i class='icon icon-help'></i></a>";
$lang->execution->whyNoStories         = "Aucune story ne peut être associée. Vérifiez s'il existe des stories dans {$lang->executionCommon} qui sont associées à {$lang->productCommon} et vérifiez qu'elles ont bien été validées.";
$lang->execution->productStories       = "Les stories associées au {$lang->executionCommon} sont une portion des stories associées au {$lang->productCommon}. Les stories ne peuvent être associées à un {$lang->executionCommon} qu'après avoir été validées. <a href='%s'> Associer Stories</a> maintenant.";
$lang->execution->haveDraft            = "%s stories sont encore en conception, elles ne peuvent pas être associées au {$lang->executionCommon} actuellement.";
$lang->execution->doneExecutions       = 'Terminé';
$lang->execution->selectDept           = 'Sélection Compartiment';
$lang->execution->selectDeptTitle      = 'Sélection Utilisateur';
$lang->execution->copyTeam             = 'Copier Equipe';
$lang->execution->copyFromTeam         = "Copié de l'Equipe {$lang->executionCommon} : <strong>%s</strong>";
$lang->execution->noMatched            = "Aucun $lang->executionCommon inclus '%s' ne peut être trouvé.";
$lang->execution->copyTitle            = "Choisissez un {$lang->executionCommon} à copier.";
$lang->execution->copyNoExecution      = 'There are no ' . $lang->executionCommon . 'available to copy.';
$lang->execution->copyFromExecution    = "Copié du {$lang->executionCommon} <strong>%s</strong>";
$lang->execution->cancelCopy           = 'Annuler la copie';
$lang->execution->byPeriod             = 'Par Temps';
$lang->execution->byUser               = 'Par Utilisateur';
$lang->execution->noExecution          = "Aucun {$lang->executionCommon}. ";
$lang->execution->noExecutions         = "Aucun {$lang->execution->common}.";
$lang->execution->noPrintData          = "No data can be printed.";
$lang->execution->noMembers            = "Actuellement il n'y a aucun membre dans l'équipe. On ne va pas aller loin... ";
$lang->execution->workloadTotal        = "The cumulative workload ratio should not exceed 100, and the total workload under the current product is: %s";
// $lang->execution->linkProjectStoryTip  = "(Link {$lang->SRCommon} comes from {$lang->SRCommon} linked under the execution)";
$lang->execution->linkAllStoryTip      = "({$lang->SRCommon} has never been linked under the project, and can be directly linked with {$lang->SRCommon} of the product linked with the sprint/stage)";
if($config->systemMode == 'classic') $lang->execution->copyTeamTitle = "Choisissez une Equipe {$lang->executionCommon} à copier.";
if($config->systemMode == 'new')     $lang->execution->copyTeamTitle = "Choisissez une Equipe {$lang->executionCommon} à copier.";

/* Interactive prompts. */
$lang->execution->confirmDelete               = "Voulez-vous réellement supprimer le {$lang->executionCommon}[%s] ?";
$lang->execution->confirmUnlinkMember         = "Voulez-vous retirer cet utilisateur du {$lang->executionCommon} ?";
$lang->execution->confirmUnlinkStory          = "After {$lang->SRCommon} is removed, cased linked to {$lang->SRCommon} will be reomoved and tasks linked to {$lang->SRCommon} will be cancelled. Do you want to continue?";
$lang->execution->confirmSync                 = "After modifying the project, in order to maintain the consistency of data, the data of products, requirements, teams and whitelist associated with the implementation will be synchronized to the new project. Please know.";
$lang->execution->confirmUnlinkExecutionStory = "Do you want to unlink this Story from the project?";
$lang->execution->notAllowedUnlinkStory       = "This {$lang->SRCommon} is linked to the {$lang->executionCommon} of the project. Remove it from the {$lang->executionCommon}, then try again.";
$lang->execution->notAllowRemoveProducts      = "The story of this product is linked with the {$lang->executionCommon}. Unlink it before doing any action.";
$lang->execution->errorNoLinkedProducts       = "Aucun {$lang->productCommon} n'est associé à ce {$lang->executionCommon}. Vous allez être redirigé vers la page {$lang->productCommon} pour en associer un.";
$lang->execution->errorSameProducts           = "Ce {$lang->executionCommon} ne peut pas être associé deux fois au même {$lang->productCommon}. Imaginez un peu les résultats !";
$lang->execution->errorSameBranches           = "{$lang->executionCommon} cannot be linked to the same branch twice";
$lang->execution->errorBegin                  = "The start time of {$lang->executionCommon} cannot be less than the start time of the project %s.";
$lang->execution->errorEnd                    = "The end time of {$lang->executionCommon} cannot be greater than the end time %s of the project.";
$lang->execution->errorLetterProject          = "The start time of {$lang->executionCommon} cannot be less than the start time of the project %s.";
$lang->execution->errorGreaterProject         = "The end time of {$lang->executionCommon} cannot be greater than the end time %s of the project.";
$lang->execution->errorCommonBegin            = 'The start date of ' . $lang->executionCommon . ' should be ≥ the start date of project : %s.';
$lang->execution->errorCommonEnd              = 'The deadline of ' . $lang->executionCommon .  ' should be ≤ the deadline of project : %s.';
$lang->execution->accessDenied                = "Votre accès au {$lang->executionCommon} est refusé ! Désolé.";
$lang->execution->tips                        = 'Note';
$lang->execution->afterInfo                   = "Le {$lang->executionCommon} a été créé avec succès ! Ensuite vous pouvez ";
$lang->execution->setTeam                     = "Composer l'Equipe";
$lang->execution->linkStory                   = 'Stories liées';
$lang->execution->createTask                  = 'Créer des Tâches';
$lang->execution->goback                      = "Revenir en arrière";
$lang->execution->gobackExecution             = "Go Back {$lang->executionCommon} List";
$lang->execution->noweekend                   = 'Exclure les Weekends';
$lang->execution->nodelay                     = 'Exclude Delay Date';
$lang->execution->withweekend                 = 'Inclure les Weekends';
$lang->execution->withdelay                   = 'Include Delay Date';
$lang->execution->interval                    = 'Intervalles';
$lang->execution->fixFirstWithLeft            = 'Mettre à jour les heures également';
$lang->execution->unfinishedExecution         = "This {$lang->executionCommon} has ";
$lang->execution->unfinishedTask              = "[%s] unfinished tasks. ";
$lang->execution->unresolvedBug               = "[%s] unresolved bugs. ";
$lang->execution->projectNotEmpty             = 'Project cannot be empty.';
$lang->execution->confirmStoryToTask          = $lang->SRCommon . '%s are converted to tasks in the current. Do you want to convert them anyways?';
$lang->execution->ge                          = "『%s』should be >= actual begin『%s』.";
$lang->execution->storyDragError              = "The {$lang->SRCommon} is not active. Please activate and drag again.";
$lang->execution->countTip                    = ' (%s member)';
$lang->execution->pleaseInput                 = "Enter";
$lang->execution->week                        = 'week';
$lang->execution->checkedExecutions           = "Pour s électionner l'élément%s.";

/* Statistics. */
$lang->execution->charts = new stdclass();
$lang->execution->charts->burn = new stdclass();
$lang->execution->charts->burn->graph = new stdclass();
$lang->execution->charts->burn->graph->caption      = " Graphe d'atterrissage";
$lang->execution->charts->burn->graph->xAxisName    = "Date";
$lang->execution->charts->burn->graph->yAxisName    = "Heure";
$lang->execution->charts->burn->graph->baseFontSize = 12;
$lang->execution->charts->burn->graph->formatNumber = 0;
$lang->execution->charts->burn->graph->animation    = 0;
$lang->execution->charts->burn->graph->rotateNames  = 1;
$lang->execution->charts->burn->graph->showValues   = 0;
$lang->execution->charts->burn->graph->reference    = 'Idéal';
$lang->execution->charts->burn->graph->actuality    = 'Actuel';
$lang->execution->charts->burn->graph->delay        = 'Delay';

$lang->execution->charts->cfd = new stdclass();
$lang->execution->charts->cfd->cfdTip        = "<p>
1. Le CFD（Cumulative Flow Diagram）indique la tendance de la charge de travail cumulée de chaque étape au fil du temps.<br>
2. L'axe horizontal représente la date et l'axe vertical représente le nombre de travaux.<br>
3. Ce CFD vous permet de calculer les quantités de travail en cours (WIP), les taux de livraison et les délais moyens pour comprendre comment votre équipe travaille.<p>";
$lang->execution->charts->cfd->cycleTime     = 'Average cycle time';
$lang->execution->charts->cfd->cycleTimeTip  = 'Average cycle time of each card from development start to completion';
$lang->execution->charts->cfd->throughput    = 'Throughput Rate';
$lang->execution->charts->cfd->throughputTip = 'Throughput Rate = WIP / Average cycle time';

$lang->execution->charts->cfd->begin          = 'Begin';
$lang->execution->charts->cfd->end            = 'End';
$lang->execution->charts->cfd->errorBegin     = 'The start time cannot be greater than the end time.';
$lang->execution->charts->cfd->errorDateRange = 'The Cumulative Flow Diagram（CFD） only provides data display within 3 months.';
$lang->execution->charts->cfd->dateRangeTip   = 'CFD only shows the data within 3 months';

$lang->execution->placeholder = new stdclass();
$lang->execution->placeholder->code      = "Abréviation du nom du {$lang->executionCommon}";
$lang->execution->placeholder->totalLeft = "Heures estimées le premier jour du {$lang->executionCommon}.";

$lang->execution->selectGroup = new stdclass();
$lang->execution->selectGroup->done = '(Fait)';

$lang->execution->orderList['order_asc']  = "Story Rang Ascendant";
$lang->execution->orderList['order_desc'] = "Story Rang Descendant";
$lang->execution->orderList['pri_asc']    = "Story Priorité Ascendante";
$lang->execution->orderList['pri_desc']   = "Story Priorité Descendante";
$lang->execution->orderList['stage_asc']  = "Story Phase Ascendante";
$lang->execution->orderList['stage_desc'] = "Story Phase Descendante";

$lang->execution->kanban        = "Kanban";
$lang->execution->kanbanSetting = "Paramétrage";
$lang->execution->setKanban     = "Paramétrage";
$lang->execution->resetKanban   = "Réinitialiser";
$lang->execution->printKanban   = "Imprimer Kanban";
$lang->execution->fullScreen    = "Full Screen";
$lang->execution->bugList       = "Bugs";

$lang->execution->kanbanHideCols   = 'Colonnes masquées';
$lang->execution->kanbanShowOption = 'Déplier';
$lang->execution->kanbanColsColor  = 'Personnalisation Couleurs';
$lang->execution->kanbanCardsUnit  = 'X';

$lang->execution->kanbanViewList['all']   = 'All';
$lang->execution->kanbanViewList['story'] = "{$lang->SRCommon}";
$lang->execution->kanbanViewList['bug']   = 'Bug';
$lang->execution->kanbanViewList['task']  = 'Task';

$lang->kanbanSetting = new stdclass();
$lang->kanbanSetting->noticeReset     = 'Voulez-vous réinitialiser le tableau Kanban ?';
$lang->kanbanSetting->optionList['0'] = 'Masquer';
$lang->kanbanSetting->optionList['1'] = 'Montrer';

$lang->printKanban = new stdclass();
$lang->printKanban->common  = 'Imprimer Kanban';
$lang->printKanban->content = 'Contenu';
$lang->printKanban->print   = 'Imprimer';

$lang->printKanban->taskStatus = 'Statut';

$lang->printKanban->typeList['all']       = 'Tout';
$lang->printKanban->typeList['increment'] = 'Incrément';

$lang->execution->typeList['']       = '';
$lang->execution->typeList['stage']  = 'Stage';
$lang->execution->typeList['sprint'] = $lang->executionCommon;
$lang->execution->typeList['kanban'] = 'Kanban';

$lang->execution->featureBar['task']['all']          = $lang->execution->allTasks;
$lang->execution->featureBar['task']['unclosed']     = $lang->execution->unclosed;
$lang->execution->featureBar['task']['assignedtome'] = $lang->execution->assignedToMe;
$lang->execution->featureBar['task']['myinvolved']   = $lang->execution->myInvolved;
$lang->execution->featureBar['task']['assignedbyme'] = $lang->execution->assignedByMe;
$lang->execution->featureBar['task']['needconfirm']  = 'Changed';
$lang->execution->featureBar['task']['status']       = $lang->execution->statusSelects[''];

$lang->execution->featureBar['all']['all']       = $lang->execution->all;
$lang->execution->featureBar['all']['undone']    = $lang->execution->undone;
$lang->execution->featureBar['all']['wait']      = $lang->execution->statusList['wait'];
$lang->execution->featureBar['all']['doing']     = $lang->execution->statusList['doing'];
$lang->execution->featureBar['all']['suspended'] = $lang->execution->statusList['suspended'];
$lang->execution->featureBar['all']['closed']    = $lang->execution->statusList['closed'];

$lang->execution->featureBar['bug']['all']        = 'All';
$lang->execution->featureBar['bug']['unresolved'] = 'Active';

$lang->execution->featureBar['build']['all'] = 'Build List';

$lang->execution->myExecutions = "J'étais impliqué";
$lang->execution->doingProject = 'Projets en cours';

$lang->execution->kanbanColType['wait']      = $lang->execution->statusList['wait']      . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['doing']     = $lang->execution->statusList['doing']     . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['suspended'] = $lang->execution->statusList['suspended'] . ' ' . $lang->execution->common;
$lang->execution->kanbanColType['closed']    = $lang->execution->statusList['closed']    . ' ' . $lang->execution->common;

$lang->execution->treeLevel = array();
$lang->execution->treeLevel['all']   = 'Déplier Tout';
$lang->execution->treeLevel['root']  = 'Masquer Tout';
$lang->execution->treeLevel['task']  = 'Stories&Tâches';
$lang->execution->treeLevel['story'] = 'Seulement Stories';

$lang->execution->action = new stdclass();
$lang->execution->action->opened               = '$date, created by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->managed              = '$date, managed by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->edited               = '$date, edited by <strong>$actor</strong>. $extra' . "\n";
$lang->execution->action->extra                = 'Linked products is %s.';
$lang->execution->action->startbychildactivate = '$date, activating the sub stage sets the execution status as Ongoing.' . "\n";
$lang->execution->action->waitbychilddelete    = '$date, deleting the sub stage sets the execution status as waitting.' . "\n";
$lang->execution->action->closebychilddelete   = '$date, deleting the sub stage sets the execution status as closing.' . "\n";
$lang->execution->action->closebychildclose    = '$date, closing the sub stage sets the execution status as closing.' . "\n";

$lang->execution->startbychildactivate = 'activated';
$lang->execution->waitbychilddelete    = 'stop';
$lang->execution->closebychilddelete   = 'closed';
$lang->execution->closebychildclose    = 'closed';

$lang->execution->statusColorList = array();
$lang->execution->statusColorList['wait']      = '#0991FF';
$lang->execution->statusColorList['doing']     = '#0BD986';
$lang->execution->statusColorList['suspended'] = '#fdc137';
$lang->execution->statusColorList['closed']    = '#838A9D';

if(!isset($lang->execution->gantt)) $lang->execution->gantt = new stdclass();
$lang->execution->gantt->progressColor[0] = '#B7B7B7';
$lang->execution->gantt->progressColor[1] = '#FF8287';
$lang->execution->gantt->progressColor[2] = '#FFC73A';
$lang->execution->gantt->progressColor[3] = '#6BD5F5';
$lang->execution->gantt->progressColor[4] = '#9DE88A';
$lang->execution->gantt->progressColor[5] = '#9BA8FF';

$lang->execution->gantt->color[0] = '#E7E7E7';
$lang->execution->gantt->color[1] = '#FFDADB';
$lang->execution->gantt->color[2] = '#FCECC1';
$lang->execution->gantt->color[3] = '#D3F3FD';
$lang->execution->gantt->color[4] = '#DFF5D9';
$lang->execution->gantt->color[5] = '#EBDCF9';

$lang->execution->gantt->textColor[0] = '#2D2D2D';
$lang->execution->gantt->textColor[1] = '#8D0308';
$lang->execution->gantt->textColor[2] = '#9D4200';
$lang->execution->gantt->textColor[3] = '#006D8E';
$lang->execution->gantt->textColor[4] = '#1A8100';
$lang->execution->gantt->textColor[5] = '#660ABC';

$lang->execution->gantt->stage = new stdclass();
$lang->execution->gantt->stage->progressColor = '#70B8FE';
$lang->execution->gantt->stage->color         = '#D2E7FC';
$lang->execution->gantt->stage->textColor     = '#0050A7';

$lang->execution->gantt->defaultColor         = '#EBDCF9';
$lang->execution->gantt->defaultProgressColor = '#9BA8FF';
$lang->execution->gantt->defaultTextColor     = '#660ABC';

$lang->execution->gantt->bar_height = '24';

$lang->execution->gantt->exportImg  = 'Export as Image';
$lang->execution->gantt->exportPDF  = 'Export as PDF';
$lang->execution->gantt->exporting  = 'Exporting...';
$lang->execution->gantt->exportFail = 'Failed to export.';

$lang->execution->boardColorList = array('#32C5FF', '#006AF1', '#9D28B2', '#FF8F26', '#7FBB00', '#424BAC', '#66c5f8', '#EC2761');
