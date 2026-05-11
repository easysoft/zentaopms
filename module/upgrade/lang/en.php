<?php
/**
 * The upgrade module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: en.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        https://www.zentao.net
 */
global $config;
$lang->upgrade->common          = 'Upgrade';
$lang->upgrade->welcome         = 'Welcome to ZenTao Upgrade';
$lang->upgrade->execute         = 'Version Upgrade';
$lang->upgrade->versionTips     = 'Upgrading to';
$lang->upgrade->changeTips      = '%s data changes';
$lang->upgrade->progress        = 'Progress';
$lang->upgrade->executedChanges = "Executed: <span id='executedCount'>0</span> / %s";
$lang->upgrade->start           = 'Start';
$lang->upgrade->result          = 'Update Status';
$lang->upgrade->fail            = 'Upgrade failed. The current version of ZenTao is ';
$lang->upgrade->successTip      = 'Successed';
$lang->upgrade->success         = "<p>Congratulations! Your ZenTao has been successfully upgraded.</p>";
$lang->upgrade->tohome          = 'Go to ZenTao';
$lang->upgrade->notice          = 'Notice';
$lang->upgrade->checkExtension  = 'Check Extensions';
$lang->upgrade->consistency     = 'Consistency Check';
$lang->upgrade->backupNotice    = <<<EOT
<p>Elevated database privileges are required. Please use an administrator account.</p>
<p>Warning: Back up your database before proceeding to prevent potential data loss.</p>
<pre class='leading-6 mt-1 p-3'>
1. Backups can be performed via GUI management tools.
2. Use the DIsql tool for backup.
$> BACKUP DATABASE BACKUPSET <span class='font-bold text-danger'>'filename'</span>;
The backup set directory "filename" will be generated in the default backup path upon completion.
The default path is defined by BAK_PATH in dm.ini. If BAK_PATH is not configured, the bak directory under SYSTEM_PATH is used by default.
This is the simplest backup command. For advanced options, please refer to the online backup syntax documentation.
</pre>
EOT;

if($config->db->driver == 'dm')
{
    $lang->upgrade->backupNotice = <<<EOT
<p>The upgrade requires high database privileges, please use the root user.</p>
<p>Please backup your database before updating ZenTao!</p>
<pre class='leading-6 mt-1 p-3'>
1. It can be backed up by graphical client tools.
2. Use DIsql tool to back up data.
   $> BACKUP DATABASE BACKUPSET <span class='font-bold text-danger'>'filename'</span>;
   After the statement is executed, a backup set directory named "filename" is generated in the default backup path.
   The default backup path is the path configured with BAK_PATH in dm.ini. If BAK_PATH is not configured, bak in SYSTEM_PATH is used by default.
   This is the simplest database backup statement,To set additional backup options, you need to understand the syntax of the online backup database.
</pre>
EOT;
}

$lang->upgrade->confirmBackup      = 'I have backed up the database';
$lang->upgrade->setStatusFileTitle = 'Please complete the following before upgrading';
$lang->upgrade->createWinFile      = 'Open the terminal and run: <span id="command" class="font-bold text-danger">echo > %s</span>';
$lang->upgrade->createLinuxFile    = 'Run <span id="command" class="font-bold text-danger">touch %s</span> in the terminal.';
$lang->upgrade->deleteStatusFile   = 'Alternatively, delete <span class="font-bold text-danger">%s</span> and create a new <span class="font-bold text-danger">ok.txt</span> file (leave it empty).';
$lang->upgrade->confirmStatusFile  = 'I have read and followed the instructions above.';
$lang->upgrade->safeDeleteFile     = 'For system security, the files need to be deleted.';

