<?php
/**
 * The convert module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->convert->common  = 'Importiert';
$lang->convert->index   = 'Home';

$lang->convert->start   = 'Start';
$lang->convert->desc    = <<<EOT
<p>Willkommen beim System Konvertierungs Assistenten. Dieses Programm hilft Ihnen dabei Daten für ZenTao PMS zu konvertieren.</p>
<strong>Es besteht ein gewisses Risiko bei Konvertieren von Daten, daher wird dringend empfohlen eine Backup der Datenbank und der relevanten Dateien zu erstellen. Des Weiteren sollte Sie darauf achten, dass niemand im System arbeitet.</strong>
EOT;

$lang->convert->setConfig      = 'Source Konfiguration';
$lang->convert->setBugfree     = 'Bugfree Konfiguration';
$lang->convert->setRedmine     = 'Redmine Konfiguration';
$lang->convert->checkBugFree   = 'Prüfe Bugfree';
$lang->convert->checkRedmine   = 'Prüfe Redmine';
$lang->convert->convertRedmine = 'Konvertiere Redmine';
$lang->convert->convertBugFree = 'Konvertiere BugFree';

$lang->convert->selectSource     = 'Quellsystem und Version wählen';
$lang->convert->mustSelectSource = "Sie müssen eine Quellsystem wählen.";

$lang->convert->direction             = "{$lang->projectCommon} konvertieren nach";
$lang->convert->questionTypeOfRedmine = 'Typ in Redmine';
$lang->convert->aimTypeOfZentao       = 'Konvertieren nach Typ in Zentao';

$lang->convert->directionList['bug']   = 'Bug';
$lang->convert->directionList['task']  = 'Aufgabe';
$lang->convert->directionList['story'] = 'Story';

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');
$lang->convert->sourceList['Redmine'] = array('Redmine_1.1' => '1.1');

$lang->convert->setting     = 'Einstellungen';
$lang->convert->checkConfig = 'Prüfe Einstellungen';

$lang->convert->ok          = '<span class="text-success"><i class="icon-check-sign"></i> OK </span>';
$lang->convert->fail        = '<span class="text-danger"><i class="icon-remove-sign"></i> Fehlgeschlagen</span>';

$lang->convert->dbHost      = 'Datenbank Server';
$lang->convert->dbPort      = 'Server Port';
$lang->convert->dbUser      = 'Datenbank User Name';
$lang->convert->dbPassword  = 'Datenbank Passwort';
$lang->convert->dbName      = 'Datenbank benutzt in %s';
$lang->convert->dbCharset   = '%s Datenbank Code';
$lang->convert->dbPrefix    = '%s Tabellen Prefix';
$lang->convert->installPath = '%s Installationspfad';

$lang->convert->checkDB    = 'Datenbank';
$lang->convert->checkTable = 'Tabelle';
$lang->convert->checkPath  = 'Installationspfad';

$lang->convert->execute    = 'Konvertieren';
$lang->convert->item       = 'Objekt konvertiert';
$lang->convert->count      = 'Zähler';
$lang->convert->info       = 'Info';

$lang->convert->bugfree = new stdclass();
$lang->convert->bugfree->users    = 'Benutzer';
$lang->convert->bugfree->projects = $lang->projectCommon;
$lang->convert->bugfree->modules  = 'Modul';
$lang->convert->bugfree->bugs     = 'Bug';
$lang->convert->bugfree->cases    = 'Testfälle';
$lang->convert->bugfree->results  = 'Ergebnis';
$lang->convert->bugfree->actions  = 'Verlauf';
$lang->convert->bugfree->files    = 'Datei';

$lang->convert->redmine = new stdclass();
$lang->convert->redmine->users        = 'Benutzer';
$lang->convert->redmine->groups       = 'Gruppe';
$lang->convert->redmine->products     = $lang->productCommon;
$lang->convert->redmine->projects     = $lang->projectCommon;
$lang->convert->redmine->stories      = 'Story';
$lang->convert->redmine->tasks        = 'Aufgabe';
$lang->convert->redmine->bugs         = 'Bug';
$lang->convert->redmine->productPlans = $lang->productCommon . 'Plan';
$lang->convert->redmine->teams        = 'Team';
$lang->convert->redmine->releases     = 'Release';
$lang->convert->redmine->builds       = 'Build';
$lang->convert->redmine->docLibs      = 'Dok Bibliothek';
$lang->convert->redmine->docs         = 'Dok';
$lang->convert->redmine->files        = 'Datei';

$lang->convert->errorFileNotExits  = 'Datei %s nicht gefunden.';
$lang->convert->errorUserExists    = 'Benutzer %s existiert.';
$lang->convert->errorGroupExists   = 'Gruppe %s existiert.';
$lang->convert->errorBuildExists   = 'Build %s existiert.';
$lang->convert->errorReleaseExists = 'Release %s existiert.';
$lang->convert->errorCopyFailed    = 'Datei %s Kopiervorgang fehlgeschlagen.';

$lang->convert->setParam = 'Bitte setzen Sie die Parameter.';

$lang->convert->statusType = new stdclass();
$lang->convert->priType    = new stdclass();

$lang->convert->aimType           = 'Konvertiere Probleme';
$lang->convert->statusType->bug   = 'Konvertiere Status (Bug Status)';
$lang->convert->statusType->story = 'Konvertiere Status (Story Status)';
$lang->convert->statusType->task  = 'Konvertiere Status (Aufgaben Status)';
$lang->convert->priType->bug      = 'Konvertiere Priorität (Bug Status)';
$lang->convert->priType->story    = 'Konvertiere Priorität (Story Status)';
$lang->convert->priType->task     = 'Konvertiere Priorität (Aufgaben Status)';

$lang->convert->issue = new stdclass();
$lang->convert->issue->redmine = 'Redmine';
$lang->convert->issue->zentao  = 'ZenTao';
$lang->convert->issue->goto    = 'Konvertieren nach';
