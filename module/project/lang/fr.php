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
$lang->project->common        = $lang->projectCommon;
$lang->project->allProjects   = 'Tous les ' . $lang->projectCommon . 's';
$lang->project->id            = $lang->projectCommon . ' ID';
$lang->project->type          = 'Type';
$lang->project->name          = "Nom du {$lang->projectCommon}";
$lang->project->code          = 'Code';
$lang->project->statge        = 'Etape';
$lang->project->pri           = 'Priorité';
$lang->project->openedBy      = 'Ouvert par';
$lang->project->openedDate    = "Date d'ouverture";
$lang->project->closedBy      = 'Fermé par';
$lang->project->closedDate    = 'Date de fermeture';
$lang->project->canceledBy    = 'Annulé par';
$lang->project->canceledDate  = "Date d'annulation";
$lang->project->begin         = 'Début';
$lang->project->end           = 'Fin';
$lang->project->dateRange     = 'Durée';
$lang->project->to            = 'à';
$lang->project->days          = 'Budget Jours';
$lang->project->day           = ' Jours';
$lang->project->workHour      = ' Heures';
$lang->project->totalHours    = 'Budget (Heure)';
$lang->project->totalDays     = 'Budget (Jour)';
$lang->project->status        = 'Statut';
$lang->project->subStatus     = 'Sous-statut';
$lang->project->desc          = 'Description';
$lang->project->owner         = 'Propriétaire';
$lang->project->PO            = "Propriétaire {$lang->projectCommon}";
$lang->project->PM            = "Directeur {$lang->projectCommon}";
$lang->project->QD            = 'Quality Manager';
$lang->project->RD            = 'Release Manager';
$lang->project->qa            = 'QA';
$lang->project->release       = 'Release';
$lang->project->acl           = "Contrôle d'accès";
$lang->project->teamname      = "Nom de l'équipe";
$lang->project->order         = "Rang du {$lang->projectCommon}";
$lang->project->orderAB       = "Rang";
$lang->project->products      = "{$lang->productCommon} liés";
$lang->project->whitelist     = 'Liste Blanche';
$lang->project->totalEstimate = 'Estimé';
$lang->project->totalConsumed = 'Coût';
$lang->project->totalLeft     = 'Reste';
$lang->project->progress      = ' Progrès';
$lang->project->hours         = 'Estimé: %s, Coût: %s, Reste: %s.';
$lang->project->viewBug       = 'Bugs';
$lang->project->noProduct     = "Aucun {$lang->productCommon} pour l'instant.";
$lang->project->createStory   = "Créer une Story";
$lang->project->all           = "Tous les {$lang->projectCommon}s";
$lang->project->undone        = 'Non Terminé';
$lang->project->unclosed      = 'Non Fermées';
$lang->project->typeDesc      = "Aucune {$lang->storyCommon}, bug, build, test, ou graphe d'atterrissage n'est disponible";
$lang->project->mine          = 'A Moi: ';
$lang->project->other         = 'Autres:';
$lang->project->deleted       = 'Supprimé';
$lang->project->delayed       = 'Ajourné';
$lang->project->product       = $lang->project->products;
$lang->project->readjustTime  = "Ajuster Début et Fin du {$lang->projectCommon}";
$lang->project->readjustTask  = 'Ajuster Début et Fin de la Tâche';
$lang->project->effort        = 'Effort';
$lang->project->relatedMember = 'Equipe';
$lang->project->watermark     = 'Exporté par ZenTao';
$lang->project->viewByUser    = 'Par Utilisateur';

$lang->project->start    = 'Démarrer';
$lang->project->activate = 'Activer';
$lang->project->putoff   = 'Ajourner';
$lang->project->suspend  = 'Suspendre';
$lang->project->close    = 'Fermer';
$lang->project->export   = 'Exporter';

$lang->project->typeList['sprint']    = 'Sprint';
$lang->project->typeList['waterfall'] = 'En Cascade';
$lang->project->typeList['ops']       = 'OPS';

$lang->project->endList[7]   = '1 Semaine';
$lang->project->endList[14]  = '2 Semaines';
$lang->project->endList[31]  = '1 Mois';
$lang->project->endList[62]  = '2 Mois';
$lang->project->endList[93]  = '3 Mois';
$lang->project->endList[186] = '6 Mois';
$lang->project->endList[365] = '1 Année';

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

$lang->project->basicInfo = 'Informations de base';
$lang->project->otherInfo = 'Autres Informations';

