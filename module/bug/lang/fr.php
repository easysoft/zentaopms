<?php
/**
 * The bug module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: en.php 4536 2013-03-02 13:39:37Z wwccss $
 * @link        https://www.zentao.net
 */
/* Fieldlist. */
$lang->bug->common           = 'Bug';
$lang->bug->id               = 'ID';
$lang->bug->product          = $lang->productCommon;
$lang->bug->branch           = 'Branche/Plateforme';
$lang->bug->module           = 'Module';
$lang->bug->project          = $lang->projectCommon;
$lang->bug->execution        = $lang->execution->common;
$lang->bug->kanban           = 'Kanban';
$lang->bug->storyVersion     = 'Version Story';
$lang->bug->color            = 'Couleur';
$lang->bug->title            = 'Titre';
$lang->bug->severity         = 'Sévérité';
$lang->bug->pri              = 'Priorité';
$lang->bug->type             = 'Type';
$lang->bug->os               = 'OS';
$lang->bug->browser          = 'Browser';
$lang->bug->hardware         = 'Hardware';
$lang->bug->result           = 'Result';
$lang->bug->repo             = 'Repo';
$lang->bug->mr               = 'Merge Request';
$lang->bug->entry            = 'Code Path';
$lang->bug->lines            = 'Lines';
$lang->bug->v1               = 'Version A';
$lang->bug->v2               = 'Version B';
$lang->bug->issueKey         = 'Sonarqube Issue Key';
$lang->bug->repoType         = 'Repo Type';
$lang->bug->steps            = 'Repro Steps';
$lang->bug->status           = 'Statut';
$lang->bug->subStatus        = 'Sous-Statut';
$lang->bug->activatedCount   = 'Heures Activation';
$lang->bug->activatedDate    = 'Date Activation';
$lang->bug->confirmed        = 'Confirmé';
$lang->bug->toTask           = 'Convertir en Tâche';
$lang->bug->toStory          = 'Convertir en Story';
$lang->bug->feedbackBy       = 'Discovered by';
$lang->bug->notifyEmail      = 'Discoverer Email';
$lang->bug->mailto           = 'Mailto';
$lang->bug->openedBy         = 'Signalé par';
$lang->bug->openedDate       = 'Date signalement';
$lang->bug->openedBuild      = 'Build ouverts';
$lang->bug->assignedTo       = 'Bugs qui me sont affectés';
$lang->bug->assignedToMe     = 'AssignToMe';
$lang->bug->assignedDate     = 'Date affectation';
$lang->bug->resolvedBy       = 'Résolus par moi';
$lang->bug->resolution       = 'Résolution';
$lang->bug->resolvedBuild    = 'Build';
$lang->bug->resolvedDate     = 'Date Correction';
$lang->bug->deadline         = 'Date Butoir';
$lang->bug->plan             = 'Plan';
$lang->bug->closedBy         = 'Fermé par';
$lang->bug->closedDate       = 'Date Fermeture';
$lang->bug->duplicateBug     = 'Bug en doublon';
$lang->bug->lastEditedBy     = 'Edité par';
$lang->bug->caseVersion      = 'Version CasTest';
$lang->bug->testtask         = 'Campagne';
$lang->bug->files            = 'Fichiers';
$lang->bug->keywords         = 'Tags';
$lang->bug->lastEditedDate   = 'Date Modif';
$lang->bug->fromCase         = 'du CasTest';
$lang->bug->toCase           = 'vers CasTest';
$lang->bug->colorTag         = 'Couleur';
$lang->bug->fixedRate        = 'Repair Rate';
$lang->bug->noticefeedbackBy = 'NoticeFeedbackBy';
$lang->bug->selectProjects   = "Select {$lang->projectCommon}s";
$lang->bug->nextStep         = 'Next Step';
$lang->bug->noProject        = "Haven’t chosen a {$lang->projectCommon} yet.";
$lang->bug->noExecution      = 'Haven’t chosen a ' . strtolower($lang->execution->common) . ' yet.';
$lang->bug->story            = 'Story';
$lang->bug->task             = 'Tâche';
$lang->bug->relatedBug       = 'Bugs Liés';
$lang->bug->case             = 'CasTests';
$lang->bug->linkMR           = 'Related MRs';
$lang->bug->linkCommit       = 'Related Commits';
$lang->bug->productplan      = $lang->bug->plan;

