<?php
/**
 * The testtask module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testtask
 * @version     $Id: en.php 4490 2013-02-27 03:27:05Z wyd621@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->testtask->index            = "Accueil Recette";
$lang->testtask->create           = "Initier Campagne";
$lang->testtask->reportChart      = 'Rapport';
$lang->testtask->reportAction     = 'Rapport CasTests';
$lang->testtask->delete           = "Supprimer Campagne";
$lang->testtask->importUnitResult = "Import T.U. Résult";
$lang->testtask->importunitresult = "Import T.U. Résult"; //Fix bug custom required testtask.
$lang->testtask->browseUnits      = "Liste des tests unitaires";
$lang->testtask->unitCases        = "Cas de test unitaires";
$lang->testtask->view             = "Détail Campagne";
$lang->testtask->edit             = "Editer Campagne";
$lang->testtask->browse           = "Tester Campagne";
$lang->testtask->linkCase         = "Inclure CasTests";
$lang->testtask->selectVersion    = "Sélectioner Version";
$lang->testtask->unlinkCase       = "Exclure";
$lang->testtask->batchUnlinkCases = "Exclure CasTests par Lot";
$lang->testtask->batchAssign      = "Affecter par Lot";
$lang->testtask->runCase          = "Jouer";
$lang->testtask->batchRun         = "Jouer par Lot";
$lang->testtask->results          = "Résultats";
$lang->testtask->resultsAction    = "Résultats CasTest";
$lang->testtask->createBug        = "Bug(+)";
$lang->testtask->assign           = 'Affecter';
$lang->testtask->cases            = 'Liste CasTests';
$lang->testtask->groupCase        = "Revu par Groupe";
$lang->testtask->pre              = 'Préc.';
$lang->testtask->next             = 'Suiv';
$lang->testtask->start            = "Commencer";
$lang->testtask->startAction      = "Démarrer Campagne";
$lang->testtask->close            = "Clôturer";
$lang->testtask->closeAction      = "Clôturer Campagne";
$lang->testtask->wait             = "En Attente";
$lang->testtask->block            = "Bloquer";
$lang->testtask->blockAction      = "Bloquer Campagne";
$lang->testtask->activate         = "Activer";
$lang->testtask->activateAction   = "Activer Campagne";
$lang->testtask->testing          = "En Déroulement";
$lang->testtask->blocked          = "Bloquée";
$lang->testtask->done             = "Jouée";
$lang->testtask->totalStatus      = "Toutes";
$lang->testtask->all              = "Tous " . $lang->productCommon . "s";  
$lang->testtask->allTasks         = 'Toutes Recettes';
$lang->testtask->collapseAll      = 'Replier';
$lang->testtask->expandAll        = 'Déplier';

$lang->testtask->id             = 'ID';
$lang->testtask->common         = 'Recette';
$lang->testtask->product        = $lang->productCommon;
$lang->testtask->project        = $lang->projectCommon;
$lang->testtask->build          = 'Build';
$lang->testtask->owner          = 'Owner';
$lang->testtask->executor       = 'Executeur';
$lang->testtask->execTime       = 'Durée Exec';
$lang->testtask->pri            = 'Priorité';
$lang->testtask->name           = 'Nom Campagne';
$lang->testtask->begin          = 'Début';
$lang->testtask->end            = 'Fin';
$lang->testtask->desc           = 'Description';
$lang->testtask->mailto         = 'Mailto';
$lang->testtask->status         = 'Statut';
$lang->testtask->subStatus      = 'Sous-statut';
$lang->testtask->assignedTo     = 'Affecté';
$lang->testtask->linkVersion    = 'Build';
$lang->testtask->lastRunAccount = 'Jouée par';
$lang->testtask->lastRunTime    = 'Dernier Run';
$lang->testtask->lastRunResult  = 'Résultat';
$lang->testtask->reportField    = 'Rapport';
$lang->testtask->files          = 'Upload';
$lang->testtask->case           = 'Liste CasTests';
$lang->testtask->version        = 'Version';
$lang->testtask->caseResult     = 'Résultat Test';
$lang->testtask->stepResults    = 'Etape Résultat';
$lang->testtask->lastRunner     = 'Joué par';
$lang->testtask->lastRunDate    = 'Dernier Run';
$lang->testtask->date           = 'Testé sur';;
$lang->testtask->deleted        = "Supprimé";
$lang->testtask->resultFile     = "Fichier Résultats";
$lang->testtask->caseCount      = 'Compteur CasTest';
$lang->testtask->passCount      = 'Pass';
$lang->testtask->failCount      = 'Fail';
$lang->testtask->summary        = '%s CasTest, %s échecs, %s heures.';

$lang->testtask->beginAndEnd    = 'Durée';
$lang->testtask->to             = 'à';

$lang->testtask->legendDesc      = 'Description';
$lang->testtask->legendReport    = 'Rapport';
$lang->testtask->legendBasicInfo = 'Infos de Base';

$lang->testtask->statusList['wait']    = 'En Attente';
$lang->testtask->statusList['doing']   = 'En Déroulement';
$lang->testtask->statusList['done']    = 'Jouée';
$lang->testtask->statusList['blocked'] = 'Bloquée';

$lang->testtask->priList[0] = '';
$lang->testtask->priList[3] = '3';
$lang->testtask->priList[1] = '1';
$lang->testtask->priList[2] = '2';
$lang->testtask->priList[4] = '4';

$lang->testtask->unlinkedCases = 'CasTests Non rattachés';
$lang->testtask->linkByBuild   = 'Copier depuis build';
$lang->testtask->linkByStory   = 'Lier par Story';
$lang->testtask->linkByBug     = 'Lier par Bug';
$lang->testtask->linkBySuite   = 'Lier par Cahier Recette';
$lang->testtask->passAll       = 'Tout Réussir';
$lang->testtask->pass          = 'Réussite';
$lang->testtask->fail          = 'Echec';
$lang->testtask->showResult    = 'Jouée <span class="text-info">%s</span> fois';
$lang->testtask->showFail      = 'Echouée <span class="text-danger">%s</span> fois';

$lang->testtask->confirmDelete     = 'Voulez-vous supprimer ce build ?';
$lang->testtask->confirmUnlinkCase = 'Voulez-vous détacher ce CasTest ?';
$lang->testtask->noticeNoOther     = "Il n'y a pas de Campagnes de Recette pour ce product.";
$lang->testtask->noTesttask        = 'Pas de campagne. ';
$lang->testtask->checkLinked       = "Vérifiez si le product auquel la campagne de recette est associée est bien lié à un projet.";
$lang->testtask->noImportData      = "Le XML importé ne parse pas les données.";
$lang->testtask->unitXMLFormat     = 'Veuillez sélectionner un fichier au format XML JUnit.';
$lang->testtask->titleOfAuto       = "%s tests automatisés";

$lang->testtask->assignedToMe  = 'Affecté à Moi';
$lang->testtask->allCases      = 'Tous les CasTests';

$lang->testtask->lblCases      = 'CasTest';
$lang->testtask->lblUnlinkCase = 'Exclure CasTest';
$lang->testtask->lblRunCase    = 'Jouer le CasTest';
$lang->testtask->lblResults    = 'Résultats';

$lang->testtask->placeholder = new stdclass();
$lang->testtask->placeholder->begin = 'Début';
$lang->testtask->placeholder->end   = 'Fin';

$lang->testtask->mail = new stdclass();
$lang->testtask->mail->create = new stdclass();
$lang->testtask->mail->edit   = new stdclass();
$lang->testtask->mail->close  = new stdclass();
$lang->testtask->mail->create->title = "%s a créé campagne de recette #%s:%s";
$lang->testtask->mail->edit->title   = "%s a modifié campagne de recette #%s:%s";
$lang->testtask->mail->close->title  = "%s a clôturé campagne de recette #%s:%s";

$lang->testtask->action = new stdclass();
$lang->testtask->action->testtaskopened  = '$date,  <strong>$actor</strong> a soumis campagne de recette <strong>$extra</strong>.' . "\n";
$lang->testtask->action->testtaskstarted = '$date,  <strong>$actor</strong> a démarré campagne de recette <strong>$extra</strong>.' . "\n";
$lang->testtask->action->testtaskclosed  = '$date,  <strong>$actor</strong> a terminé campagne de recette <strong>$extra</strong>.' . "\n";

$lang->testtask->unexecuted = 'En Attente';

/* 统计报表。*/
$lang->testtask->report = new stdclass();
$lang->testtask->report->common = 'Rapport';
$lang->testtask->report->select = 'Sélect Type de Rapport';
$lang->testtask->report->create = 'Créer Rapport';