/* 字段取值列表。*/
$lang->project->statusList['wait']      = 'En attente';
$lang->project->statusList['doing']     = 'En cours';
$lang->project->statusList['suspended'] = 'Suspendu';
$lang->project->statusList['closed']    = 'Fermé';

$lang->project->aclList['open']    = "Défaut (les utilisateurs qui peuvent consulter l'onglet {$lang->projectCommon} peuvent y accéder.)";
$lang->project->aclList['private'] = "Privé (réservé aux membres de l'équipe.)";
$lang->project->aclList['custom']  = "Liste blanche (seuls les membres de l'équipe et de la Liste Blanche peuvent y accéder.)";

/* 方法列表。 Méthode List */
$lang->project->index             = "Accueil {$lang->projectCommon}";
$lang->project->task              = 'Liste Tâches';
$lang->project->groupTask         = 'Vision Groupée';
$lang->project->story             = 'Liste Stories';
$lang->project->bug               = 'Liste Bugs';
$lang->project->dynamic           = 'Historique';
$lang->project->latestDynamic     = 'Historique';
$lang->project->build             = 'Liste Builds';
$lang->project->testtask          = 'Recette';
$lang->project->burn              = ' Atterrissage';
$lang->project->computeBurn       = 'Calculer';
$lang->project->burnData          = "Données d'atterrissage";
$lang->project->fixFirst          = 'Fixer 1er-Jour Estimation';
$lang->project->team              = 'Membres';
$lang->project->doc               = 'Documents';
$lang->project->doclib            = 'Répertoire de Documents';
$lang->project->manageProducts    = 'Liaisons du ' . $lang->projectCommon . ' avec les ' . $lang->productCommon . 's';
$lang->project->linkStory         = 'Stories liées';
$lang->project->linkStoryByPlan   = 'Stories liées par Plan';
$lang->project->linkPlan          = 'Plans liés';
$lang->project->unlinkStoryTasks  = 'Dissocier';
$lang->project->linkedProducts    = "{$lang->productCommon}s liés à ce {$lang->projectCommon}";
$lang->project->unlinkedProducts  = "{$lang->productCommon}s dissociés de ce {$lang->projectCommon}";
$lang->project->view              = "Détail du {$lang->projectCommon}";
$lang->project->startAction       = "Commencer le {$lang->projectCommon}";
$lang->project->activateAction    = "Activer le {$lang->projectCommon}";
$lang->project->delayAction       = "Ajourner le {$lang->projectCommon}";
$lang->project->suspendAction     = "Suspendre le {$lang->projectCommon}";
$lang->project->closeAction       = "Fermer le {$lang->projectCommon}";
$lang->project->testtaskAction    = "Recettes du {$lang->projectCommon}";
$lang->project->teamAction        = "Membres du {$lang->projectCommon}";
$lang->project->kanbanAction      = "Kaban {$lang->projectCommon}";
$lang->project->printKanbanAction = "Imprimer le Kanban";
$lang->project->treeAction        = "Arborescence {$lang->projectCommon}";
$lang->project->exportAction      = "Exporter {$lang->projectCommon}";
$lang->project->computeBurnAction = "Calculer Atterrissage";
$lang->project->create            = "Créer {$lang->projectCommon}";
$lang->project->copy              = "Copier {$lang->projectCommon}";
$lang->project->delete            = "Supprimer {$lang->projectCommon}";
$lang->project->browse            = "Liste du {$lang->projectCommon}";
$lang->project->edit              = "Editer {$lang->projectCommon}";
$lang->project->batchEdit         = "Edition par lot";
$lang->project->manageMembers     = 'Organiser Equipe';
$lang->project->unlinkMember      = 'Retirer le membre';
$lang->project->unlinkStory       = 'Dissocier Story';
$lang->project->unlinkStoryAB     = 'Dissocier';
$lang->project->batchUnlinkStory  = 'Dissocier Stories par lot';
$lang->project->importTask        = 'Transfert Tâche';
$lang->project->importPlanStories = 'Lier Stories Par Plan';
$lang->project->importBug         = 'Importer Bug';
$lang->project->updateOrder       = "Rang {$lang->projectCommon}";
$lang->project->tree              = 'Arboressence';
$lang->project->treeTask          = 'Seulement les Tâches';
$lang->project->treeStory         = 'Seulement les Stories';
$lang->project->treeOnlyTask      = 'Seulement les Tâches';
$lang->project->treeOnlyStory     = 'Seulement les Stories';
$lang->project->storyKanban       = 'Story Kanban';
$lang->project->storySort         = 'Rang Story';
$lang->project->importPlanStory   = $lang->projectCommon . ' est créé!\nVoulez-vous importer des stories qui ont été ajoutées au Plan ?';
$lang->project->iteration         = 'Itérations';
$lang->project->iterationInfo     = '%s Itérations';
$lang->project->viewAll           = 'Voir Tout';