$lang->bug->abbr = new stdclass();
$lang->bug->abbr->module         = 'Module';
$lang->bug->abbr->severity       = 'S';
$lang->bug->abbr->status         = 'Statut';
$lang->bug->abbr->activatedCount = 'Actif';
$lang->bug->abbr->confirmed      = 'C';
$lang->bug->abbr->openedBy       = 'Détecteur';
$lang->bug->abbr->openedDate     = 'Signalé';
$lang->bug->abbr->assignedTo     = 'Affecté à';
$lang->bug->abbr->resolvedBy     = 'Résolu par';
$lang->bug->abbr->resolution     = 'Résolution';
$lang->bug->abbr->resolvedDate   = 'Date Correction';
$lang->bug->abbr->deadline       = 'Butoir';
$lang->bug->abbr->lastEditedBy   = 'Edité par';
$lang->bug->abbr->lastEditedDate = 'Date Modif';
$lang->bug->abbr->assignToMe     = 'Affectés à Moi';
$lang->bug->abbr->openedByMe     = 'Signalés par Moi';
$lang->bug->abbr->resolvedByMe   = 'Résolus par Moi';

/* Method list. */
$lang->bug->index              = 'Accueil Bug';
$lang->bug->browse             = 'Liste des Bugs';
$lang->bug->create             = 'Signaler un Bug';
$lang->bug->batchCreate        = 'Signaler par Lot';
$lang->bug->createCase         = 'Create Case';
$lang->bug->copy               = 'Copier';
$lang->bug->edit               = 'Editer Bug';
$lang->bug->batchEdit          = 'Editer par Lot';
$lang->bug->view               = 'Détail Bug';
$lang->bug->delete             = 'Supprimer';
$lang->bug->deleteAction       = 'Supprimer Bug';
$lang->bug->confirm            = 'Confirmer';
$lang->bug->confirmAction      = 'Confirmer Bug';
$lang->bug->batchConfirm       = 'Confirmé par Lot';
$lang->bug->assignTo           = 'Affecter à';
$lang->bug->assignAction       = 'Affecter Bug';
$lang->bug->batchAssignTo      = 'Affecter par Lot';
$lang->bug->resolve            = 'Résoudre';
$lang->bug->resolveAction      = 'Résoudre Bug';
$lang->bug->batchResolve       = 'Résoudre par Lot';
$lang->bug->createAB           = 'Add';
$lang->bug->close              = 'Clôturer';
$lang->bug->closeAction        = 'Clôturer Bug';
$lang->bug->batchClose         = 'Fermer par Lot';
$lang->bug->activate           = 'Activer';
$lang->bug->activateAction     = 'Activer Bug';
$lang->bug->batchActivate      = 'Activer par Lot';
$lang->bug->reportChart        = 'Signalements';
$lang->bug->reportAction       = 'Signalement Bugs';
$lang->bug->export             = 'Exporter Données';
$lang->bug->exportAction       = 'Exporter Bug';
$lang->bug->confirmStoryChange = 'Confirmer Changement Story';
$lang->bug->search             = 'Rechercher';
$lang->bug->batchChangeModule  = 'Batch Edit Modules';
$lang->bug->batchChangeBranch  = 'Batch Edit Branches';
$lang->bug->batchChangePlan    = 'Batch Edit Plans';
$lang->bug->linkBugs           = 'Associer Bug';
$lang->bug->unlinkBug          = 'Dissocier';

