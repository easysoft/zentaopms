<?php
/**
 * The convert module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        https://www.zentao.net
 */
$lang->convert->common  = 'Importiert';
$lang->convert->index   = 'Home';

$lang->convert->start   = 'Start';
$lang->convert->desc    = <<<EOT
<p>Welcome to the System Conversion Wizard, this program will assist you to convert data to ZenTao.</p>
<strong>There are risks in the conversion, so it is strongly recommended that you back up your databse and relavant files before conversion, and make sure that no one is using either system.</strong>
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

$lang->convert->direction             = "{$lang->executionCommon} konvertieren nach";
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
$lang->convert->bugfree->users      = 'Benutzer';
$lang->convert->bugfree->executions = $lang->executionCommon;
$lang->convert->bugfree->modules    = 'Modul';
$lang->convert->bugfree->bugs       = 'Bug';
$lang->convert->bugfree->cases      = 'Testfälle';
$lang->convert->bugfree->results    = 'Ergebnis';
$lang->convert->bugfree->actions    = 'Verlauf';
$lang->convert->bugfree->files      = 'Datei';

$lang->convert->redmine = new stdclass();
$lang->convert->redmine->users        = 'Benutzer';
$lang->convert->redmine->groups       = 'Gruppe';
$lang->convert->redmine->products     = $lang->productCommon;
$lang->convert->redmine->executions   = $lang->executionCommon;
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

$lang->convert->jira = new stdclass();
$lang->convert->jira->method           = 'Chose Import Method';
$lang->convert->jira->next             = 'Next';
$lang->convert->jira->importFromDB     = 'Import From Database';
$lang->convert->jira->importFromFile   = 'Import From File';
$lang->convert->jira->mapJira2Zentao   = 'Map Jira To Zentao';
$lang->convert->jira->database         = 'Jira Database';
$lang->convert->jira->dbNameNotice     = "Please enter the Jira database name.";
$lang->convert->jira->importNotice     = 'Notice: Importing data is risky! Make sure to complete the following steps in sequence before merging.';
$lang->convert->jira->dbDesc           = 'If your Jira uses MySQL database, please choose this way.';
$lang->convert->jira->fileDesc         = 'Choose this method if your Jira uses a non-MySQL database.';
$lang->convert->jira->jiraObject       = 'Jira Issues';
$lang->convert->jira->zentaoObject     = 'Zentao Object';
$lang->convert->jira->jiraLinkType     = 'Jira Relates';
$lang->convert->jira->zentaoLinkType   = 'Zentao Link Type';
$lang->convert->jira->jiraResolution   = 'Jira Resolution';
$lang->convert->jira->zentaoResolution = 'Zentao Resolution';
$lang->convert->jira->zentaoReason     = 'Zentao Story Closed Reason';
$lang->convert->jira->jiraStatus       = 'Jira Issues Status';
$lang->convert->jira->storyStatus      = 'Zentao Story Status';
$lang->convert->jira->storyStage       = 'Zentao Story Stage';
$lang->convert->jira->bugStatus        = 'Zentao Bug Status';
$lang->convert->jira->taskStatus       = 'Zentao Task Status';
$lang->convert->jira->initJiraUser     = 'Init Jira User';
$lang->convert->jira->importJira       = 'Import Jira';
$lang->convert->jira->start            = 'Start';

$lang->convert->jira->dbNameEmpty        = 'Jira database name cannot be empty!';
$lang->convert->jira->invalidDB          = 'Invalid database name!';
$lang->convert->jira->invalidTable       = 'This database is not a Jira database!';
$lang->convert->jira->notReadAndWrite    = 'Please modify the %s directory permissions!';
$lang->convert->jira->notExistEntities   = 'The %s file does not exist!';
$lang->convert->jira->passwordNotice     = 'Set the default password of Jira users after they are imported into ZenTao. Users can modify the password in ZenTao later.';
$lang->convert->jira->groupNotice        = 'Set the default permission grouping of Jira users after importing into ZenTao.';
$lang->convert->jira->passwordDifferent  = 'Password do not match!';
$lang->convert->jira->passwordEmpty      = 'Password can not be empty!';
$lang->convert->jira->passwordLess       = 'Password must not be less than six characters!';
$lang->convert->jira->importSuccessfully = 'Done!';
$lang->convert->jira->importResult       = "Import <strong class='text-red'>%s</strong> data, <strong class='%scount'>%s</strong> pieces of data processed；";
$lang->convert->jira->importing          = 'Data is being imported, please do not switch to other pages.';
$lang->convert->jira->importingAB        = 'Data Import';
$lang->convert->jira->imported           = 'Data Import Complete';

$lang->convert->jira->zentaoObjectList[''] = '';
$lang->convert->jira->zentaoObjectList['task']        = 'Task';
$lang->convert->jira->zentaoObjectList['requirement'] = 'Requirement';
$lang->convert->jira->zentaoObjectList['story']       = 'Story';
$lang->convert->jira->zentaoObjectList['bug']         = 'Bug';

$lang->convert->jira->zentaoLinkTypeList['subTaskLink']  = 'Parent-Child Task';
$lang->convert->jira->zentaoLinkTypeList['subStoryLink'] = 'Parent-Child Story';
$lang->convert->jira->zentaoLinkTypeList['duplicate']    = 'Duplicate';
$lang->convert->jira->zentaoLinkTypeList['relates']      = 'Relates';

$lang->convert->jira->steps[1] = 'Objects';
$lang->convert->jira->steps[2] = 'Relates';
$lang->convert->jira->steps[3] = 'Resolution';
$lang->convert->jira->steps[4] = 'Status';

$lang->convert->jira->importSteps['db'][1]   = 'Backup ZenTao database, backup Jira database.';
$lang->convert->jira->importSteps['db'][2]   = 'Using ZenTao when importing data will cause performance pressure on the server, please try to ensure that no one is using ZenTao when importing data.';
$lang->convert->jira->importSteps['db'][3]   = 'Import the Jira database into the Mysql used by ZenTao, the name is distinguished from the ZenTao database.';
$lang->convert->jira->importSteps['db'][4]   = "Put the Jira <strong class='text-red'> attachments</strong> directory under <strong class='text-red'>%s</strong>, Make sure you have enough disk space on the ZenTao server.";
$lang->convert->jira->importSteps['db'][5]   = "After the above steps are completed, please enter the Jira database name to proceed to the next step.";

$lang->convert->jira->importSteps['file'][1] = 'Backup ZenTao database, backup Jira database.';
$lang->convert->jira->importSteps['file'][2] = 'Using ZenTao when importing data will cause performance pressure on the server, please try to ensure that no one is using ZenTao when importing data.';
$lang->convert->jira->importSteps['file'][3] = "Put the Jira backup file <strong class='text-red'>entities.xml</strong> under <strong class='text-red'>%s</strong>.";
$lang->convert->jira->importSteps['file'][4] = "Put the Jira <strong class='text-red'> attachments</strong> directory under <strong class='text-red'>%s</strong>, Make sure you have enough disk space on the ZenTao server.";
$lang->convert->jira->importSteps['file'][5]   = "After the above steps are completed, click Next.";

$lang->convert->jira->objectList['user']      = 'User';
$lang->convert->jira->objectList['project']   = 'Project';
$lang->convert->jira->objectList['issue']     = 'Issue';
$lang->convert->jira->objectList['build']     = 'Build';
$lang->convert->jira->objectList['issuelink'] = 'Issue Link';
$lang->convert->jira->objectList['action']    = 'Action';
$lang->convert->jira->objectList['file']      = 'File';