/* 分组浏览。*/
$lang->project->allTasks     = 'Voir Toutes';
$lang->project->assignedToMe = 'à Moi';
$lang->project->myInvolved   = "Où j'ai participé";

$lang->project->statusSelects['']             = 'Plus...';
$lang->project->statusSelects['wait']         = 'En Attente';
$lang->project->statusSelects['doing']        = 'En Cours';
$lang->project->statusSelects['undone']       = 'Non terminées';
$lang->project->statusSelects['finishedbyme'] = 'Terminées par moi';
$lang->project->statusSelects['done']         = 'Faites';
$lang->project->statusSelects['closed']       = 'Fermées';
$lang->project->statusSelects['cancel']       = 'Annulées';

$lang->project->groups['']           = 'Vision groupée';
$lang->project->groups['story']      = 'Grouper par Story';
$lang->project->groups['status']     = 'Grouper par Statut';
$lang->project->groups['pri']        = 'Grouper par Priorité';
$lang->project->groups['assignedTo'] = 'Grouper par Assignation';
$lang->project->groups['finishedBy'] = 'Grouper par Finisseur';
$lang->project->groups['closedBy']   = 'Grouper par Clôtureur';
$lang->project->groups['type']       = 'Grouper par Type';

$lang->project->groupFilter['story']['all']         = 'Toutes';
$lang->project->groupFilter['story']['linked']      = 'Tâches lies à des stories';
$lang->project->groupFilter['pri']['all']           = 'Toutes';
$lang->project->groupFilter['pri']['noset']         = 'Non Spécifiée';
$lang->project->groupFilter['assignedTo']['undone'] = 'Non Terminées';
$lang->project->groupFilter['assignedTo']['all']    = 'Toutes';

$lang->project->byQuery = 'Recherche';

/* 查询条件列表。*/
$lang->project->allProject      = "Tous les {$lang->projectCommon}s";
$lang->project->aboveAllProduct = "Tous les {$lang->productCommon}s dépendants";
$lang->project->aboveAllProject = "Tous les {$lang->projectCommon}s dépendants";