/* Query condition list. */
$lang->bug->assignToMe         = 'Affectés à moi';
$lang->bug->openedByMe         = 'Détectés par moi';
$lang->bug->resolvedByMe       = 'Résolus par moi';
$lang->bug->closedByMe         = 'Fermés par moi';
$lang->bug->assignedByMe       = 'AssignedByMe';
$lang->bug->assignToNull       = 'Non affectés';
$lang->bug->unResolved         = 'Actifs';
$lang->bug->toClosed           = 'A Fermer';
$lang->bug->unclosed           = 'Non fermés';
$lang->bug->unconfirmed        = 'Non confirmés';
$lang->bug->longLifeBugs       = 'Persistant';
$lang->bug->postponedBugs      = 'Reporté';
$lang->bug->overdueBugs        = 'Retard';
$lang->bug->allBugs            = 'Tous';
$lang->bug->byQuery            = 'Rechercher';
$lang->bug->needConfirm        = 'Story Changée';
$lang->bug->allProject         = "All {$lang->projectCommon}s";
$lang->bug->allProduct         = 'Tous les ' . $lang->productCommon . 's';
$lang->bug->my                 = 'Mes';
$lang->bug->yesterdayResolved  = 'Bugs Résolus Hier ';
$lang->bug->yesterdayConfirmed = 'Bugs Confirmés Hier ';
$lang->bug->yesterdayClosed    = 'Bugs Fermés Hier ';

$lang->bug->deleted        = 'Supprimé';
$lang->bug->labelConfirmed = 'Confirmed';
$lang->bug->labelPostponed = 'Postponed';
$lang->bug->changed        = 'Changed';
$lang->bug->storyChanged   = 'Story Changed';
$lang->bug->ditto          = 'Idem';

/* Page tags. */
$lang->bug->lblAssignedTo = 'Affecté à';
$lang->bug->lblMailto     = 'Mailto';
$lang->bug->lblLastEdited = 'Edité par';
$lang->bug->lblResolved   = 'Résolu par';
$lang->bug->loadAll       = 'Load All';
$lang->bug->createBuild   = 'Nouveau';

global $config;
/* Legend list. */
$lang->bug->legendBasicInfo             = 'Infos de Base';
$lang->bug->legendAttach                = 'Fichiers';
$lang->bug->legendPRJExecStoryTask      = "{$lang->SRCommon}/{$lang->executionCommon}/Story/Task";
$lang->bug->legendExecStoryTask         = "{$lang->SRCommon}/Story/Task";
$lang->bug->lblTypeAndSeverity          = 'Type/Severité';
$lang->bug->lblSystemBrowserAndHardware = 'Système/Browser';
$lang->bug->legendSteps                 = 'Etapes Repro';
$lang->bug->legendComment               = 'Note';
$lang->bug->legendLife                  = 'Vie du Bug';
$lang->bug->legendMisc                  = 'Divers';
$lang->bug->legendRelated               = 'Infos connexes';
$lang->bug->legendThisWeekCreated       = 'This Week Created';

/* Template. */
$lang->bug->tplStep   = "<p>[Etape]</p><p></p>";
$lang->bug->tplResult = "<p>[Résultats]</p><p></p>";
$lang->bug->tplExpect = "<p>[Résultats attendus]</p><p></p>";

/* Value list for each field. */
$lang->bug->severityList[0] = '';
$lang->bug->severityList[1] = '1';
$lang->bug->severityList[2] = '2';
$lang->bug->severityList[3] = '3';
$lang->bug->severityList[4] = '4';

$lang->bug->priList[0] = '';
$lang->bug->priList[1] = '1';
$lang->bug->priList[2] = '2';
$lang->bug->priList[3] = '3';
$lang->bug->priList[4] = '4';

