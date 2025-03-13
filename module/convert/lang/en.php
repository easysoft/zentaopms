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

$lang->convert->jiraUserMode = array();
$lang->convert->jiraUserMode['account'] = 'Use Jira Account';
$lang->convert->jiraUserMode['email']   = 'Use Jira Email';

$lang->convert->confluenceUserMode = array();
$lang->convert->confluenceUserMode['account'] = 'Use Confluence Account';
$lang->convert->confluenceUserMode['email']   = 'Use Confluence Email';

$lang->convert->directionList['bug']   = 'Bug';
$lang->convert->directionList['task']  = 'Task';
$lang->convert->directionList['story'] = $lang->SRCommon;

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');
$lang->convert->sourceList['Redmine'] = array('Redmine_1.1' => '1.1');

$lang->convert->setting     = 'Settings';
$lang->convert->checkConfig = 'Check Settings';
$lang->convert->add         = 'Add';
$lang->convert->title       = 'Title';

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
$lang->convert->importFailed       = 'Import failed';

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
$lang->convert->jira->back             = 'Back';
$lang->convert->jira->next             = 'Next';
$lang->convert->jira->importFromDB     = 'Import From Database';
$lang->convert->jira->importFromFile   = 'Import From File';
$lang->convert->jira->mapJira2Zentao   = 'Map Jira To Zentao';
$lang->convert->jira->database         = 'Jira Database';
$lang->convert->jira->domain           = 'Jira Domain';
$lang->convert->jira->admin            = 'Jira Account';
$lang->convert->jira->token            = 'Jira Passwork/Token';
$lang->convert->jira->dbNameNotice     = "Please enter the Jira database name.";
$lang->convert->jira->importNotice     = 'Notice: Importing data is risky! Make sure to complete the following steps in sequence before merging.';
$lang->convert->jira->accountNotice    = 'Those who use email will use the string before @ as their username, and those exceeding 30 characters will be truncated.';
$lang->convert->jira->apiError         = 'Unable to connect to Jira API interface, please check your Jira domain name and account, password/Token information.';
$lang->convert->jira->dbDesc           = 'If your Jira is a locally deployed version, please choose this way.';
$lang->convert->jira->fileDesc         = 'If your Jira is a cloud version or it is inconvenient to access the database, please choose this way';
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
$lang->convert->jira->objectField      = 'Field mapping';
$lang->convert->jira->objectStatus     = 'Status mapping';
$lang->convert->jira->objectAction     = 'Action mapping';
$lang->convert->jira->objectResolution = 'Resolution mapping';
$lang->convert->jira->jiraField        = 'Jira %s Field';
$lang->convert->jira->jiraStatus       = 'Jira %s Status';
$lang->convert->jira->jiraAction       = 'Jira %s Action';
$lang->convert->jira->jiraResolution   = 'Jira %s Resolution';
$lang->convert->jira->zentaoField      = 'Zentao %s Field';
$lang->convert->jira->zentaoStatus     = 'Zentao %s Status';
$lang->convert->jira->zentaoStage      = 'Zentao %s Stage';
$lang->convert->jira->zentaoAction     = 'Zentao %s Action';
$lang->convert->jira->zentaoReason     = 'Zentao %s Closed Reason';
$lang->convert->jira->zentaoResolution = 'Zentao %s Resolution';
$lang->convert->jira->initJiraUser     = 'Init Jira User';
$lang->convert->jira->importJira       = 'Import Jira';
$lang->convert->jira->start            = 'Start';

