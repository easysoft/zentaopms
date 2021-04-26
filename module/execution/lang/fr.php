<?php
/**
 * The project module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: en.php 5094 2013-07-10 08:46:15Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.pm
 */
/* Fields. */
$lang->execution->allExecutions   = 'Tous les ' . $lang->executionCommon . 's';
$lang->execution->allExecutionAB  = 'All Executions';
$lang->execution->id              = $lang->executionCommon . ' ID';
$lang->execution->type            = $lang->executionCommon . 'Type';
$lang->execution->name            = $lang->executionCommon . 'Name';
$lang->execution->code            = $lang->executionCommon . 'Code';
$lang->execution->project         = 'Project';
$lang->execution->execName        = 'Execution Name';
$lang->execution->execCode        = 'Execution Code';
$lang->execution->execType        = 'Execution Type';
$lang->execution->stage           = 'Etape';
$lang->execution->pri             = 'Priorité';
$lang->execution->openedBy        = 'Ouvert par';
$lang->execution->openedDate      = "Date d'ouverture";
$lang->execution->closedBy        = 'Fermé par';
$lang->execution->closedDate      = 'Date de fermeture';
$lang->execution->canceledBy      = 'Annulé par';
$lang->execution->canceledDate    = "Date d'annulation";
$lang->execution->begin           = 'Début';
$lang->execution->end             = 'Fin';
$lang->execution->dateRange       = 'Durée';
$lang->execution->to              = 'à';
$lang->execution->days            = 'Budget Jours';
$lang->execution->day             = ' Jours';
$lang->execution->workHour        = ' Heures';
$lang->execution->workHourUnit    = 'H';
$lang->execution->totalHours      = 'Budget (Heure)';
$lang->execution->totalDays       = 'Budget (Jour)';
$lang->execution->status          = $lang->executionCommon . 'Status';
$lang->execution->execStatus      = 'Status';
$lang->execution->subStatus       = 'Sub Status';
$lang->execution->desc            = $lang->executionCommon . 'Description';
$lang->execution->execDesc        = 'Description';
$lang->execution->owner           = 'Propriétaire';
$lang->execution->PO              = "Propriétaire {$lang->executionCommon}";
$lang->execution->PM              = "Directeur {$lang->executionCommon}";
$lang->execution->execPM          = "Directeur Execution";
$lang->execution->QD              = 'Test Manager';
$lang->execution->RD              = 'Release Manager';
$lang->execution->release         = 'Release';
$lang->execution->acl             = "Contrôle d'accès";
$lang->execution->teamname        = "Nom de l'équipe";
$lang->execution->order           = "Rang du {$lang->executionCommon}";
$lang->execution->orderAB         = "Rang";
$lang->execution->products        = "{$lang->productCommon} liés";
$lang->execution->whitelist       = 'Liste Blanche';
$lang->execution->addWhitelist    = 'Add Whitelist';
$lang->execution->unbindWhitelist = 'Remove Whitelist';
$lang->execution->totalEstimate   = 'Estimé';
$lang->execution->totalConsumed   = 'Coût';
$lang->execution->totalLeft       = 'Reste';
$lang->execution->progress        = ' Progrès';
$lang->execution->hours           = 'Estimé: %s, Coût: %s, Reste: %s.';
$lang->execution->viewBug         = 'Bugs';
$lang->execution->noProduct       = "Aucun {$lang->productCommon} pour l'instant.";
$lang->execution->createStory     = "Créer une Story";
$lang->execution->storyTitle      = "Story Name";
$lang->execution->all             = "Tous les {$lang->executionCommon}s";
$lang->execution->undone          = 'Non Terminé';
$lang->execution->unclosed        = 'Non Fermées';
$lang->execution->typeDesc        = "Aucune {$lang->SRCommon}, bug, build, test, ou graphe d'atterrissage n'est disponible";
$lang->execution->mine            = 'A Moi: ';
$lang->execution->involved        = 'Mine: ';
$lang->execution->other           = 'Autres:';
$lang->execution->deleted         = 'Supprimé';
$lang->execution->delayed         = 'Delayed';
$lang->execution->product         = $lang->execution->products;
$lang->execution->readjustTime    = "Ajuster Début et Fin du {$lang->executionCommon}";
$lang->execution->readjustTask    = 'Ajuster Début et Fin de la Tâche';
$lang->execution->effort          = 'Effort';
$lang->execution->relatedMember   = 'Equipe';
$lang->execution->watermark       = 'Exporté par ZenTao';
$lang->execution->burnXUnit       = '(Date)';
$lang->execution->burnYUnit       = '(Hours)';
$lang->execution->waitTasks       = 'Waiting Tasks';
$lang->execution->viewByUser      = 'Par Utilisateur';
$lang->execution->oneProduct      = "Only one stage can be linked {$lang->productCommon}";
$lang->execution->noLinkProduct   = "Stage not linked {$lang->productCommon}";
$lang->execution->recent          = 'Recent visits: ';
$lang->execution->copyNoExecution = 'There are no ' . $lang->executionCommon . 'available to copy.';