$lang->bug->osList['']         = '';
$lang->bug->osList['all']      = 'Tous';
$lang->bug->osList['windows']  = 'Windows';
$lang->bug->osList['win11']    = 'Windows 11';
$lang->bug->osList['win10']    = 'Windows 10';
$lang->bug->osList['win8']     = 'Windows 8';
$lang->bug->osList['win7']     = 'Windows 7';
$lang->bug->osList['winxp']    = 'Windows XP';
$lang->bug->osList['osx']      = 'Mac OS';
$lang->bug->osList['android']  = 'Android';
$lang->bug->osList['ios']      = 'IOS';
$lang->bug->osList['linux']    = 'Linux';
$lang->bug->osList['ubuntu']   = 'Ubuntu';
$lang->bug->osList['chromeos'] = 'Chrome OS';
$lang->bug->osList['fedora']   = 'Fedora';
$lang->bug->osList['unix']     = 'Unix';
$lang->bug->osList['others']   = 'Autres';

$lang->bug->browserList['']        = '';
$lang->bug->browserList['all']     = 'Tous';
$lang->bug->browserList['chrome']  = 'Chrome';
$lang->bug->browserList['edge']    = 'Edge';
$lang->bug->browserList['ie']      = 'IE series';
$lang->bug->browserList['ie11']    = 'IE11';
$lang->bug->browserList['ie10']    = 'IE10';
$lang->bug->browserList['ie9']     = 'IE9';
$lang->bug->browserList['ie8']     = 'IE8';
$lang->bug->browserList['firefox'] = 'Firefox series';
$lang->bug->browserList['opera']   = 'Opera series';
$lang->bug->browserList['safari']  = 'Safari';
$lang->bug->browserList['360']     = '360 series';
$lang->bug->browserList['qq']      = 'QQ series';
$lang->bug->browserList['other']   = 'Autres';

$lang->bug->typeList['']             = '';
$lang->bug->typeList['codeerror']    = 'Code Erreur';
$lang->bug->typeList['config']       = 'Configuration';
$lang->bug->typeList['install']      = 'Installation';
$lang->bug->typeList['security']     = 'Faille Sécurité';
$lang->bug->typeList['performance']  = 'Performance';
$lang->bug->typeList['standard']     = 'Convention de Code';
$lang->bug->typeList['automation']   = 'Script automatisation';
$lang->bug->typeList['designdefect'] = 'Erreur de Design';
$lang->bug->typeList['others']       = 'Autres';

$lang->bug->statusList['']         = '';
$lang->bug->statusList['active']   = 'Actif';
$lang->bug->statusList['resolved'] = 'Résolu';
$lang->bug->statusList['closed']   = 'Fermé';

$lang->bug->confirmedList[''] = '';
$lang->bug->confirmedList[1] = 'confirmé';
$lang->bug->confirmedList[0] = 'non confirmé';

$lang->bug->resolutionList['']           = '';
$lang->bug->resolutionList['bydesign']   = 'Du Design';
$lang->bug->resolutionList['duplicate']  = 'Doublon';
$lang->bug->resolutionList['external']   = 'Externe';
$lang->bug->resolutionList['fixed']      = 'Résolu';
$lang->bug->resolutionList['notrepro']   = 'Irreproductible';
$lang->bug->resolutionList['postponed']  = 'Reporté à +tard';
$lang->bug->resolutionList['willnotfix'] = "On ne corrigera pas";
$lang->bug->resolutionList['tostory']    = 'Converti en Story';

/* Statistical statement. */
$lang->bug->report = new stdclass();
$lang->bug->report->common = 'Rapport';
$lang->bug->report->select = 'Sélect Type de Rapport';
$lang->bug->report->create = 'Créer Rapport';

