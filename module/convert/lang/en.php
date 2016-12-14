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
$lang->convert->common  = 'Imported';
$lang->convert->index   = 'Home';

$lang->convert->start   = 'Start';
$lang->convert->desc    = <<<EOT
<p>Welcome to the System Conversion Wizard, this program will assist you to convert data to ZenTao PMS.</p>
<strong>There is certain risk of conversion, so it is strongly recommended that you back up your databse and relavant files before conversion, and make sure no one is operating on either systems.</strong>
EOT;

$lang->convert->setConfig      = 'Source Config';
$lang->convert->setBugfree     = 'Bugfree Config';
$lang->convert->setRedmine     = 'Redmine Config';
$lang->convert->checkBugFree   = 'Check Bugfree';
$lang->convert->checkRedmine   = 'Check Redmine';
$lang->convert->convertRedmine = 'Convert Redmine';
$lang->convert->convertBugFree = 'Convert BugFree';

$lang->convert->selectSource     = 'Select source system and its version';
$lang->convert->mustSelectSource = "You must select a source system.";

$lang->convert->direction             = "{$lang->projectCommon} converted to";
$lang->convert->questionTypeOfRedmine = 'Type in Redmine';
$lang->convert->aimTypeOfZentao       = 'Convert to Type in Zentao';

$lang->convert->directionList['bug']   = 'Bug';
$lang->convert->directionList['task']  = 'Task';
$lang->convert->directionList['story'] = 'Story';

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');
$lang->convert->sourceList['Redmine'] = array('Redmine_1.1' => '1.1');

$lang->convert->setting     = 'Settings';
$lang->convert->checkConfig = 'Check Settings';

$lang->convert->ok          = '<span class="text-success"><i class="icon-ok-sign"></i> OK </span>';
$lang->convert->fail        = '<span class="text-danger"><i class="icon-remove-sign"></i> Failed</span>';

$lang->convert->dbHost      = 'Database Server';
$lang->convert->dbPort      = 'Server Port';
$lang->convert->dbUser      = 'Databse User Name';
$lang->convert->dbPassword  = 'Database Password';
$lang->convert->dbName      = 'Databse used in %s';
$lang->convert->dbCharset   = '%s Database Code';
$lang->convert->dbPrefix    = '%s Table Prefix';
$lang->convert->installPath = '%s Installed path';

$lang->convert->checkDB    = 'Database';
$lang->convert->checkTable = 'Table';
$lang->convert->checkPath  = 'Installed Path';

$lang->convert->execute    = 'Convert';
$lang->convert->item       = 'Item Converted';
$lang->convert->count      = 'Count';
$lang->convert->info       = 'Info';

$lang->convert->bugfree = new stdclass();
$lang->convert->bugfree->users    = 'User';
$lang->convert->bugfree->projects = $lang->projectCommon;
$lang->convert->bugfree->modules  = 'Module';
$lang->convert->bugfree->bugs     = 'Bug';
$lang->convert->bugfree->cases    = 'Test Case';
$lang->convert->bugfree->results  = 'Result';
$lang->convert->bugfree->actions  = 'History';
$lang->convert->bugfree->files    = 'File';

$lang->convert->redmine = new stdclass();
$lang->convert->redmine->users        = 'User';
$lang->convert->redmine->groups       = 'Group';
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
$lang->convert->redmine->files        = 'File';

$lang->convert->errorFileNotExits  = 'File %s is not found.';
$lang->convert->errorUserExists    = 'User %s existed.';
$lang->convert->errorGroupExists   = 'Group %s existed.';
$lang->convert->errorBuildExists   = 'Build %s existed.';
$lang->convert->errorReleaseExists = 'Release %s existed.';
$lang->convert->errorCopyFailed    = 'File %s copy failed.';

$lang->convert->setParam = 'Please set parameters.';

$lang->convert->statusType = new stdclass();
$lang->convert->priType    = new stdclass();

$lang->convert->aimType           = 'Convert Issue';
$lang->convert->statusType->bug   = 'Convert Status (Bug Status)';
$lang->convert->statusType->story = 'Convert Status (Story Status)';
$lang->convert->statusType->task  = 'Convert Status (Task Status)';
$lang->convert->priType->bug      = 'Convert Priority (Bug Status)';
$lang->convert->priType->story    = 'Convert Priority (Story Status)';
$lang->convert->priType->task     = 'Convert Priority (Task Status)';

$lang->convert->issue = new stdclass();
$lang->convert->issue->redmine = 'Redmine';
$lang->convert->issue->zentao  = 'ZenTao';
$lang->convert->issue->goto    = 'Convert To';