$lang->execution->start    = 'Démarrer';
$lang->execution->activate = 'Activer';
$lang->execution->putoff   = 'Ajourner';
$lang->execution->suspend  = 'Suspendre';
$lang->execution->close    = 'Fermer';
$lang->execution->export   = 'Exporter';

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

$lang->team = new stdclass();
$lang->team->account    = 'Utilisateur';
$lang->team->role       = 'Rôle';
$lang->team->join       = 'Ajouté';
$lang->team->hours      = 'Heure/jour';
$lang->team->days       = 'Jour';
$lang->team->totalHours = 'Total Heures';

$lang->team->limited            = 'Restrictions';
$lang->team->limitedList['yes'] = 'Oui';
$lang->team->limitedList['no']  = 'Non';

$lang->execution->basicInfo = 'Informations de base';
$lang->execution->otherInfo = 'Autres Informations';

/* 字段取值列表。*/
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
    $lang->execution->aclList['open']    = "Public (Users who can visit {$lang->executionCommon} can access it.)";
}

$lang->execution->storyPoint = 'Story Point';

$lang->execution->burnByList['left']       = 'View by remaining hours';
$lang->execution->burnByList['estimate']   = "View by plan hours";
$lang->execution->burnByList['storyPoint'] = 'View by story point';

/* Méthode List */
$lang->execution->index             = "Accueil {$lang->executionCommon}";
$lang->execution->task              = 'Liste Tâches';
$lang->execution->groupTask         = 'Vision Groupée';
$lang->execution->story             = 'Liste Stories';
$lang->execution->qa                = 'QA';
$lang->execution->bug               = 'Liste Bugs';
$lang->execution->testcase          = 'Testcase List';
$lang->execution->dynamic           = 'Historique';
$lang->execution->latestDynamic     = 'Historique';
$lang->execution->build             = 'Liste Builds';
$lang->execution->testtask          = 'Recette';
$lang->execution->burn              = ' Atterrissage';
$lang->execution->computeBurn       = 'Calculer';
$lang->execution->burnData          = "Données d'atterrissage";
$lang->execution->fixFirst          = 'Fixer 1er-Jour Estimation';
$lang->execution->team              = 'Membres';
$lang->execution->doc               = 'Documents';
$lang->execution->doclib            = 'Répertoire de Documents';
$lang->execution->manageProducts    = 'Liaisons du ' . $lang->executionCommon . ' avec les ' . $lang->productCommon . 's';
$lang->execution->linkStory         = 'Stories liées';
$lang->execution->linkStoryByPlan   = 'Stories liées par Plan';
$lang->execution->linkPlan          = 'Plans liés';
$lang->execution->unlinkStoryTasks  = 'Dissocier';
$lang->execution->linkedProducts    = "{$lang->productCommon}s liés à ce {$lang->executionCommon}";
$lang->execution->unlinkedProducts  = "{$lang->productCommon}s dissociés de ce {$lang->executionCommon}";
$lang->execution->view              = "Détail du Execution";
$lang->execution->startAction       = "Commencer le "
$lang->execution->activateAction    = "Activer le Execution";
$lang->execution->delayAction       = "Ajourner le Execution";
$lang->execution->suspendAction     = "Suspendre le Execution";
$lang->execution->closeAction       = "Fermer le Execution";
$lang->execution->testtaskAction    = "Recettes du Execution";
$lang->execution->teamAction        = "Membres du Execution";
$lang->execution->kanbanAction      = "Kaban Execution";
$lang->execution->printKanbanAction = "Imprimer le Kanban";
$lang->execution->treeAction        = "Arborescence Execution";
$lang->execution->exportAction      = "Exporter Execution";
$lang->execution->computeBurnAction = "Calculer Atterrissage";
$lang->execution->create            = "Créer {$lang->executionCommon}";
$lang->execution->createExec        = "Create Execution";
$lang->execution->copyExec          = "Copy Execution";
$lang->execution->copy              = "Copier {$lang->executionCommon}";
$lang->execution->delete            = "Supprimer {$lang->executionCommon}";
$lang->execution->deleteAB          = "Delete Execution";
$lang->execution->browse            = "Liste du {$lang->executionCommon}";
$lang->execution->list              = "{$lang->executionCommon} List";
$lang->execution->edit              = "Editer {$lang->executionCommon}";
$lang->execution->editAB            = "Edit Execution";
$lang->execution->batchEdit         = "Edition par lot";
$lang->execution->batchEditAB       = "Batch Edit";
$lang->execution->manageMembers     = 'Organiser Equipe';
$lang->execution->unlinkMember      = 'Retirer le membre';
$lang->execution->unlinkStory       = 'Dissocier Story';
$lang->execution->unlinkStoryAB     = 'Dissocier';
$lang->execution->batchUnlinkStory  = 'Dissocier Stories par lot';
$lang->execution->importTask        = 'Transfert Tâche';
$lang->execution->importPlanStories = 'Lier Stories Par Plan';
$lang->execution->importBug         = 'Importer Bug';
$lang->execution->tree              = 'Arboressence';
$lang->execution->treeTask          = 'Seulement les Tâches';
$lang->execution->treeStory         = 'Seulement les Stories';
$lang->execution->treeOnlyTask      = 'Seulement les Tâches';
$lang->execution->treeOnlyStory     = 'Seulement les Stories';
$lang->execution->storyKanban       = 'Story Kanban';
$lang->execution->storySort         = 'Rang Story';
$lang->execution->importPlanStory   = $lang->executionCommon . ' est créé!\nVoulez-vous importer des stories qui ont été ajoutées au Plan ?';
$lang->execution->iteration         = 'Itérations';
$lang->execution->iterationInfo     = '%s Itérations';
$lang->execution->viewAll           = 'Voir Tout';