$lang->bug->report->charts['bugsPerExecution']      = 'Bugs ' . $lang->executionCommon;
$lang->bug->report->charts['bugsPerBuild']          = 'Bugs Par Build';
$lang->bug->report->charts['bugsPerModule']         = 'Bugs Par Module';
$lang->bug->report->charts['openedBugsPerDay']      = 'Bugs Détectés Par Jour';
$lang->bug->report->charts['resolvedBugsPerDay']    = 'Bugs Résolus Par Jour';
$lang->bug->report->charts['closedBugsPerDay']      = 'Bugs Fermés Par Jour';
$lang->bug->report->charts['openedBugsPerUser']     = 'Bugs Détectés Par User';
$lang->bug->report->charts['resolvedBugsPerUser']   = 'Bugs Résolus Par User';
$lang->bug->report->charts['closedBugsPerUser']     = 'Bugs Fermés Par User';
$lang->bug->report->charts['bugsPerSeverity']       = 'Sévérité Bug';
$lang->bug->report->charts['bugsPerResolution']     = 'Résulution Bug';
$lang->bug->report->charts['bugsPerStatus']         = 'Statut Bug';
$lang->bug->report->charts['bugsPerActivatedCount'] = 'Heure Activation';
$lang->bug->report->charts['bugsPerPri']            = 'Priorité Bug';
$lang->bug->report->charts['bugsPerType']           = 'Type de Bug';
$lang->bug->report->charts['bugsPerAssignedTo']     = 'Bug Affecté à';
//$lang->bug->report->charts['bugLiveDays']        = 'Rapport sur le temps de travail des bugs';
//$lang->bug->report->charts['bugHistories']       = 'Rapport sur les étapes de gestion des bugs';

$lang->bug->report->options = new stdclass();
$lang->bug->report->options->graph  = new stdclass();
$lang->bug->report->options->type   = 'pie';
$lang->bug->report->options->width  = 500;
$lang->bug->report->options->height = 140;

$lang->bug->report->bugsPerExecution      = new stdclass();
$lang->bug->report->bugsPerBuild          = new stdclass();
$lang->bug->report->bugsPerModule         = new stdclass();
$lang->bug->report->openedBugsPerDay      = new stdclass();
$lang->bug->report->resolvedBugsPerDay    = new stdclass();
$lang->bug->report->closedBugsPerDay      = new stdclass();
$lang->bug->report->openedBugsPerUser     = new stdclass();
$lang->bug->report->resolvedBugsPerUser   = new stdclass();
$lang->bug->report->closedBugsPerUser     = new stdclass();
$lang->bug->report->bugsPerSeverity       = new stdclass();
$lang->bug->report->bugsPerResolution     = new stdclass();
$lang->bug->report->bugsPerStatus         = new stdclass();
$lang->bug->report->bugsPerActivatedCount = new stdclass();
$lang->bug->report->bugsPerType           = new stdclass();
$lang->bug->report->bugsPerPri            = new stdclass();
$lang->bug->report->bugsPerAssignedTo     = new stdclass();
$lang->bug->report->bugLiveDays           = new stdclass();
$lang->bug->report->bugHistories          = new stdclass();

$lang->bug->report->bugsPerExecution->graph      = new stdclass();
$lang->bug->report->bugsPerBuild->graph          = new stdclass();
$lang->bug->report->bugsPerModule->graph         = new stdclass();
$lang->bug->report->openedBugsPerDay->graph      = new stdclass();
$lang->bug->report->resolvedBugsPerDay->graph    = new stdclass();
$lang->bug->report->closedBugsPerDay->graph      = new stdclass();
$lang->bug->report->openedBugsPerUser->graph     = new stdclass();
$lang->bug->report->resolvedBugsPerUser->graph   = new stdclass();
$lang->bug->report->closedBugsPerUser->graph     = new stdclass();
$lang->bug->report->bugsPerSeverity->graph       = new stdclass();
$lang->bug->report->bugsPerResolution->graph     = new stdclass();
$lang->bug->report->bugsPerStatus->graph         = new stdclass();
$lang->bug->report->bugsPerActivatedCount->graph = new stdclass();
$lang->bug->report->bugsPerType->graph           = new stdclass();
$lang->bug->report->bugsPerPri->graph            = new stdclass();
$lang->bug->report->bugsPerAssignedTo->graph     = new stdclass();
$lang->bug->report->bugLiveDays->graph           = new stdclass();
$lang->bug->report->bugHistories->graph          = new stdclass();

