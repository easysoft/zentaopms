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
$lang->convert->index   = 'Homepage';

$lang->convert->start   = 'Start';
$lang->convert->desc    = <<<EOT
<p>Welcome to the System Migration Wizard. This tool will assist you in migrating data from external systems into the Sanplex.</p>
<strong>Data migration involves potential risks. Before proceeding, we strongly recommend backing up your database and associated data files. Please also ensure that no other users are performing operations on the system during the migration process</strong>
EOT;

$lang->convert->setConfig      = 'Source System Config';
$lang->convert->setBugfree     = 'Bugfree Config';
$lang->convert->setRedmine     = 'Redmine Config';
$lang->convert->checkBugFree   = 'Check Bugfree';
$lang->convert->checkRedmine   = 'Check Redmine';
$lang->convert->convertRedmine = 'Migrate from Redmine';
$lang->convert->convertBugFree = 'Migrate from BugFree';

$lang->convert->selectSource     = 'Select source system and its version.';
$lang->convert->mustSelectSource = "A source system is required.";

$lang->convert->direction             = "Migrate {$lang->executionCommon} Issue";
$lang->convert->questionTypeOfRedmine = 'Issue Type in Redmine';
$lang->convert->aimTypeOfZentao       = 'Issue Type in Sanplex';

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
$lang->convert->checkConfig = 'Check Configs';
$lang->convert->add         = 'Add';
$lang->convert->title       = 'Title';

$lang->convert->ok          = '<span class="text-success"><i class="icon-check-sign"></i> Pass </span>';
$lang->convert->fail        = '<span class="text-danger"><i class="icon-remove-sign"></i> Failed</span>';

$lang->convert->dbHost      = 'Database Server';
$lang->convert->dbPort      = 'Server Port';
$lang->convert->dbUser      = 'Database User Name';
$lang->convert->dbPassword  = 'Database Password';
$lang->convert->dbName      = '%s Database';
$lang->convert->dbCharset   = '%s Database Coding';
$lang->convert->dbPrefix    = '%s Table Prefix';
$lang->convert->installPath = '%s Installation Root Directory';

$lang->convert->checkDB    = 'Database';
$lang->convert->checkTable = 'Table';
$lang->convert->checkPath  = 'Installation Path';

$lang->convert->execute    = 'Start Migration';
$lang->convert->item       = 'Items';
$lang->convert->count      = 'Count';
$lang->convert->info       = 'Details';

$lang->convert->bugfree = new stdclass();
$lang->convert->bugfree->users      = 'User';
$lang->convert->bugfree->executions = $lang->executionCommon;
$lang->convert->bugfree->modules    = 'Module';
$lang->convert->bugfree->bugs       = 'Bug';
$lang->convert->bugfree->cases      = 'Test Case';
$lang->convert->bugfree->results    = 'Test Result';
$lang->convert->bugfree->actions    = 'History';
$lang->convert->bugfree->files      = 'Attachments';

$lang->convert->redmine = new stdclass();
$lang->convert->redmine->users        = 'User';
$lang->convert->redmine->groups       = 'User Group';
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
$lang->convert->redmine->docs         = 'Docs';
$lang->convert->redmine->files        = 'Attachments';

$lang->convert->errorFileNotExits  = 'File %s is not found.';
$lang->convert->errorUserExists    = 'User %s already exists.';
$lang->convert->errorGroupExists   = 'Group %s already exists.';
$lang->convert->errorBuildExists   = 'Build %s already exists.';
$lang->convert->errorReleaseExists = 'Release %s already exists.';
$lang->convert->errorCopyFailed    = 'Failed to copy file %s.';
$lang->convert->importFailed       = 'Migration failed.';

$lang->convert->setParam = 'Please set the migration match items.';

$lang->convert->statusType = new stdclass();
$lang->convert->priType    = new stdclass();

$lang->convert->aimType           = 'Issue Types';
$lang->convert->statusType->bug   = 'Status Type (Bug Status)';
$lang->convert->statusType->story = 'Status Type (Story Status)';
$lang->convert->statusType->task  = 'Status Type (Task Status)';
$lang->convert->priType->bug      = 'Priority Type (Bug Status)';
$lang->convert->priType->story    = 'Priority Type (Story Status)';
$lang->convert->priType->task     = 'Priority Type (Task Status)';