/* 页面提示。*/
$lang->project->linkStoryByPlanTips = "Cette action va lier toutes les stories incluses dans le plan à ce {$lang->projectCommon}.";
$lang->project->selectProject       = "Sélectionner {$lang->projectCommon}";
$lang->project->beginAndEnd         = 'Durée';
$lang->project->begin               = 'Début';
$lang->project->end                 = 'Fin';
$lang->project->lblStats            = 'Efforts';
$lang->project->stats               = 'Disponible: <strong>%s</strong>(h). Estimé: <strong>%s</strong>(h). Coût: <strong>%s</strong>(h). Reste: <strong>%s</strong>(h).';
$lang->project->taskSummary         = "Total des tâches de cette page :<strong>%s</strong>. A Faire: <strong>%s</strong>. En cours: <strong>%s</strong>. &nbsp;&nbsp;&nbsp; Estimé: <strong>%s</strong>(h). Coût: <strong>%s</strong>(h). Reste: <strong>%s</strong>(h).";
$lang->project->pageSummary         = "Total des tâches de cette page: <strong>%total%</strong>. A Faire: <strong>%wait%</strong>. En cours: <strong>%doing%</strong>. &nbsp;&nbsp;&nbsp; Estimé: <strong>%estimate%</strong>(h). Coût: <strong>%consumed%</strong>(h). Reste: <strong>%left%</strong>(h).";
$lang->project->checkedSummary      = "Sélectionné: <strong>%total%</strong>. A Faire: <strong>%wait%</strong>. En cours: <strong>%doing%</strong>. &nbsp;&nbsp;&nbsp; Estimé: <strong>%estimate%</strong>(h). Coût: <strong>%consumed%</strong>(h). Reste: <strong>%left%</strong>(h).";
$lang->project->memberHoursAB       = "%s a <strong>%s</ strong> heures.";
$lang->project->memberHours         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">%s Heures Disponibles</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->countSummary        = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Tâches</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">En Cours</div><div class="segment-value"><span class="label label-dot label-primary"></span> %s</div></div><div class="segment"><div class="segment-title">A Faire</div><div class="segment-value"><span class="label label-dot label-primary muted"></span> %s</div></div></div></div>';
$lang->project->timeSummary         = '<div class="table-col"><div class="clearfix segments"><div class="segment"><div class="segment-title">Estimé</div><div class="segment-value">%s</div></div><div class="segment"><div class="segment-title">Coût</div><div class="segment-value text-red">%s</div></div><div class="segment"><div class="segment-title">Reste</div><div class="segment-value">%s</div></div></div></div>';
$lang->project->groupSummaryAB      = "<div>Tâches <strong>%s ：</strong><span class='text-muted'>A Faire</span> %s &nbsp; <span class='text-muted'>En Cours</span> %s</div><div>Estimé <strong>%s ：</strong><span class='text-muted'>Coût</span> %s &nbsp; <span class='text-muted'>Reste</span> %s</div>";
$lang->project->wbs                 = "Créer Tâche";
$lang->project->batchWBS            = "Créer Tâche en lot";
$lang->project->howToUpdateBurn     = "<a href='https://api.zentao.pm/goto.php?item=burndown' target='_blank' title='Comment mettre à jour le Graphe d´atterrissage ?' class='btn btn-link'>Mise à jour <i class='icon icon-help'></i></a>";
$lang->project->whyNoStories        = "Aucune story ne peut être associée. Vérifiez s'il existe des stories dans {$lang->projectCommon} qui sont associées à {$lang->productCommon} et vérifiez qu'elles ont bien été validées.";
$lang->project->productStories      = "Les stories associées au {$lang->projectCommon} sont une portion des stories associées au {$lang->productCommon}. Les stories ne peuvent être associées à un {$lang->projectCommon} qu'après avoir été validées. <a href='%s'> Associer Stories</a> maintenant.";
$lang->project->haveDraft           = "%s stories sont encore en conception, elles ne peuvent pas être associées au {$lang->projectCommon} actuellement.";
$lang->project->doneProjects        = 'Terminé';
$lang->project->selectDept          = 'Sélection Compartiment';
$lang->project->selectDeptTitle     = 'Sélection Utilisateur';
$lang->project->copyTeam            = 'Copier Equipe';
$lang->project->copyFromTeam        = "Copié de l'Equipe {$lang->projectCommon} : <strong>%s</strong>";
$lang->project->noMatched           = "Aucun $lang->projectCommon inclus '%s' ne peut être trouvé.";
$lang->project->copyTitle           = "Choisissez un {$lang->projectCommon} à copier.";
$lang->project->copyTeamTitle       = "Choisissez une Equipe {$lang->projectCommon} à copier.";
$lang->project->copyNoProject       = "Aucun {$lang->projectCommon} ne peut être copié.";
$lang->project->copyFromProject     = "Copié du {$lang->projectCommon} <strong>%s</strong>";
$lang->project->cancelCopy          = 'Annuler la copie';
$lang->project->byPeriod            = 'Par Temps';
$lang->project->byUser              = 'Par Utilisateur';
$lang->project->noProject           = "Aucun {$lang->projectCommon}. ";
$lang->project->noMembers           = "Actuellement il n'y a aucun membre dans l'équipe. On ne va pas aller loin... ";

/* 交互提示。*/
$lang->project->confirmDelete         = "Voulez-vous réellement supprimer le {$lang->projectCommon}[%s] ?";
$lang->project->confirmUnlinkMember   = "Voulez-vous retirer cet utilisateur du {$lang->projectCommon} ?";
$lang->project->confirmUnlinkStory    = "Voulez-vous retirer cette Story du {$lang->projectCommon} ?";
$lang->project->errorNoLinkedProducts = "Aucun {$lang->productCommon} n'est associé à ce {$lang->projectCommon}. Vous allez être redirigé vers la page {$lang->productCommon} pour en associer un.";
$lang->project->errorSameProducts     = "Ce {$lang->projectCommon} ne peut pas être associé deux fois au même {$lang->productCommon}. Imaginez un peu les résultats !";
$lang->project->accessDenied          = "Votre accès au {$lang->projectCommon} est refusé ! Désolé.";
$lang->project->tips                  = 'Note';
$lang->project->afterInfo             = "Le {$lang->projectCommon} a été créé avec succès ! Ensuite vous pouvez ";
$lang->project->setTeam               = "Composer l'Equipe";
$lang->project->linkStory             = 'Associer Story';
$lang->project->createTask            = 'Créer des Tâches';
$lang->project->goback                = "Revenir en arrière";
$lang->project->noweekend             = 'Exclure les Weekends';
$lang->project->withweekend           = 'Inclure les Weekends';
$lang->project->interval              = 'Intervalles';
$lang->project->fixFirstWithLeft      = 'Mettre à jour les heures également';

