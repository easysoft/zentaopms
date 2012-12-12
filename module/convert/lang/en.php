<?php
/**
 * The convert module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->convert->common  = 'Import';
$lang->convert->index   = 'Index';
$lang->convert->next    = 'Next';
$lang->convert->pre     = 'Back';
$lang->convert->reload  = 'Reload';
$lang->convert->error   = 'Error ';

$lang->convert->start   = 'Begin import';
$lang->convert->desc    = <<<EOT
<p>Welcome to use this convert wizard which will help you to import other system data to ZenTaoPMS.</p>
<strong>Importing is dangerous. Be sure to backup your database and other data files and sure nobody is using pms when importing.</strong>
EOT;

$lang->convert->setConfig      = 'Source config';
$lang->convert->setBugfree     = 'Bugfree config';
$lang->convert->setRedmine     = 'Redmine config';
$lang->convert->checkBugFree   = 'Check Bugfree';
$lang->convert->checkRedmine   = 'Check Redmine';
$lang->convert->convertRedmine = 'Convert Redmine';
$lang->convert->convertBugFree = 'Convert BugFree';

$lang->convert->selectSource     = 'Select source system and version';
$lang->convert->source           = 'Source system';
$lang->convert->version          = 'Version';
$lang->convert->mustSelectSource = "Must select a source system";

$lang->convert->direction              = 'Please select the direction of issue in Redmine';
$lang->convert->questionTypeOfRedmine  = 'Types of issue in Redmine';
$lang->convert->aimTypeOfZentao        = 'Aim type in Zentao';

$lang->convert->directionList['bug']   = 'Bug';
$lang->convert->directionList['task']  = 'Task';
$lang->convert->directionList['story'] = 'Story';

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');
$lang->convert->sourceList['Redmine'] = array('Redmine_1_1' => '1.1');

$lang->convert->setting     = 'Setting';
$lang->convert->checkConfig = 'Check setting';

$lang->convert->ok         = 'Check passed(√)';
$lang->convert->fail       = 'Check failed(×)';

$lang->convert->settingDB   = 'Set database';
$lang->convert->dbHost      = 'Database server';
$lang->convert->dbPort      = 'Server port';
$lang->convert->dbUser      = 'Database user';
$lang->convert->dbPassword  = 'Database password';
$lang->convert->dbName      = '%s database';
$lang->convert->dbCharset   = '%s charset';
$lang->convert->dbPrefix    = '%s table prefix';
$lang->convert->installPath = '%s installed path';

$lang->convert->checkDB    = 'Database';
$lang->convert->checkTable = 'Table';
$lang->convert->checkPath  = 'Installed path';

$lang->convert->execute    = 'Execute import';
$lang->convert->item       = 'Imported items';
$lang->convert->count      = 'Count';
$lang->convert->info       = 'Info';

$lang->convert->bugfree = new stdclass();
$lang->convert->bugfree->users    = 'User';
$lang->convert->bugfree->projects = 'Project';
$lang->convert->bugfree->modules  = 'Module';
$lang->convert->bugfree->bugs     = 'Bug';
$lang->convert->bugfree->cases    = 'Case';
$lang->convert->bugfree->results  = 'Result';
$lang->convert->bugfree->actions  = 'History';
$lang->convert->bugfree->files    = 'File';

$lang->convert->redmine = new stdclass();
$lang->convert->redmine->users        = 'Users';
$lang->convert->redmine->groups       = 'Groups';
$lang->convert->redmine->products     = 'Products';
$lang->convert->redmine->projects     = 'Projects';
$lang->convert->redmine->stories      = 'Stories';
$lang->convert->redmine->tasks        = 'Tasks';
$lang->convert->redmine->bugs         = 'Bugs';
$lang->convert->redmine->productPlans = 'ProductPlans';
$lang->convert->redmine->teams        = 'Teams';
$lang->convert->redmine->releases     = 'Releases';
$lang->convert->redmine->builds       = 'Builds';
$lang->convert->redmine->docLibs      = 'DocLibs';
$lang->convert->redmine->docs         = 'Docs';
$lang->convert->redmine->files        = 'files';

$lang->convert->errorConnectDB     = 'Connect to database server failed.';
$lang->convert->errorFileNotExits  = 'File %s not exits.';
$lang->convert->errorUserExists    = 'User %s exits already.';
$lang->convert->errorGroupExists   = 'Group %s exits already.';
$lang->convert->errorBuildExists   = 'Build %s exits already.';
$lang->convert->errorReleaseExists = 'Release %s exits already.';
$lang->convert->errorCopyFailed    = 'file %s copy failed.';

$lang->convert->setParam = 'Please set params';

$lang->convert->statusType = new stdclass();
$lang->convert->priType    = new stdclass();

$lang->convert->aimType           = 'Issue types goto';
$lang->convert->statusType->bug   = 'Status types goto(status of Bug)';
$lang->convert->statusType->story = 'Status types goto(status of story)';
$lang->convert->statusType->task  = 'Status types goto(status of task)';
$lang->convert->priType->bug      = 'Priority types goto(priority of Bug)';
$lang->convert->priType->story    = 'Priority types goto(priority of story)';
$lang->convert->priType->task     = 'Priority types goto(priority of task)';

$lang->convert->issue = new stdclass();
$lang->convert->issue->redmine = 'Redmine';
$lang->convert->issue->zentao  = 'ZenTao';
$lang->convert->issue->goto    = 'Goto';