/* 分组浏览。*/
$lang->execution->allTasks     = 'Voir Toutes';
$lang->execution->assignedToMe = 'à Moi';
$lang->execution->myInvolved   = "Où j'ai participé";

$lang->execution->statusSelects['']             = 'Plus...';
$lang->execution->statusSelects['wait']         = 'En Attente';
$lang->execution->statusSelects['doing']        = 'En Cours';
$lang->execution->statusSelects['undone']       = 'Non terminées';
$lang->execution->statusSelects['finishedbyme'] = 'Terminées par moi';
$lang->execution->statusSelects['done']         = 'Faites';
$lang->execution->statusSelects['closed']       = 'Fermées';
$lang->execution->statusSelects['cancel']       = 'Annulées';

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

/* 查询条件列表。*/
$lang->execution->allExecution      = "Tous les {$lang->executionCommon}s";
$lang->execution->aboveAllProduct = "Tous les {$lang->productCommon}s dépendants";
$lang->execution->aboveAllExecution = "Tous les {$lang->executionCommon}s dépendants";

/* 页面提示。*/
$lang->execution->linkStoryByPlanTips = "Cette action va lier toutes les stories incluses dans le plan à ce {$lang->executionCommon}.";
$lang->execution->selectExecution     = "Sélectionner {$lang->executionCommon}";
$lang->execution->beginAndEnd         = 'Durée';
$lang->execution->lblStats            = 'Efforts';
$lang->execution->stats               = 'Disponible: <strong>%s</strong>(h). Estimé: <strong>%s</strong>(h). Coût: <strong>%s</strong>(h). Reste: <strong>%s</strong>(h).';
$lang->execution->taskSummary         = "Total des tâches de cette page :<strong>%s</strong>. A Faire: <strong>%s</strong>. En cours: <strong>%s</strong>. &nbsp;&nbsp;&nbsp; Estimé: <strong>%s</strong>(h). Coût: <strong>%s</strong>(h). Reste: <strong>%s</strong>(h).";
$lang->execution->pageSummary         = "Total des tâches de cette page: <strong>%total%</strong>. A Faire: <strong>%wait%</strong>. En cours: <strong>%doing%</strong>. &nbsp;&nbsp;&nbsp; Estimé: <strong>%estimate%</strong>(h). Coût: <strong>%consumed%</strong>(h). Reste: <strong>%left%</strong>(h).";
$lang->execution->checkedSummary      = "Sélectionné: <strong>%total%</strong>. A Faire: <strong>%wait%</strong>. En cours: <strong>%doing%</strong>. &nbsp;&nbsp;&nbsp; Estimé: <strong>%estimate%</strong>(h). Coût: <strong>%consumed%</strong>(h). Reste: <strong>%left%</strong>(h).";
$lang->execution->memberHoursAB       = "%s a <strong>%s</ strong> heures.";
$lang->execution->memberHours         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s Heures Disponibles</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->countSummary        = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Tâches</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">En Cours</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">A Faire</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->execution->timeSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Estimé</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Coût</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">Reste</div><div class="segment-value">%s</div></div></div></div>';
$lang->execution->groupSummaryAB      = "<div>Tâches <strong>%s ：</strong><span class='text-muted'>A Faire</span> %s &nbsp; <span class='text-muted'>En Cours</span> %s</div><div>Estimé <strong>%s ：</strong><span class='text-muted'>Coût</span> %s &nbsp; <span class='text-muted'>Reste</span> %s</div>";
$lang->execution->wbs                 = "Créer Tâche";
$lang->execution->batchWBS            = "Créer Tâche en lot";
$lang->execution->howToUpdateBurn     = "<a href='https://api.zentao.pm/goto.php?item=burndown' target='_blank' title='Comment mettre à jour le Graphe d´atterrissage ?' class='btn btn-link'>Mise à jour <i class='icon icon-help'></i></a>";
$lang->execution->whyNoStories        = "Aucune story ne peut être associée. Vérifiez s'il existe des stories dans {$lang->executionCommon} qui sont associées à {$lang->productCommon} et vérifiez qu'elles ont bien été validées.";
$lang->execution->productStories      = "Les stories associées au {$lang->executionCommon} sont une portion des stories associées au {$lang->productCommon}. Les stories ne peuvent être associées à un {$lang->executionCommon} qu'après avoir été validées. <a href='%s'> Associer Stories</a> maintenant.";
$lang->execution->haveDraft           = "%s stories sont encore en conception, elles ne peuvent pas être associées au {$lang->executionCommon} actuellement.";
$lang->execution->doneExecutions      = 'Terminé';
$lang->execution->selectDept          = 'Sélection Compartiment';
$lang->execution->selectDeptTitle     = 'Sélection Utilisateur';
$lang->execution->copyTeam            = 'Copier Equipe';
$lang->execution->copyFromTeam        = "Copié de l'Equipe {$lang->executionCommon} : <strong>%s</strong>";
$lang->execution->noMatched           = "Aucun $lang->executionCommon inclus '%s' ne peut être trouvé.";
$lang->execution->copyTitle           = "Choisissez un {$lang->executionCommon} à copier.";
$lang->execution->copyTeamTitle       = "Choisissez une Equipe {$lang->executionCommon} à copier.";
$lang->execution->copyNoExecution     = "Aucun {$lang->executionCommon} ne peut être copié.";
$lang->execution->copyFromExecution   = "Copié du {$lang->executionCommon} <strong>%s</strong>";
$lang->execution->cancelCopy          = 'Annuler la copie';
$lang->execution->byPeriod            = 'Par Temps';
$lang->execution->byUser              = 'Par Utilisateur';
$lang->execution->noExecution         = "Aucun {$lang->executionCommon}. ";
$lang->execution->noExecutions        = "Aucun {$lang->execution->common}.";
$lang->execution->noMembers           = "Actuellement il n'y a aucun membre dans l'équipe. On ne va pas aller loin... ";
$lang->execution->workloadTotal       = "The cumulative workload ratio should not exceed 100, and the total workload under the current product is: %s";
$lang->execution->linkPRJStoryTip     = "(Link {$lang->SRCommon} comes from {$lang->SRCommon} linked under the project)";
$lang->execution->linkAllStoryTip     = "({$lang->SRCommon} has never been linked under the project, and can be directly linked with {$lang->SRCommon} of the product linked with the sprint/stage)";