$lang->upgrade->selectVersion = 'Select Version';
$lang->upgrade->copyCommand   = 'Copy Command';
$lang->upgrade->copySuccess   = 'Copied';
$lang->upgrade->copyFail      = 'Copying not supported by your browser. Please copy manually.';
$lang->upgrade->continue      = 'Continue Upgrade';
$lang->upgrade->noteVersion   = "Make sure to select the correct version, otherwise data loss may occur.";
$lang->upgrade->fromVersion   = 'Current Version';
$lang->upgrade->toVersion     = 'Upgrade to';
$lang->upgrade->confirm       = 'Confirm SQL';
$lang->upgrade->sureExecute   = 'Execute';
$lang->upgrade->upgradingTips = 'Upgrading in progress. Please wait. Do not refresh, power off, or shut down!';
$lang->upgrade->forbiddenExt  = 'The following extensions are incompatible with the new version and have been automatically disabled:';
$lang->upgrade->updateFile    = 'Attachment information update required.';
$lang->upgrade->showSQLLog    = 'Database inconsistencies detected. Attempting to fix. Below are the SQL statements for repair:';
$lang->upgrade->noticeErrSQL  = 'Database inconsistencies detected, and automatic repair failed. Please run the following SQL statements manually, then refresh the page to re-check.';
$lang->upgrade->execCommand   = 'Please run the above command on the server, then refresh the page.';
$lang->upgrade->afterExec     = 'Please modify the database manually based on the error messages above, then refresh the page.';
$lang->upgrade->mergeProgram  = 'Data import';
$lang->upgrade->mergeTips     = 'Data Migration Tips';
$lang->upgrade->toPMS15Guide  = 'ZenTao Open Source v15 Upgrade';
$lang->upgrade->toPRO10Guide  = 'ZenTao profession v10 Upgrade ';
$lang->upgrade->toBIZ5Guide   = 'ZenTao enterprise v5 Upgrade ';
$lang->upgrade->toMAXGuide    = 'ZenTao ultimate version upgrade';

$lang->upgrade->line            = 'Product Line';
$lang->upgrade->allLines        = "All Product Lines";
$lang->upgrade->program         = 'Target Program & Project';
$lang->upgrade->existProgram    = 'Existing programs';
$lang->upgrade->existProject    = 'Existing projects';
$lang->upgrade->existLine       = 'Existing product lines';
$lang->upgrade->product         = $lang->productCommon;
$lang->upgrade->project         = 'Iteration';
$lang->upgrade->repo            = 'Repo';
$lang->upgrade->mergeRepo       = 'Merge Repo';
$lang->upgrade->setProgram      = 'Assign Project to Program';
$lang->upgrade->setProject      = "Assign {$lang->executionCommon} to Project";
$lang->upgrade->dataMethod      = 'Data migration method';
$lang->upgrade->selectMergeMode = 'Please select a data merging method';
$lang->upgrade->mergeMode       = 'Data Merging Method:';
$lang->upgrade->begin           = 'Start on';
$lang->upgrade->end             = 'End on';
$lang->upgrade->unknownDate     = 'Unscheduled Projects';
$lang->upgrade->selectProject   = 'The target project';
$lang->upgrade->programName     = 'Program Name';
$lang->upgrade->projectName     = 'Project Name';
$lang->upgrade->projectManage   = 'Project Manage';
$lang->upgrade->compatibleEXT   = 'Extension Compatibility';
$lang->upgrade->fileName        = 'File Name';
$lang->upgrade->list            = ' List';
$lang->upgrade->next            = 'Next';
$lang->upgrade->back            = 'Back';

$lang->upgrade->upgradeDocs     = 'Upgrade Document Data';
$lang->upgrade->upgradingDocs   = 'Upgrading documents, please wait...';
$lang->upgrade->upgradeDocsTip  = 'Found %s document-related items to upgrade';

$lang->upgrade->upgradeDocTemplates    = 'Upgrade Doc Template Data';
$lang->upgrade->upgradingDocTemplates  = 'Upgrading document templates, please wait...';
$lang->upgrade->upgradeDocTemplatesTip = 'Upgrading historical template data. You can view and manage them in the Template Plaza afterward.';

