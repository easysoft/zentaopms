<?php
/**
 * The upgrade module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: en.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->upgrade->common          = 'Upgrade';
$lang->upgrade->start           = 'Start';
$lang->upgrade->result          = 'Ergebnis';
$lang->upgrade->fail            = 'Fehlgeschlagen';
$lang->upgrade->successTip      = 'Successed';
$lang->upgrade->success         = 'Erfolgreich';
$lang->upgrade->tohome          = 'Home';
$lang->upgrade->license         = 'Zentao PMS nutzt jetzt Z PUBLIC LICENSE(ZPL) 1.2.';
$lang->upgrade->warnning        = 'Warnung!';
$lang->upgrade->checkExtension  = 'Erweiterung prüfen';
$lang->upgrade->consistency     = 'Check Consistency';
$lang->upgrade->warnningContent = <<<EOT
<p>The upgrade requires high database privileges, please use the root user.<br>
   Please backup your database before updating ZenTao!</p>
<pre>
1. Use phpMyAdmin to backup.
2. Use mysqlCommand to backup.
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span>
   Change the red text into corresponding Username and Database name.
   e.g. mysqldump -u root -p zentao > zentao.bak
</pre>
EOT;

$lang->upgrade->createFileWinCMD   = 'Öffnen Sie ein Konsolenfenster und rufen Sie <strong style="color:#ed980f">echo > %s</strong> auf';
$lang->upgrade->createFileLinuxCMD = 'Ausführen in der Shell: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>Please complete the following actions</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>Or delete "<strong style="color:#ed980f">%s</strong>" and create <strong style="color:#ed980f">ok.txt</strong> and leave it blank.</li>
                                      </ul>
                                      <p><strong style="color:red">I have read and done as instructed above. <a href="upgrade.php">Continue upgrading.</a></strong></p>';

$lang->upgrade->selectVersion  = 'Version auswählen';
$lang->upgrade->continue       = 'Weiter';
$lang->upgrade->noteVersion    = "Wählen Sie eine kompatible Version oder es droht Datenverlust.";
$lang->upgrade->fromVersion    = 'Von Version';
$lang->upgrade->toVersion      = 'Upgrade nach Version';
$lang->upgrade->confirm        = 'SQL Bestätigen';
$lang->upgrade->sureExecute    = 'Ausführen';
$lang->upgrade->upgradingTips  = 'The upgrade is in progress, please be patient. Do not refresh the page, blackout, or turn off your computer!';
$lang->upgrade->forbiddenExt   = 'Die Erweiterung ist nicht kompatibel mit der Upgradeversion. Sie wurde deaktiviert:';
$lang->upgrade->updateFile     = 'Updateinformation wurden hinzugefügt.';
$lang->upgrade->noticeSQL      = 'Your database is inconsistent with the standard and it failed to fix it. Please run the following SQL and refresh.';
$lang->upgrade->afterDeleted   = 'File is not deleted. Please refresh after you delete it.';
$lang->upgrade->mergeProgram   = 'Data Merge';
$lang->upgrade->mergeTips      = 'Data Migration Tips';
$lang->upgrade->toPMS15Guide   = 'ZenTao open source version 15 upgrade';
$lang->upgrade->toPRO10Guide   = 'ZenTao profession version 10 upgrade';
$lang->upgrade->toBIZ5Guide    = 'ZenTao enterprise version 5 upgrade';
$lang->upgrade->toMAXGuide     = 'ZenTao ultimate version upgrade';
$lang->upgrade->to15Desc       = <<<EOD
<p>ZenTao version 15.0 has major upgrade of features, including:</p>
<p><strong>1. Add the concept of Program</strong></p>
<p>A program is a collection of interrelated and coordinated projects. It is at the highest level and belongs to the concept of a strategic level. It has multi-level project management, helping managers to form strategic directions and allocate resources at a macro level.</p>
<p><strong>2. Clarify the concept of Product and Project</strong></p>
<p>Product defines what should be done and focuses on requirement management. Project defines how to do it and focuses on finishing tasks of the project within the specified time, budget and quality objectives. A project can be done in Agile or Waterfall, which is the management of the campaign level.</p> <p><strong>3. Add the concept of Project Model</strong></p>
<p>The new version 15.0 adds a Waterfall model (available in ZenTao Max) to help the project team choose the right project management method according to their needs. Kanban model will be coming soon.</p> <p><strong>4. Add the concept of Execution</strong></p>
<p>In Version 15.0, depending on the management model, a project can have multiple iterations/sprints/ phases which are Executions in ZenTao. Through executions, the project tasks are completed and the final outcome is delivered.</p> <p><strong>5. Adjust the Navigation</strong></p>
<p>The top level navigation is moved to the left, and multi-application switching is added as new UI/UX.</p>
<br/>
<p>You can try the online demo before you decide to enable new features: <a class='text-info' href='http://zentaomax.demo.zentao.net' target='_blank'>New Features Online Demo</a></p>
<p>You can also download an introduction PPT to help you understand it：<a class='text-info' href='' target='_blank'> New Features Introduction PPT</a></p>
<video src="https://dl.cnezsoft.com/vedio/zentaoconcepteng0716.mp4" width="100%" controls ="controls"></video>
<p style="text-align:center"><small>ZenTao Version 15 Introduction</small></p>
<br/>
<p><strong>How do you like to use ZenTao?</strong></p>
EOD;




$lang->upgrade->mergeProgramDesc = <<<EOD
<p>Next, ZenTao will migrate the existing data of {$lang->productCommon} and {$lang->projectCommon} to Program and Project. It will be one of the followings:</p><br />
<h4>1. Manage {$lang->productCommon} and {$lang->projectCommon} by {$lang->productCommon} Line </h4>
<p>Migrate the data of {$lang->productCommon} and {$lang->projectCommon} by {$lang->productCommon} Line to a Program. You can also migrate it separately.</p>
<h4>2. Manage {$lang->projectCommon} by {$lang->productCommon}</h4>
<p>You can migrate the data of several {$lang->productCommon}s and {$lang->projectCommon}s to one Program. Or select {$lang->projectCommon}s of a {$lang->productCommon} and {$lang->productCommon} to a Program.</p>
<h4>2. Independent {$lang->projectCommon}</h4>
<p>Choose several {$lang->projectCommon}s and migrate them to one Program, or migrate them separately.</p>
<h4>4. Link {$lang->projectCommon}s of several {$lang->productCommon}s</h4>
<p>After selecting a historical {$lang->projectCommon} as a project upgrade, the user can select the program to which this project belongs or create a new program.</p>
<p>After selecting a historical {$lang->projectCommon} as the iterative upgrade, the user can select the project and program to which the execution belongs or create a new project and program.</p>
EOD;

$lang->upgrade->to15Mode['classic'] = 'Keep the classic mode';
$lang->upgrade->to15Mode['new']     = 'Use the program mode';

$lang->upgrade->selectedModeTips['classic'] = 'You can also switch to the Program mode in the Admin-Custom-Custom later.';
$lang->upgrade->selectedModeTips['new']     = 'Switching to the program management mode requires merging the previous data, and the system will guide you to complete this operation.';

$lang->upgrade->line           = 'Product Line';
$lang->upgrade->allLines       = "All Product Lines";
$lang->upgrade->program        = 'Merge Project';
$lang->upgrade->existProgram   = 'Existing programs';
$lang->upgrade->existProject   = 'Existing projects';
$lang->upgrade->existLine      = 'Existing' . $lang->productCommon . ' lines';
$lang->upgrade->product        = $lang->productCommon;
$lang->upgrade->project        = 'Iteration';
$lang->upgrade->repo           = 'Repo';
$lang->upgrade->mergeRepo      = 'Merge Repo';
$lang->upgrade->setProgram     = 'Set the project to which the program belongs';
$lang->upgrade->dataMethod     = 'Data migration method';
$lang->upgrade->begin          = 'Begin Date';
$lang->upgrade->end            = 'End Date';
$lang->upgrade->selectProject  = 'The target project';
$lang->upgrade->programName    = 'Program Name';
$lang->upgrade->projectName    = 'Project Name';
$lang->upgrade->compatibleEXT  = 'Extension mechanism compatible';
$lang->upgrade->fileName       = 'File Name';
$lang->upgrade->next           = 'Next';

$lang->upgrade->newProgram         = 'Create';
$lang->upgrade->editedName         = 'New Name';
$lang->upgrade->projectEmpty       = 'Project must be not empty.';
$lang->upgrade->mergeSummary       = "Dear users, there are %s {$lang->productCommon} and %s {$lang->projectCommon} in your system waiting for Migration. By System Calculation, we recommend your migration plan as follows, you can also adjust according to your own situation:";
$lang->upgrade->mergeByProductLine = "PRODUCTLINE-BASED {$lang->projectCommon}: Consolidate the entire {$lang->productCommon} line and the {$lang->productCommon} and {$lang->projectCommon} below it into one large project.";
$lang->upgrade->mergeByProduct     = "PRODUCT-BASED {$lang->projectCommon}: You can select multiple {$lang->productCommon} and their lower {$lang->projectCommon} to merge into a large project, or you can select a {$lang->productCommon} to merge its lower {$lang->projectCommon} into a larger project";
$lang->upgrade->mergeByProject     = "Independent {$lang->projectCommon}: You can select several {$lang->projectCommon} and merge them into one large project, or merge them independently";
$lang->upgrade->mergeByMoreLink    = "{$lang->projectCommon} that relates multiple {$lang->productCommon}: You can select several {$lang->projectCommon} and merge them into one large project, or merge them independently.";
$lang->upgrade->mergeRepoTips      = "Merge the selected version library under the selected product.";
$lang->upgrade->needBuild4Add      = 'Full text retrieval has been added in this upgrad. Please create an index.';
$lang->upgrade->needChangeEngine   = 'The table engine needs to be replaced in this upgrade, Please go [Admin->System->TableEngine] page to replace engine.';
$lang->upgrade->errorEngineInnodb  = 'Your MySQL version is too low to support InnoDB data table engine. Please modify it to MyISAM and try again.';
$lang->upgrade->duplicateProject   = "Project name in the same program cannot be duplicate. Please adjust the duplicate names.";
$lang->upgrade->upgradeTips        = "Historically deleted data cannot be upgraded, and restoration is not supported after the upgrade. Please be aware.";
$lang->upgrade->moveEXTFileFail    = 'The migration file failed, please execute the above command and refresh!';
$lang->upgrade->deleteDirTip       = 'After the upgrade, the following folders will affect the use of system functions, please delete them.';
$lang->upgrade->errorNoProduct     = "Select the {$lang->productCommon} that you want to merge.";
$lang->upgrade->errorNoExecution   = "Select the {$lang->projectCommon} that you want to merge.";
$lang->upgrade->moveExtFileTip     = <<<EOT
<p>The new version will be compatible with the extension mechanism of the historical customization/plug-in. You need to migrate the customization/plug-in related files to extension/custom, otherwise the customization/plug-in function will not be available.</p>
<p>Please confirm whether the system has been customized/plug-in. If no customization/plug-in has been done, you can uncheck the following files; Whether you have done customization/plug-in, you can also keep the file checked.</p>
EOT;

$lang->upgrade->projectType['project']   = "Upgrade the historical {$lang->projectCommon} as a project";
$lang->upgrade->projectType['execution'] = "Upgrade the historical {$lang->projectCommon} as an execution";

$lang->upgrade->createProjectTip = <<<EOT
<p>After the upgrade, the existing {$lang->projectCommon} will be Project in the new version.</p>
<p>ZenTao will create an item in Execute with the same name of {$lang->projectCommon} according to the data in {$lang->projectCommon}, and move the tasks, stories, and bugs in {$lang->projectCommon} to it.</p>
EOT;

$lang->upgrade->createExecutionTip = <<<EOT
<p>ZenTao will upgrade existing {$lang->projectCommon} as execution.</p>
<p>After the upgrade, the data of existing {$lang->projectCommon} will be in a Project - Execute of the new version .</p>
EOT;

include dirname(__FILE__) . '/version.php';

$lang->upgrade->recoveryActions = new stdclass();
$lang->upgrade->recoveryActions->cancel = 'Cancel';
$lang->upgrade->recoveryActions->review = 'Review';