/* 交互提示。*/
$lang->execution->confirmDelete             = "Voulez-vous réellement supprimer le {$lang->executionCommon}[%s] ?";
$lang->execution->confirmUnlinkMember       = "Voulez-vous retirer cet utilisateur du {$lang->executionCommon} ?";
$lang->execution->confirmUnlinkStory        = "Voulez-vous retirer cette Story du {$lang->executionCommon} ?";
$lang->execution->confirmUnlinkExecutionStory = "Do you want to unlink this Story from the project?";
$lang->execution->notAllowedUnlinkStory     = "This {$lang->SRCommon} is linked to the {$lang->executionCommon} of the project. Remove it from the {$lang->executionCommon}, then try again.";
$lang->execution->notAllowRemoveProducts    = "The story of this product is linked with the {$lang->executionCommon}. Unlink it before doing any action.";
$lang->execution->errorNoLinkedProducts     = "Aucun {$lang->productCommon} n'est associé à ce {$lang->executionCommon}. Vous allez être redirigé vers la page {$lang->productCommon} pour en associer un.";
$lang->execution->errorSameProducts         = "Ce {$lang->executionCommon} ne peut pas être associé deux fois au même {$lang->productCommon}. Imaginez un peu les résultats !";
$lang->execution->accessDenied              = "Votre accès au {$lang->executionCommon} est refusé ! Désolé.";
$lang->execution->tips                      = 'Note';
$lang->execution->afterInfo                 = "Le {$lang->executionCommon} a été créé avec succès ! Ensuite vous pouvez ";
$lang->execution->setTeam                   = "Composer l'Equipe";
$lang->execution->linkStory                 = 'Associer Story';
$lang->execution->createTask                = 'Créer des Tâches';
$lang->execution->goback                    = "Revenir en arrière";
$lang->execution->noweekend                 = 'Exclure les Weekends';
$lang->execution->withweekend               = 'Inclure les Weekends';
$lang->execution->interval                  = 'Intervalles';
$lang->execution->fixFirstWithLeft          = 'Mettre à jour les heures également';
$lang->execution->unfinishedExecution         = "This {$lang->executionCommon} has ";
$lang->execution->unfinishedTask            = "[%s] unfinished tasks. ";
$lang->execution->unresolvedBug             = "[%s] unresolved bugs. ";
$lang->execution->projectNotEmpty           = 'Project cannot be empty.';