$lang->upgrade->weeklyReportTitle        = 'Week % s (% s ~% s)';
$lang->upgrade->milestoneTitle           = 'Milestone Report';
$lang->upgrade->upgradeProjectReports    = "Upgrade {$lang->projectCommon} Report Data";
$lang->upgrade->upgradingProjectReports  = "Upgrading {$lang->projectCommon} report data, please wait...";
$lang->upgrade->upgradeProjectReportsTip = "Found %s {$lang->projectCommon} report-related items to upgrade";

$lang->upgrade->newProgram        = 'Create';
$lang->upgrade->editedName        = 'Renamed To';
$lang->upgrade->projectEmpty      = 'The project cannot be empty.';
$lang->upgrade->mergeSummary      = "Dear user, there are %s items in your system waiting to be migrated.";
$lang->upgrade->productCount      = "%s {$lang->productCommon}";
$lang->upgrade->projectCount      = "%s {$lang->projectCommon}";
$lang->upgrade->mergeByProject    = "There are 2 data migration methods available. If your historical {$lang->projectCommon} are long-term, we recommend upgrading them as Projects.</br>If they are short-term, we recommend upgrading them as {$lang->executionCommon}.";
$lang->upgrade->mergeRepoTips     = "Merge selected repositories into the selected product.";
$lang->upgrade->needBuild4Add     = 'This upgrade requires new indexes. Please go to [Admin -> System -> Rebuild Index] to re-create them.';
$lang->upgrade->needChangeEngine  = 'Database engine updates are required for this upgrade. Please change it at [Admin -> System -> Table Engine].';
$lang->upgrade->errorEngineInnodb = 'The current database does not support the InnoDB engine. Please switch to MyISAM and try again.';
$lang->upgrade->duplicateProject  = "Project names must be unique within a program. Please rename any duplicate projects.";
$lang->upgrade->upgradeTips       = "Deleted historical data will not be migrated and cannot be restored after the upgrade.";
$lang->upgrade->moveEXTFileFail   = 'File migration failed. Please run the command above and refresh.';
$lang->upgrade->deleteDirTip      = 'The following folders will interfere with system functions after the upgrade. Please delete them.';
$lang->upgrade->errorNoProduct    = "Please select the {$lang->productCommon} to be merged.";
$lang->upgrade->errorNoExecution  = "Please select the {$lang->projectCommon} to be merged.";
$lang->upgrade->moveExtFileTip    = <<<EOT
<p>The new version will apply extension compatibility to historical customizations and plugins. To ensure these functions remain active, the related files must be migrated to extension/custom; otherwise, they will no longer work.</p>
<p>Please confirm if your system has any customizations or plugins. If not, you can uncheck the files below. If you are unsure, we recommend keeping them checked to avoid any issues.</p>
EOT;

$lang->upgrade->projectType['project']   = "Upgrade historical {$lang->projectCommon} as Projects";
$lang->upgrade->projectType['execution'] = "Upgrade historical {$lang->projectCommon} as {$lang->executionCommon}";

$lang->upgrade->createProjectTip = <<<EOT
<p>After the upgrade, each historical {$lang->projectCommon} will map directly to a new Project.</p>
<p>The system will create an {$lang->executionCommon} with the same name for each historical {$lang->projectCommon}. Tasks, stories, bugs, and other data will then be migrated into these corresponding {$lang->executionCommon}.</p>
EOT;

$lang->upgrade->createExecutionTip = <<<EOT
<p>The system will upgrade historical {$lang->projectCommon} as {$lang->executionCommon}.</p>
<p>After the upgrade, the data from historical {$lang->projectCommon} will be mapped to {$lang->executionCommon} under the new Projects.</p>
EOT;

$lang->upgrade->mergeModes = array();
$lang->upgrade->mergeModes['project']   = "Auto-merge data: Upgrade historical {$lang->projectCommon} as Projects";
$lang->upgrade->mergeModes['execution'] = "Auto-merge data: Upgrade historical {$lang->projectCommon} as {$lang->executionCommon}";
$lang->upgrade->mergeModes['manually']  = 'Manually merge data';