$lang->convert->issue = new stdclass();
$lang->convert->issue->redmine = 'Redmine';
$lang->convert->issue->zentao  = 'Sanplex';
$lang->convert->issue->goto    = 'Map To';

$lang->convert->jira = new stdclass();
$lang->convert->jira->method           = 'Select Migration Method';
$lang->convert->jira->back             = 'Back';
$lang->convert->jira->next             = 'Next';
$lang->convert->jira->importFromDB     = 'Database Import';
$lang->convert->jira->importFromFile   = 'File Import';
$lang->convert->jira->importFromAPI    = 'API Import';
$lang->convert->jira->mapJira2Zentao   = 'Map Jira To Zentao';
$lang->convert->jira->database         = 'Jira Database';
$lang->convert->jira->domain           = 'Jira Domain';
$lang->convert->jira->admin            = 'Jira Admin Account';
$lang->convert->jira->token            = 'Jira Password/Token';
$lang->convert->jira->apiToken         = 'Jira Token';
$lang->convert->jira->dbNameNotice     = "Please enter the Jira database name.";
$lang->convert->jira->importNotice     = 'Warning: Data import carries risks. Please ensure the following steps are completed in sequence before merging.';
$lang->convert->jira->accountNotice    = 'For email accounts, the text before the "@" symbol will be used as the username. Characters exceeding the 30-character limit will be truncated.';
$lang->convert->jira->userExceeds      = 'The current system license limit is %s users. Please verify that the total user count after import does not exceed this limit, as the import process will be aborted if it does.';
$lang->convert->jira->apiError         = 'Unable to connect to the Jira API. Please verify your Jira domain, username, and password/API Token.';
$lang->convert->jira->dbDesc           = 'Best for self-hosted Jira instances (Server or Data Center) with database access.';
$lang->convert->jira->fileDesc         = 'Best for Jira Cloud or when database access is restricted.';
$lang->convert->jira->apiDesc          = 'Best for Jira Cloud or when you cannot access the database and server files.';
$lang->convert->jira->jiraObject       = 'Jira Issues';
$lang->convert->jira->zentaoObject     = 'Sanplex Objects';
$lang->convert->jira->jiraLinkType     = 'Jira Relations';
$lang->convert->jira->zentaoLinkType   = 'Sanplex Relations';
$lang->convert->jira->jiraResolution   = 'Jira Resolution';
$lang->convert->jira->zentaoResolution = 'Zentao Resolution';
$lang->convert->jira->zentaoReason     = 'Zentao Story Closed Reason';
$lang->convert->jira->jiraStatus       = 'Jira Issues Status';
$lang->convert->jira->storyStatus      = 'Zentao Story Status';
$lang->convert->jira->storyStage       = 'Zentao Story Phase';
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
$lang->convert->jira->zentaoStage      = 'Zentao %s Phase';
$lang->convert->jira->zentaoAction     = 'Zentao %s Action';
$lang->convert->jira->zentaoReason     = 'Zentao %s Closure Reason';
$lang->convert->jira->zentaoResolution = 'Zentao %s Resolution';
$lang->convert->jira->initJiraUser     = 'Set Jira Users';
$lang->convert->jira->importJira       = 'Migrate From Jira';
$lang->convert->jira->start            = 'Start Migration';