$lang->convert->jira->dbNameEmpty        = 'Jira database name cannot be empty!';
$lang->convert->jira->invalidDB          = 'Invalid database name!';
$lang->convert->jira->invalidTable       = 'This database is not a Jira database!';
$lang->convert->jira->notReadAndWrite    = 'The directory does not exist or insufficient permissions! Please create the directory %s and grant read and write permissions.';
$lang->convert->jira->notExistEntities   = 'The %s file does not exist!';
$lang->convert->jira->passwordNotice     = 'Set the default password of users after they are imported into ZenTao. Users can modify the password in ZenTao later.';
$lang->convert->jira->groupNotice        = 'Set the default permission grouping of users after importing into ZenTao.';
$lang->convert->jira->mapObjectNotice    = 'When selecting a mapping relationship, if you choose to add a new workflow, a new object will be automatically created in the workflow after import.';
$lang->convert->jira->mapFieldNotice     = 'The built-in fields in JIRA have been automatically matched. Please select the mapping relationship for custom fields. When selecting the mapping relationship, if you choose to add, new fields will be automatically created after import, and unselected fields will not be imported.';
$lang->convert->jira->mapStatusNotice    = 'When selecting a mapping relationship, unselected states are imported and automatically matched to %s.';
$lang->convert->jira->mapReasonNotice    = 'When selecting a mapping relationship, if "Add" is chosen, a new solution will be automatically created after import. Solutions not selected will be matched as "Completed" by default after import.';
$lang->convert->jira->mapRelationNotice  = 'When selecting a mapping relationship, if you choose to add, an association relationship will be automatically created after import, and unselected associations will not be imported.';
$lang->convert->jira->passwordDifferent  = 'Password do not match!';
$lang->convert->jira->passwordEmpty      = 'Password can not be empty!';
$lang->convert->jira->passwordLess       = 'Password must not be less than six characters!';
$lang->convert->jira->importSuccessfully = 'Done!';
$lang->convert->jira->importResult       = "Import <strong class='text-red'>%s</strong> data, <strong class='%scount'>%s</strong> pieces of data processed；";
$lang->convert->jira->importing          = 'Data is being imported, please do not switch to other pages.';
$lang->convert->jira->importingAB        = 'Data Import';
$lang->convert->jira->imported           = 'Data Import Complete';
$lang->convert->jira->restore            = 'The last import of information was not completed. Do you want to continue filling in from the last process?';

$lang->convert->jira->zentaoObjectList['']            = '';
$lang->convert->jira->zentaoObjectList['epic']        = 'Epic';
$lang->convert->jira->zentaoObjectList['requirement'] = 'Requirement';
$lang->convert->jira->zentaoObjectList['story']       = 'Story';
$lang->convert->jira->zentaoObjectList['task']        = 'Task';
$lang->convert->jira->zentaoObjectList['testcase']    = 'Case';
$lang->convert->jira->zentaoObjectList['bug']         = 'Bug';

$lang->convert->jira->zentaoLinkTypeList['subTaskLink']  = 'Parent-Child Task';
$lang->convert->jira->zentaoLinkTypeList['subStoryLink'] = 'Parent-Child Story';
$lang->convert->jira->zentaoLinkTypeList['duplicate']    = 'Duplicate';
$lang->convert->jira->zentaoLinkTypeList['relates']      = 'Relates';

$lang->convert->jira->steps['object']     = 'Objects';
$lang->convert->jira->steps['objectData'] = 'Object Data';
$lang->convert->jira->steps['relation']   = 'Relation';
$lang->convert->jira->steps['user']       = 'Import Jira User';
$lang->convert->jira->steps['confirme']   = 'Import Data';

$lang->convert->jira->importSteps['db'][1]   = 'Backup ZenTao database, backup Jira database.';
$lang->convert->jira->importSteps['db'][2]   = 'Using ZenTao when importing data will cause performance pressure on the server, please try to ensure that no one is using ZenTao when importing data.';
$lang->convert->jira->importSteps['db'][3]   = 'Import the Jira database into the Mysql used by ZenTao, the name is distinguished from the ZenTao database.';
$lang->convert->jira->importSteps['db'][4]   = "Put the Jira <strong class='text-red'> attachments</strong> directory under <strong class='text-red'>%s</strong>, Make sure you have enough disk space on the ZenTao server.";
$lang->convert->jira->importSteps['db'][5]   = "After the above steps are completed, please enter the Jira database name to proceed to the next step.";