$lang->upgrade->mergeProjectTip   = "Historical {$lang->projectCommon} will be synchronized directly to the new Projects. Meanwhile, the system will create an {$lang->executionCommon} with the same name for each, and migrate all tasks, stories, bugs, and other data into the corresponding {$lang->executionCommon}.";
$lang->upgrade->mergeExecutionTip = "The system will automatically create Projects by year and merge historical {$lang->projectCommon} data into the corresponding Projects.";
$lang->upgrade->createProgramTip  = "Meanwhile, a default Program will be created to contain all {$lang->projectCommon}.";
$lang->upgrade->mergeManuallyTip  = 'You can manually select the data merging method.';

$lang->upgrade->defaultGroup = 'Default Group';

include dirname(__FILE__) . '/version.php';

$lang->upgrade->recoveryActions = new stdclass();
$lang->upgrade->recoveryActions->cancel = 'Cancel';
$lang->upgrade->recoveryActions->review = 'Review';

$lang->upgrade->remark     = 'Notes';
$lang->upgrade->remarkDesc = 'You can also switch the mode in Admin -> System ->Mode.';

$lang->upgrade->upgradingTip = 'System upgrading, please wait...';

$lang->upgrade->addTraincoursePrivTips = "To help users better learn project management, we have granted all privilege groups access to Academy courses and practice libraries by default. If you do not need this feature, you can disable it in Admin -> System -> Feature Toggle.";

$lang->upgrade->storyStageList['']           = '';
$lang->upgrade->storyStageList['wait']       = 'Waiting';
$lang->upgrade->storyStageList['planned']    = 'Planned';
$lang->upgrade->storyStageList['projected']  = 'Projected';
$lang->upgrade->storyStageList['designing']  = 'Designing';
$lang->upgrade->storyStageList['designed']   = 'Designed';
$lang->upgrade->storyStageList['developing'] = 'Developing';
$lang->upgrade->storyStageList['developed']  = 'Developed';
$lang->upgrade->storyStageList['testing']    = 'Testing';
$lang->upgrade->storyStageList['tested']     = 'Tested';
$lang->upgrade->storyStageList['verified']   = 'Accepted';
$lang->upgrade->storyStageList['rejected']   = 'Acceptance Failed';
$lang->upgrade->storyStageList['delivering'] = 'Delivering';
$lang->upgrade->storyStageList['delivered']  = 'Delivered';
$lang->upgrade->storyStageList['released']   = 'Released';
$lang->upgrade->storyStageList['closed']     = 'Closed';

$lang->upgrade->flowFields['program']   = 'Program';
$lang->upgrade->flowFields['product']   = 'Product';
$lang->upgrade->flowFields['project']   = 'Project';
$lang->upgrade->flowFields['execution'] = 'Execution';

$lang->upgrade->defaultCharterApprovalFlow = new stdclass();
$lang->upgrade->defaultCharterApprovalFlow->projectApproval = new stdclass();
$lang->upgrade->defaultCharterApprovalFlow->projectApproval->title = 'Project Initiation Workflow';
$lang->upgrade->defaultCharterApprovalFlow->projectApproval->desc  = 'Design the approval process for project initiation requests.';

$lang->upgrade->defaultCharterApprovalFlow->completionApproval = new stdclass();
$lang->upgrade->defaultCharterApprovalFlow->completionApproval->title = 'Project Closure Workflow';
$lang->upgrade->defaultCharterApprovalFlow->completionApproval->desc  = 'Design the approval process for project closure requests.';

$lang->upgrade->defaultCharterApprovalFlow->cancelProjectApproval = new stdclass();
$lang->upgrade->defaultCharterApprovalFlow->cancelProjectApproval->title = 'Project Cancellation Workflow';
$lang->upgrade->defaultCharterApprovalFlow->cancelProjectApproval->desc  = 'Design the approval process for canceling project initiations.';