$lang->convert->jira->dbNameEmpty        = 'Jira database name is required.';
$lang->convert->jira->invalidDB          = 'Invalid database name.';
$lang->convert->jira->invalidTable       = 'This database is not a Jira database.';
$lang->convert->jira->notReadAndWrite    = 'The directory does not exist or insufficient permissions! Please create the directory %s and grant read and write permissions.';
$lang->convert->jira->notExistEntities   = 'The %s file does not exist.';
$lang->convert->jira->passwordNotice     = 'Set the default password for users migrated to Sanplex. Users can later update their passwords within Sanplex.';
$lang->convert->jira->groupNotice        = 'Set the default permission group for users migrated to Sanplex.';
$lang->convert->jira->mapObjectNotice    = 'When defining field mappings, if “Create as a new workflow” is selected, a new workflow object will be automatically created upon import.';
$lang->convert->jira->mapFieldNotice     = 'Built-in Jira fields have been matched automatically. Please define mappings for custom fields. If “Create new” is selected, new fields will be created upon import; any unmapped fields will not be imported.';
$lang->convert->jira->mapStatusNotice    = 'When defining status mappings, any unmapped statuses will be automatically mapped to %s after migration.';
$lang->convert->jira->mapReasonNotice    = 'When defining resolution mappings, selecting “Create new” will generate new resolution entries upon migration. Any unmapped resolutions will default to “Done.”';
$lang->convert->jira->mapRelationNotice  = 'When defining relationship mappings, selecting “Create new” will automatically create new link types upon import. Any unmapped relationships will not be imported.';
$lang->convert->jira->changeItems        = "Updated %s — previous value: “%s”, new value: “%s.”";
$lang->convert->jira->passwordDifferent  = 'Passwords do not match.';
$lang->convert->jira->passwordEmpty      = 'Password cannot be empty.';
$lang->convert->jira->passwordLess       = 'Password must be at least six characters long.';
$lang->convert->jira->importSuccessfully = 'Jira migration completed.';
$lang->convert->jira->importResult       = "Imported <strong class='text-danger'>%s</strong> data entries, processed <strong class='%s count'>%s</strong> records.";
$lang->convert->jira->importing          = 'Data import in progress — please do not navigate away from this page.';
$lang->convert->jira->importingAB        = 'Migrating data...';
$lang->convert->jira->imported           = 'Data migration completed.';
$lang->convert->jira->restore            = 'The previous migration was not completed. Would you like to continue from where you left off?';

$lang->convert->jira->zentaoObjectList['']            = '';
$lang->convert->jira->zentaoObjectList['epic']        = 'Epic';
$lang->convert->jira->zentaoObjectList['requirement'] = 'Feature';
$lang->convert->jira->zentaoObjectList['story']       = 'Story';
$lang->convert->jira->zentaoObjectList['task']        = 'Task';
$lang->convert->jira->zentaoObjectList['testcase']    = 'Test Case';
$lang->convert->jira->zentaoObjectList['bug']         = 'Bug';

$lang->convert->jira->zentaoLinkTypeList['subTaskLink']  = 'Parent-Child Task';
$lang->convert->jira->zentaoLinkTypeList['subStoryLink'] = 'Parent-Child Story';
$lang->convert->jira->zentaoLinkTypeList['duplicate']    = 'Duplicate';
$lang->convert->jira->zentaoLinkTypeList['relates']      = 'Mutual Relationship';

$lang->convert->jira->steps['object']     = 'Object Mapping';
$lang->convert->jira->steps['objectData'] = 'Object Data Mapping';
$lang->convert->jira->steps['relation']   = 'Global Relationship Mapping';
$lang->convert->jira->steps['user']       = 'Migrate Jira User';
$lang->convert->jira->steps['confirme']   = 'Migration Data Confirmation';

$lang->convert->jira->importSteps['db'][1]   = 'Back up both Sanplex and Jira databases.';
$lang->convert->jira->importSteps['db'][2]   = 'Avoid using Sanplex during the import to prevent server performance issues. Ensure no other users are active on the system during the process.';
$lang->convert->jira->importSteps['db'][3]   = 'Import the Jira database into the MySQL instance used by Sanplex, and assign it a different name from the Sanplex database.';
$lang->convert->jira->importSteps['db'][4]   = "Place the Jira attachment directory <strong class='text-danger'>attachments</strong> under <strong class='text-danger'>%s</strong>, and ensure that the Sanplex server has sufficient disk space.";
$lang->convert->jira->importSteps['db'][5]   = "After completing the above steps, please enter the name of the Jira database to proceed.";

$lang->convert->jira->importSteps['file'][1] = 'Back up the Sanplex database and Jira files.';
$lang->convert->jira->importSteps['file'][2] = 'Avoid using Sanplex during the import to prevent server performance issues. Ensure no other users are active on the system during the process.';
$lang->convert->jira->importSteps['file'][3] = "Place the Jira backup file <strong class='text-danger'>entities.xml</strong> in <strong class='text-danger'>%s</strong> and grant read/write permissions to that directory.";
$lang->convert->jira->importSteps['file'][4] = "Place the Jira attachment directory <strong class='text-danger'>attachments</strong> under <strong class='text-danger'>%s</strong>, and ensure that the Sanplex server has sufficient disk space.";
$lang->convert->jira->importSteps['file'][5] = "Please enter your current Jira domain, admin account, and password/token to ensure data integrity.";
$lang->convert->jira->importSteps['file'][6] = "Click Next after completing the steps above.";