$lang->bug->report->bugsPerExecution->graph->xAxisName = $lang->executionCommon;
$lang->bug->report->bugsPerBuild->graph->xAxisName     = 'Build';
$lang->bug->report->bugsPerModule->graph->xAxisName    = 'Module';

$lang->bug->report->openedBugsPerDay->type             = 'bar';
$lang->bug->report->openedBugsPerDay->graph->xAxisName = 'Date';

$lang->bug->report->resolvedBugsPerDay->type             = 'bar';
$lang->bug->report->resolvedBugsPerDay->graph->xAxisName = 'Date';

$lang->bug->report->closedBugsPerDay->type             = 'bar';
$lang->bug->report->closedBugsPerDay->graph->xAxisName = 'Date';

$lang->bug->report->openedBugsPerUser->graph->xAxisName   = 'User';
$lang->bug->report->resolvedBugsPerUser->graph->xAxisName = 'User';
$lang->bug->report->closedBugsPerUser->graph->xAxisName   = 'User';

$lang->bug->report->bugsPerSeverity->graph->xAxisName       = 'Sévérité';
$lang->bug->report->bugsPerResolution->graph->xAxisName     = 'Résolution';
$lang->bug->report->bugsPerStatus->graph->xAxisName         = 'Statut';
$lang->bug->report->bugsPerActivatedCount->graph->xAxisName = 'Heure Activation';
$lang->bug->report->bugsPerPri->graph->xAxisName            = 'Priorité';
$lang->bug->report->bugsPerType->graph->xAxisName           = 'Type';
$lang->bug->report->bugsPerAssignedTo->graph->xAxisName     = 'Affecté à';
$lang->bug->report->bugLiveDays->graph->xAxisName           = 'Temps résolution';
$lang->bug->report->bugHistories->graph->xAxisName          = 'Etapes résolution';