/* 统计。*/
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
$lang->execution->resetKanban   = "Réinitialiser";
$lang->execution->printKanban   = "Impression";
$lang->execution->bugList       = "Bugs";

$lang->execution->kanbanHideCols   = 'Colonnes masquées';
$lang->execution->kanbanShowOption = 'Déplier';
$lang->execution->kanbanColsColor  = 'Personnalisation Couleurs';

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

$lang->execution->featureBar['task']['all']          = $lang->execution->allTasks;
$lang->execution->featureBar['task']['unclosed']     = $lang->execution->unclosed;
$lang->execution->featureBar['task']['assignedtome'] = $lang->execution->assignedToMe;
$lang->execution->featureBar['task']['myinvolved']   = $lang->execution->myInvolved;
$lang->execution->featureBar['task']['delayed']      = 'Ajournées';
$lang->execution->featureBar['task']['needconfirm']  = 'A confirmer';
$lang->execution->featureBar['task']['status']       = $lang->execution->statusSelects[''];

$lang->execution->featureBar['all']['all']       = $lang->execution->all;
$lang->execution->featureBar['all']['undone']    = $lang->execution->undone;
$lang->execution->featureBar['all']['wait']      = $lang->execution->statusList['wait'];
$lang->execution->featureBar['all']['doing']     = $lang->execution->statusList['doing'];
$lang->execution->featureBar['all']['suspended'] = $lang->execution->statusList['suspended'];
$lang->execution->featureBar['all']['closed']    = $lang->execution->statusList['closed'];

$lang->execution->treeLevel = array();
$lang->execution->treeLevel['all']   = 'Déplier Tout';
$lang->execution->treeLevel['root']  = 'Masquer Tout';
$lang->execution->treeLevel['task']  = 'Stories&Tâches';
$lang->execution->treeLevel['story'] = 'Seulement Stories';