$lang->convert->jira->importSteps['api'][1] = 'Backup Sanplex database.';
$lang->convert->jira->importSteps['api'][2] = 'Avoid using Sanplex during the import to prevent server performance issues. Ensure no other users are active on the system during the process.';
$lang->convert->jira->importSteps['api'][3] = 'Enter the domain name, administrator account, password/Token of the current Jira environment.';
$lang->convert->jira->importSteps['api'][4] = "After the above steps are completed, click Next.";

$lang->convert->jira->objectList['user']       = 'User';
$lang->convert->jira->objectList['project']    = 'Project';
$lang->convert->jira->objectList['issue']      = 'Issue';
$lang->convert->jira->objectList['build']      = 'Build';
$lang->convert->jira->objectList['issuelink']  = 'Relationship';
$lang->convert->jira->objectList['worklog']    = 'Working Log';
$lang->convert->jira->objectList['action']     = 'Comment';
$lang->convert->jira->objectList['changeitem'] = 'Change History';
$lang->convert->jira->objectList['file']       = 'File';

$lang->convert->jira->buildinFields = array();
$lang->convert->jira->buildinFields['summary']              = array('name'=> 'Title',           'jiraField' => 'summary',              'control' => 'input',        'optionType' => 'custom', 'type' => 'varchar',    'length' => '255', 'buildin' => false);
$lang->convert->jira->buildinFields['pri']                  = array('name'=> 'Priority',        'jiraField' => 'priority',             'control' => 'select',       'optionType' => 'custom', 'type' => 'int',        'length' => '3', 'buildin' => false);
$lang->convert->jira->buildinFields['resolution']           = array('name'=> 'Resolution',      'jiraField' => 'resolution',           'control' => 'select',       'optionType' => 'custom', 'type' => 'varchar',    'length' => '255', 'buildin' => false);
$lang->convert->jira->buildinFields['reporter']             = array('name'=> 'Reporter',        'jiraField' => 'reporter',             'control' => 'select',       'optionType' => 'user',   'type' => 'varchar',    'length' => '255');
$lang->convert->jira->buildinFields['duedate']              = array('name'=> 'Due Date',        'jiraField' => 'duedate',              'control' => 'date',         'optionType' => 'custom', 'type' => 'date',       'length' => '0', 'buildin' => false);
$lang->convert->jira->buildinFields['resolutiondate']       = array('name'=> 'Resolution Date', 'jiraField' => 'resolutiondate',       'control' => 'datetime',     'optionType' => 'custom', 'type' => 'datetime',   'length' => '0', 'buildin' => false);
$lang->convert->jira->buildinFields['votes']                = array('name'=> 'Votes',           'jiraField' => 'votes',                'control' => 'integer',      'optionType' => 'custom', 'type' => 'int',        'length' => '6');
$lang->convert->jira->buildinFields['environment']          = array('name'=> 'Environment',     'jiraField' => 'environment',          'control' => 'textarea',     'optionType' => 'custom', 'type' => 'text',       'length' => '0');
$lang->convert->jira->buildinFields['timeoriginalestimate'] = array('name'=> 'Estimated',       'jiraField' => 'timeoriginalestimate', 'control' => 'decimal',      'optionType' => 'custom', 'type' => 'decimal',    'length' => '0');
$lang->convert->jira->buildinFields['timespent']            = array('name'=> 'Cost',            'jiraField' => 'timespent',            'control' => 'decimal',      'optionType' => 'custom', 'type' => 'decimal',    'length' => '0');
$lang->convert->jira->buildinFields['desc']                 = array('name'=> 'Description',     'jiraField' => 'description',          'control' => 'richtext',     'optionType' => 'custom', 'type' => 'mediumtext', 'length' => '0', 'buildin' => false);
