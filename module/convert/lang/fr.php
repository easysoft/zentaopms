<?php
/**
 * The convert module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.pm
 */
$lang->convert->common  = 'Importé';
$lang->convert->index   = 'Accueil';

$lang->convert->start   = 'Commencer';
$lang->convert->desc    = <<<EOT
<p>Bienvenue dans l'Assistant de Conversion, ce programme va vous assister pour convertir des données vers ZenTao.</p>
<strong>La conversion comporte des risques, il est donc fortement recommandé d'effectuer une sauvegarde de votre base de données et des fichiers importants avant d'effectuer la conversion, et de vous assurer que plus personne n'utilise le système.</strong>
EOT;

$lang->convert->setConfig      = 'Source Config';
$lang->convert->setBugfree     = 'Bugfree Config';
$lang->convert->setRedmine     = 'Redmine Config';
$lang->convert->checkBugFree   = 'Check Bugfree';
$lang->convert->checkRedmine   = 'Check Redmine';
$lang->convert->convertRedmine = 'Convert Redmine';
$lang->convert->convertBugFree = 'Convert BugFree';

$lang->convert->selectSource     = 'Sélectionnez le système source et sa version';
$lang->convert->mustSelectSource = "Vous devez sélectionner un système source.";

$lang->convert->direction             = "{$lang->projectCommon} convertis vers";
$lang->convert->questionTypeOfRedmine = 'Type dans Redmine';
$lang->convert->aimTypeOfZentao       = 'Convertir vers Type dans ZenTao';

$lang->convert->directionList['bug']   = 'Bug';
$lang->convert->directionList['task']  = 'Task';
$lang->convert->directionList['story'] = 'Story';

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');
$lang->convert->sourceList['Redmine'] = array('Redmine_1.1' => '1.1');

$lang->convert->setting     = 'Paramètres';
$lang->convert->checkConfig = 'Vérifier les paramètres';

$lang->convert->ok          = '<span class="text-success"><i class="icon-check-sign"></i> OK </span>';
$lang->convert->fail        = '<span class="text-danger"><i class="icon-remove-sign"></i> Failed</span>';

$lang->convert->dbHost      = 'Database Server';
$lang->convert->dbPort      = 'Server Port';
$lang->convert->dbUser      = 'Database User Name';
$lang->convert->dbPassword  = 'Database Password';
$lang->convert->dbName      = 'Database utilisée dans %s';
$lang->convert->dbCharset   = '%s Database Coding';
$lang->convert->dbPrefix    = '%s Table Prefix';
$lang->convert->installPath = '%s Installation Root Directory';

$lang->convert->checkDB    = 'Database';
$lang->convert->checkTable = 'Table';
$lang->convert->checkPath  = 'Installation Path';

$lang->convert->execute    = 'Convertir';
$lang->convert->item       = 'Item Convertis';
$lang->convert->count      = 'Non.';
$lang->convert->info       = 'Info';

$lang->convert->bugfree = new stdclass();
$lang->convert->bugfree->users    = 'User';
$lang->convert->bugfree->projects = $lang->projectCommon;
$lang->convert->bugfree->modules  = 'Module';
$lang->convert->bugfree->bugs     = 'Bug';
$lang->convert->bugfree->cases    = 'CasTests';
$lang->convert->bugfree->results  = 'Résultats';
$lang->convert->bugfree->actions  = 'Historique';
$lang->convert->bugfree->files    = 'Fichiers';

$lang->convert->redmine = new stdclass();
$lang->convert->redmine->users        = 'User';
$lang->convert->redmine->groups       = 'Groupe';
$lang->convert->redmine->products     = $lang->productCommon;
$lang->convert->redmine->projects     = $lang->projectCommon;
$lang->convert->redmine->stories      = 'Story';
$lang->convert->redmine->tasks        = 'Task';
$lang->convert->redmine->bugs         = 'Bug';
$lang->convert->redmine->productPlans = $lang->productCommon . 'Plan';
$lang->convert->redmine->teams        = 'Team';
$lang->convert->redmine->releases     = 'Release';
$lang->convert->redmine->builds       = 'Build';
$lang->convert->redmine->docLibs      = 'Doc Lib';
$lang->convert->redmine->docs         = 'Doc';
$lang->convert->redmine->files        = 'Fichiers';

$lang->convert->errorFileNotExits  = 'Fichier %s non trouvé.';
$lang->convert->errorUserExists    = 'Utilisation %s existant.';
$lang->convert->errorGroupExists   = 'Groupe %s existant.';
$lang->convert->errorBuildExists   = 'Build %s existant.';
$lang->convert->errorReleaseExists = 'Release %s existante.';
$lang->convert->errorCopyFailed    = 'Echec de la copie du fichier %s.';

$lang->convert->setParam = 'Fixer les paramètres.';

$lang->convert->statusType = new stdclass();
$lang->convert->priType    = new stdclass();

$lang->convert->aimType           = 'Problème de conversion';
$lang->convert->statusType->bug   = 'Convert Statut (Bug Statut)';
$lang->convert->statusType->story = 'Convert Statut (Story Statut)';
$lang->convert->statusType->task  = 'Convert Statut (Task Statut)';
$lang->convert->priType->bug      = 'Convert Priorité (Bug Statut)';
$lang->convert->priType->story    = 'Convert Priorité (Story Statut)';
$lang->convert->priType->task     = 'Convert Priorité (Task Statut)';

$lang->convert->issue = new stdclass();
$lang->convert->issue->redmine = 'Redmine';
$lang->convert->issue->zentao  = 'ZenTao';
$lang->convert->issue->goto    = 'Convertir vers';