$lang->convert->jira->importSteps['file'][1] = 'Backup ZenTao database, backup Jira file.';
$lang->convert->jira->importSteps['file'][2] = 'Using ZenTao when importing data will cause performance pressure on the server, please try to ensure that no one is using ZenTao when importing data.';
$lang->convert->jira->importSteps['file'][3] = "Put the Jira backup file <strong class='text-red'>entities.xml</strong> under <strong class='text-red'>%s</strong>.";
$lang->convert->jira->importSteps['file'][4] = "Put the Jira <strong class='text-red'> attachments</strong> directory under <strong class='text-red'>%s</strong>, Make sure you have enough disk space on the ZenTao server.";
$lang->convert->jira->importSteps['file'][5] = "To ensure the integrity of the imported data, please enter the domain name, administrator account, password/Token of the current Jira environment.";
$lang->convert->jira->importSteps['file'][6] = "After the above steps are completed, click Next.";

$lang->convert->jira->objectList['user']      = 'User';
$lang->convert->jira->objectList['project']   = 'Project';
$lang->convert->jira->objectList['issue']     = 'Issue';
$lang->convert->jira->objectList['build']     = 'Build';
$lang->convert->jira->objectList['issuelink'] = 'Issue Link';
$lang->convert->jira->objectList['worklog']   = 'Worklog';
$lang->convert->jira->objectList['action']    = 'Action';
$lang->convert->jira->objectList['file']      = 'File';

$lang->convert->jira->buildinFields = array();
$lang->convert->jira->buildinFields['summary']              = array('name'=> 'Summary',                'jiraField' => 'SUMMARY',              'control' => 'input',        'optionType' => 'custom', 'type' => 'varchar',    'length' => '255', 'buildin' => false);
$lang->convert->jira->buildinFields['pri']                  = array('name'=> 'Pri',                    'jiraField' => 'PRIORITY',             'control' => 'select',       'optionType' => 'custom', 'type' => 'int',        'length' => '3', 'buildin' => false);
$lang->convert->jira->buildinFields['resolution']           = array('name'=> 'Resolution',             'jiraField' => 'RESOLUTION',           'control' => 'select',       'optionType' => 'custom', 'type' => 'varchar',    'length' => '255', 'buildin' => false);
$lang->convert->jira->buildinFields['reporter']             = array('name'=> 'Reporter',               'jiraField' => 'REPORTER',             'control' => 'select',       'optionType' => 'user',   'type' => 'varchar',    'length' => '255');
$lang->convert->jira->buildinFields['environment']          = array('name'=> 'Environment',            'jiraField' => 'ENVIRONMENT',          'control' => 'multi-select', 'optionType' => 'custom', 'type' => 'text',       'length' => '0');
$lang->convert->jira->buildinFields['duedate']              = array('name'=> 'Duedate',                'jiraField' => 'DUEDATE',              'control' => 'date',         'optionType' => 'custom', 'type' => 'date',       'length' => '0');
$lang->convert->jira->buildinFields['resolutiondate']       = array('name'=> 'Resolution Date',        'jiraField' => 'RESOLUTIONDATE',       'control' => 'datetime',     'optionType' => 'custom', 'type' => 'datetime',   'length' => '0', 'buildin' => false);
$lang->convert->jira->buildinFields['votes']                = array('name'=> 'Votes',                  'jiraField' => 'VOTES',                'control' => 'integer',      'optionType' => 'custom', 'type' => 'int',        'length' => '6');
$lang->convert->jira->buildinFields['timeoriginalestimate'] = array('name'=> 'Time Original Estimate', 'jiraField' => 'TIMEORIGINALESTIMATE', 'control' => 'decimal',      'optionType' => 'custom', 'type' => 'decimal',    'length' => '0');
$lang->convert->jira->buildinFields['timespent']            = array('name'=> 'Timespent',              'jiraField' => 'TIMESPENT',            'control' => 'decimal',      'optionType' => 'custom', 'type' => 'decimal',    'length' => '0');
$lang->convert->jira->buildinFields['fixfor']               = array('name'=> 'Fixfor',                 'jiraField' => 'FIXFOR',               'control' => 'integer',      'optionType' => 'custom', 'type' => 'int',        'length' => '6');
$lang->convert->jira->buildinFields['desc']                 = array('name'=> 'Desc',                   'jiraField' => 'DESCRIPTION',          'control' => 'richtext',     'optionType' => 'custom', 'type' => 'mediumtext', 'length' => '0', 'buildin' => false);