$lang->testtask->report->charts['testTaskPerRunResult'] = 'Résultat des CasTests';
$lang->testtask->report->charts['testTaskPerType']      = 'Type de CasTests';
$lang->testtask->report->charts['testTaskPerModule']    = 'Module CasTests';
$lang->testtask->report->charts['testTaskPerRunner']    = 'CasTests joués par';
$lang->testtask->report->charts['bugSeverityGroups']    = 'Distribution Bug Sévérité';
$lang->testtask->report->charts['bugStatusGroups']      = 'Distribution Bug Statuts';
$lang->testtask->report->charts['bugOpenedByGroups']    = 'Distribution signalement de Bug';
$lang->testtask->report->charts['bugResolvedByGroups']  = 'Distribution Résolus par';
$lang->testtask->report->charts['bugResolutionGroups']  = 'Distribution Résolution';
$lang->testtask->report->charts['bugModuleGroups']      = 'Distribution Bug Module';

$lang->testtask->report->options = new stdclass();
$lang->testtask->report->options->graph  = new stdclass();
$lang->testtask->report->options->type   = 'pie';
$lang->testtask->report->options->width  = 500;
$lang->testtask->report->options->height = 140;

$lang->testtask->featureBar['browse']['totalStatus'] = $lang->testtask->totalStatus;
$lang->testtask->featureBar['browse']['wait']        = $lang->testtask->wait;
$lang->testtask->featureBar['browse']['doing']       = $lang->testtask->testing;
$lang->testtask->featureBar['browse']['blocked']     = $lang->testtask->blocked;
$lang->testtask->featureBar['browse']['done']        = $lang->testtask->done;