/* Operating record. */
$lang->bug->action = new stdclass();
$lang->bug->action->resolved             = array('main' => '$date, résolu par <strong>$actor</strong> et la résolution est <strong>$extra</strong> $appendLink.', 'extra' => 'resolutionList');
$lang->bug->action->tostory              = array('main' => '$date, converti par <strong>$actor</strong> en <strong>Story</strong> avec ID <strong>$extra</strong>.');
$lang->bug->action->totask               = array('main' => '$date, importé par <strong>$actor</strong> en tant que <strong>Task</strong> avec ID <strong>$extra</strong>.');
$lang->bug->action->converttotask        = array('main' => '$date, imported by <strong>$actor</strong> as <strong>Task</strong>，with ID <strong>$extra</strong>。');
$lang->bug->action->linked2plan          = array('main' => '$date, lié par <strong>$actor</strong> au Plan <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromplan     = array('main' => '$date, supprimé par <strong>$actor</strong> du Plan <strong>$extra</strong>.');
$lang->bug->action->linked2build         = array('main' => '$date, lié par <strong>$actor</strong> au Build <strong>$extra</strong>.');
$lang->bug->action->unlinkedfrombuild    = array('main' => '$date, retiré par <strong>$actor</strong> du Build <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromrelease  = array('main' => '$date, retiré par <strong>$actor</strong> de la Release <strong>$extra</strong>.');
$lang->bug->action->linked2release       = array('main' => '$date, ajouté par <strong>$actor</strong> à la Release <strong>$extra</strong>.');
$lang->bug->action->linked2revision      = array('main' => '$date, linked by <strong>$actor</strong> to Revision <strong>$extra</strong>.');
$lang->bug->action->unlinkedfromrevision = array('main' => '$date, unlinked by <strong>$actor</strong> to Revision <strong>$extra</strong>.');
$lang->bug->action->linkrelatedbug       = array('main' => '$date, associé par <strong>$actor</strong> au Bug <strong>$extra</strong>.');
$lang->bug->action->unlinkrelatedbug     = array('main' => '$date, dissocié par <strong>$actor</strong> du Bug <strong>$extra</strong>.');

$lang->bug->featureBar['browse']['all']          = $lang->bug->allBugs;
$lang->bug->featureBar['browse']['unclosed']     = $lang->bug->unclosed;
$lang->bug->featureBar['browse']['openedbyme']   = $lang->bug->openedByMe;
$lang->bug->featureBar['browse']['assigntome']   = $lang->bug->assignToMe;
$lang->bug->featureBar['browse']['resolvedbyme'] = $lang->bug->resolvedByMe;
$lang->bug->featureBar['browse']['more']         = $lang->more;

$lang->bug->moreSelects['browse']['more']['unresolved']    = $lang->bug->unResolved;
$lang->bug->moreSelects['browse']['more']['assignedbyme']  = $lang->bug->assignedByMe;
$lang->bug->moreSelects['browse']['more']['unconfirmed']   = $lang->bug->unconfirmed;
$lang->bug->moreSelects['browse']['more']['assigntonull']  = $lang->bug->assignToNull;
$lang->bug->moreSelects['browse']['more']['longlifebugs']  = $lang->bug->longLifeBugs;
$lang->bug->moreSelects['browse']['more']['toclosed']      = $lang->bug->toClosed;
$lang->bug->moreSelects['browse']['more']['postponedbugs'] = $lang->bug->postponedBugs;
$lang->bug->moreSelects['browse']['more']['overduebugs']   = $lang->bug->overdueBugs;
$lang->bug->moreSelects['browse']['more']['needconfirm']   = $lang->bug->needConfirm;

$lang->bug->placeholder = new stdclass();
$lang->bug->placeholder->chooseBuilds = 'Sélect Build';
$lang->bug->placeholder->newBuildName = 'Nom Nouv Build';
$lang->bug->placeholder->duplicate    = 'Please enter keyword search';

/* Interactive prompt. */
$lang->bug->notice = new stdclass();
$lang->bug->notice->summary               = "Total de <strong>%s</strong> bugs sur cette page, et <strong>%s</strong> sont Actifs.";
$lang->bug->notice->confirmChangeProduct  = "Tout changement aux {$lang->productCommon} fera que les {$lang->executionCommon}s, stories et tâches associées vont changer. Voulez-vous faire cela ?";
$lang->bug->notice->confirmDelete         = 'Voulez-vous supprimer ce bug ?';
$lang->bug->notice->remindTask            = 'Ce bug a été converti en tâche. Voulez-vous mettre à jour le statut de la Tâche(ID %s) ?';
$lang->bug->notice->skipClose             = 'Les bugs %s ne sont pas résolus et ne peuvent pas être fermés, ils seront donc ignorés automatiquement. ';
$lang->bug->notice->executionAccessDenied = "Votre accès à {$lang->executionCommon} auquel ce bug appartient est refusé !";
$lang->bug->notice->confirmUnlinkBuild    = "Replacing the solution version will disassociate the bug from the old version. Are you sure you want to disassociate the bug from %s?";
$lang->bug->notice->noSwitchBranch        = 'The linked module of Bug%s is not in the current branch. It will be omitted.';
$lang->bug->notice->confirmToStory        = 'The bug will be closed automatically after transferring to requirements, and the reason for closing is that the bug has been converted to requirements status.';
$lang->bug->notice->productDitto          = 'This bug is not linked to the same product as the last one!';
$lang->bug->notice->noBug                 = "Pas de bug pour l'instant. Bravo !";
$lang->bug->notice->noModule              = "<div>Vous n'avez pas de modules.</div><div>Gérer Modules maintenant</div>";
$lang->bug->notice->delayWarning          = " <strong class='text-danger'> Retard %s jours </strong>";

$lang->bug->error = new stdclass();
$lang->bug->error->notExist       = "Bug doesn't exist.";
$lang->bug->error->cannotActivate = 'Bugs with a status other than Resolved or Closed cannot be activated.';
$lang->bug->error->stepsNotEmpty  = "The reproduction step cannot be empty.";
