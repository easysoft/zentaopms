<?php
/**
 * The convert module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: en.php 4129 2013-01-18 01:58:14Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->convert->common  = 'Imported';
$lang->convert->index   = 'Home';

$lang->convert->start   = 'Start';
$lang->convert->desc    = <<<EOT
<p>Welcome to the System Conversion Wizard, this program will assist you to convert data to ZenTao.</p>
<strong>There are risks in the conversion, so it is strongly recommended that you back up your databse and relavant files before conversion, and make sure that no one is using either system.</strong>
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

$lang->convert->direction             = "{$lang->executionCommon} converted to";
$lang->convert->questionTypeOfRedmine = 'Type in Redmine';
$lang->convert->aimTypeOfZentao       = 'Convert to Type in ZenTao';

$lang->convert->directionList['bug']   = 'Bug';
$lang->convert->directionList['task']  = 'Task';
$lang->convert->directionList['story'] = 'Story';

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');
$lang->convert->sourceList['Redmine'] = array('Redmine_1.1' => '1.1');

$lang->convert->setting     = 'Settings';
$lang->convert->checkConfig = 'Check Settings';

$lang->convert->ok          = '<span class="text-success"><i class="icon-check-sign"></i> OK </span>';
$lang->convert->fail        = '<span class="text-danger"><i class="icon-remove-sign"></i> Failed</span>';

$lang->convert->dbHost      = 'Database Server';
$lang->convert->dbPort      = 'Server Port';
$lang->convert->dbUser      = 'Database User Name';
$lang->convert->dbPassword  = 'Database Password';
$lang->convert->dbName      = 'Database used in %s';
$lang->convert->dbCharset   = '%s Database Coding';
$lang->convert->dbPrefix    = '%s Table Prefix';
$lang->convert->installPath = '%s Installation Root Directory';

$lang->convert->checkDB    = 'Database';
$lang->convert->checkTable = 'Table';
$lang->convert->checkPath  = 'Installation Path';

$lang->convert->execute    = 'Convert';
$lang->convert->item       = 'Item Converted';
$lang->convert->count      = 'No.';
$lang->convert->info       = 'Info';

$lang->convert->bugfree = new stdclass();
$lang->convert->bugfree->users      = 'User';
$lang->convert->bugfree->executions = $lang->executionCommon;
$lang->convert->bugfree->modules    = 'Module';
$lang->convert->bugfree->bugs       = 'Bug';
$lang->convert->bugfree->cases      = 'Test Case';
$lang->convert->bugfree->results    = 'Result';
$lang->convert->bugfree->actions    = 'History';
$lang->convert->bugfree->files      = 'Files';

$lang->convert->redmine = new stdclass();
$lang->convert->redmine->users        = 'User';
$lang->convert->redmine->groups       = 'Group';
$lang->convert->redmine->products     = $lang->productCommon;
$lang->convert->redmine->executions   = $lang->executionCommon;
$lang->convert->redmine->stories      = 'Story';
$lang->convert->redmine->tasks        = 'Task';
$lang->convert->redmine->bugs         = 'Bug';
$lang->convert->redmine->productPlans = $lang->productCommon . 'Plan';
$lang->convert->redmine->teams        = 'Team';
$lang->convert->redmine->releases     = 'Release';
$lang->convert->redmine->builds       = 'Build';
$lang->convert->redmine->docLibs      = 'Doc Lib';
$lang->convert->redmine->docs         = 'Doc';
$lang->convert->redmine->files        = 'Files';

$lang->convert->errorFileNotExits  = 'File %s is not found.';
$lang->convert->errorUserExists    = 'User %s existed.';
$lang->convert->errorGroupExists   = 'Group %s existed.';
$lang->convert->errorBuildExists   = 'Build %s existed.';
$lang->convert->errorReleaseExists = 'Release %s existed.';
$lang->convert->errorCopyFailed    = 'File %s copy failed.';

$lang->convert->setParam = 'Set parameters.';

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