$lang->upgrade->defaultCharterApprovalFlow->activateProjectApproval = new stdclass();
$lang->upgrade->defaultCharterApprovalFlow->activateProjectApproval->title = 'Project Reactivation Workflow';
$lang->upgrade->defaultCharterApprovalFlow->activateProjectApproval->desc  = 'Design the approval process for reactivating project initiations.';

$lang->upgrade->deliverableModule['plan']   = 'Plan';
$lang->upgrade->deliverableModule['story']  = 'Story';
$lang->upgrade->deliverableModule['design'] = 'Design';
$lang->upgrade->deliverableModule['test']   = 'Test';
$lang->upgrade->deliverableModule['other']  = 'Other';

$lang->upgrade->reviewObjectList['PP']         = 'Project Plan';
$lang->upgrade->reviewObjectList['QAP']        = 'Quality Assurance Plan';
$lang->upgrade->reviewObjectList['CMP']        = 'Configuration Management Plan';
$lang->upgrade->reviewObjectList['ITP']        = 'Integration Test Plan';
$lang->upgrade->reviewObjectList['ERS']        = 'Epic Statement';
$lang->upgrade->reviewObjectList['URS']        = 'Feature Statement';
$lang->upgrade->reviewObjectList['SRS']        = 'Project Requirements Specification';
$lang->upgrade->reviewObjectList['HLDS']       = 'High Level Design Statement';
$lang->upgrade->reviewObjectList['DDS']        = 'Detailed Design Statement';
$lang->upgrade->reviewObjectList['DBDS']       = 'Database Design Statement';
$lang->upgrade->reviewObjectList['ADS']        = 'Interface Design Statement';
$lang->upgrade->reviewObjectList['Code']       = 'Code';
$lang->upgrade->reviewObjectList['intergrate'] = 'Integrate Test Cases';
$lang->upgrade->reviewObjectList['STP']        = 'System Test Plan';
$lang->upgrade->reviewObjectList['system']     = 'System Test Cases';
$lang->upgrade->reviewObjectList['UM']         = 'User Manual';

$lang->upgrade->baselineReview = array();
$lang->upgrade->baselineReview['baseline'] = 'Baseline Review';
$lang->upgrade->baselineReview['change']   = 'Project Change Review';

$lang->upgrade->changeModes = [];
$lang->upgrade->changeModes['create'] = 'Add';
$lang->upgrade->changeModes['update'] = 'Update';
$lang->upgrade->changeModes['delete'] = 'Delete';

$lang->upgrade->changeActions = [];
$lang->upgrade->changeActions['createView']  = 'Create database view %VIEW%';
$lang->upgrade->changeActions['dropView']    = 'Drop database view %VIEW%';
$lang->upgrade->changeActions['createTable'] = 'Create database table %TABLE%';
$lang->upgrade->changeActions['dropTable']   = 'Drop database table %TABLE%';
$lang->upgrade->changeActions['renameTable'] = 'Rename database table %OLD% to %NEW%';
$lang->upgrade->changeActions['addField']    = 'Add field %FIELD% to database table %TABLE%';
$lang->upgrade->changeActions['modifyField'] = 'Modify field %FIELD% in database table %TABLE%';
$lang->upgrade->changeActions['dropField']   = 'Drop field %FIELD% from database table %TABLE%';
$lang->upgrade->changeActions['renameField'] = 'Rename field %OLD% in table %TABLE% to %NEW%';
$lang->upgrade->changeActions['createIndex'] = 'Add index %INDEX% to database table %TABLE%';
$lang->upgrade->changeActions['dropIndex']   = 'Drop index %INDEX% from database table %TABLE%';
$lang->upgrade->changeActions['insertValue'] = 'Insert data into database table %TABLE%';
$lang->upgrade->changeActions['updateValue'] = 'Update data in database table %TABLE%';
$lang->upgrade->changeActions['deleteValue'] = 'Delete data from database table %TABLE%';
$lang->upgrade->changeActions['method']      = 'Execute method %METHOD% of module %MODULE%';
$lang->upgrade->changeActions['other']       = 'Other operations';