$lang->project->action = new stdclass();
$lang->project->action->opened  = '$date, créée par <strong>$actor</strong> .' . "\n";
$lang->project->action->managed = '$date, gérée par <strong>$actor</strong> .' . "\n";
$lang->project->action->edited  = '$date, edited by <strong>$actor</strong> . $extra' . "\n";
$lang->project->action->extra   = "Les {$lang->productCommon}s associés sont %s.";

/* 统计。*/
$lang->project->charts = new stdclass();
$lang->project->charts->burn = new stdclass();
$lang->project->charts->burn->graph = new stdclass();
$lang->project->charts->burn->graph->caption      = " Graphe d'atterrissage";
$lang->project->charts->burn->graph->xAxisName    = "Date";
$lang->project->charts->burn->graph->yAxisName    = "Heure";
$lang->project->charts->burn->graph->baseFontSize = 12;
$lang->project->charts->burn->graph->formatNumber = 0;
$lang->project->charts->burn->graph->animation    = 0;
$lang->project->charts->burn->graph->rotateNames  = 1;
$lang->project->charts->burn->graph->showValues   = 0;
$lang->project->charts->burn->graph->reference    = 'Idéal';
$lang->project->charts->burn->graph->actuality    = 'Actuel';

$lang->project->placeholder = new stdclass();
$lang->project->placeholder->code      = "Abréviation du nom du {$lang->projectCommon}";
$lang->project->placeholder->totalLeft = "Heures estimées le premier jour du {$lang->projectCommon}.";

$lang->project->selectGroup = new stdclass();
$lang->project->selectGroup->done = '(Fait)';

$lang->project->orderList['order_asc']  = "Story Rang Ascendant";
$lang->project->orderList['order_desc'] = "Story Rang Descendant";
$lang->project->orderList['pri_asc']    = "Story Priorité Ascendante";
$lang->project->orderList['pri_desc']   = "Story Priorité Descendante";
$lang->project->orderList['stage_asc']  = "Story Phase Ascendante";
$lang->project->orderList['stage_desc'] = "Story Phase Descendante";

$lang->project->kanban        = "Kanban";
$lang->project->kanbanSetting = "Paramétrage";
$lang->project->resetKanban   = "Réinitialiser";
$lang->project->printKanban   = "Impression";
$lang->project->bugList       = "Bugs";

$lang->project->kanbanHideCols   = 'Colonnes masquées';
$lang->project->kanbanShowOption = 'Déplier';
$lang->project->kanbanColsColor  = 'Personnalisation Couleurs';

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

$lang->project->featureBar['task']['all']          = $lang->project->allTasks;
$lang->project->featureBar['task']['unclosed']     = $lang->project->unclosed;
$lang->project->featureBar['task']['assignedtome'] = $lang->project->assignedToMe;
$lang->project->featureBar['task']['myinvolved']   = $lang->project->myInvolved;
$lang->project->featureBar['task']['delayed']      = 'Ajournées';
$lang->project->featureBar['task']['needconfirm']  = 'A confirmer';
$lang->project->featureBar['task']['status']       = $lang->project->statusSelects[''];

$lang->project->featureBar['all']['all']       = $lang->project->all;
$lang->project->featureBar['all']['undone']    = $lang->project->undone;
$lang->project->featureBar['all']['wait']      = $lang->project->statusList['wait'];
$lang->project->featureBar['all']['doing']     = $lang->project->statusList['doing'];
$lang->project->featureBar['all']['suspended'] = $lang->project->statusList['suspended'];
$lang->project->featureBar['all']['closed']    = $lang->project->statusList['closed'];

$lang->project->treeLevel = array();
$lang->project->treeLevel['all']   = 'Déplier Tout';
$lang->project->treeLevel['root']  = 'Masquer Tout';
$lang->project->treeLevel['task']  = 'Stories&Tâches';
$lang->project->treeLevel['story'] = 'Seulement Stories';

global $config;
if($config->global->flow == 'onlyTask')
{
    unset($lang->project->groups['story']);
    unset($lang->project->featureBar['task']['needconfirm']);
}
