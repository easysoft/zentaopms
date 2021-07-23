<?php
/**
 * The upgrade module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: en.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->upgrade->common          = 'Update';
$lang->upgrade->start           = 'Start';
$lang->upgrade->result          = 'Result';
$lang->upgrade->fail            = 'Failed';
$lang->upgrade->successTip      = 'Successed';
$lang->upgrade->success         = "<p><i class='icon icon-check-circle'></i></p><p>Congratulations!</p><p>Your ZenTao is updated.</p>";
$lang->upgrade->tohome          = 'Visit ZenTao';
$lang->upgrade->license         = 'ZenTao is under Z PUBLIC LICENSE(ZPL) 1.2.';
$lang->upgrade->warnning        = 'Warning!';
$lang->upgrade->checkExtension  = 'Check Extensions';
$lang->upgrade->consistency     = 'Check Consistency';
$lang->upgrade->warnningContent = <<<EOT
<p>Please backup your database before updating ZenTao!</p>
<pre>
1. Use phpMyAdmin to backup.
2. Use mysqlCommand to backup.
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span>
   Change the red text into corresponding Username and Database name.
   e.g. mysqldump -u root -p zentao >zentao.bak
</pre>
EOT;

$lang->upgrade->createFileWinCMD   = 'Open command line and execute <strong style="color:#ed980f">echo > %s</strong>';
$lang->upgrade->createFileLinuxCMD = 'Execute command line: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>Please complete the following actions</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>Or delete "<strong style="color:#ed980f">%s</strong>" and create <strong style="color:#ed980f">ok.txt</strong> and leave it blank.</li>
                                      </ul>
                                      <p><strong style="color:red">I have read and done as instructed above. <a href="upgrade.php">Continue upgrading.</a></strong></p>';

$lang->upgrade->selectVersion = 'Version';
$lang->upgrade->continue      = 'Continue';
$lang->upgrade->noteVersion   = "Select the compatible version, or it might cause data loss.";
$lang->upgrade->fromVersion   = 'From';
$lang->upgrade->toVersion     = 'To';
$lang->upgrade->confirm       = 'Confirm SQL';
$lang->upgrade->sureExecute   = 'Execute';
$lang->upgrade->forbiddenExt  = 'The extension is incompatible with the version. It has been deactivated:';
$lang->upgrade->updateFile    = 'File information has to be updated.';
$lang->upgrade->noticeSQL     = 'Your database is inconsistent with the standard and it failed to fix it. Please run the following SQL and refresh.';
$lang->upgrade->afterDeleted  = 'File is not deleted. Please refresh after you delete it.';
$lang->upgrade->mergeProgram  = 'Data Merge';
$lang->upgrade->mergeTips     = 'Data Migration Tips';
$lang->upgrade->toPMS15Guide  = 'ZenTao open source version 15 upgrade';
$lang->upgrade->toPRO10Guide  = 'ZenTao profession version 10 upgrade';
$lang->upgrade->toBIZ5Guide   = 'ZenTao enterprise version 5 upgrade';
$lang->upgrade->toMAXGuide    = 'ZenTao ultimate version upgrade';
$lang->upgrade->to15Desc      = <<<EOD
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
<p style="text-align:center"><small>ZenTao Version 15.0 Introduction</small></p>
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
<p>You can set {$lang->projectCommon}s as one new project.</p>
EOD;

$lang->upgrade->to15Mode['classic'] = 'Keep the classic mode';
$lang->upgrade->to15Mode['new']     = 'Use the program mode';

$lang->upgrade->selectedModeTips['classic'] = 'You can also switch to the Program mode in the Admin-Custom-Custom later.';
$lang->upgrade->selectedModeTips['new']     = 'Switching to the program management mode requires merging the previous data, and the system will guide you to complete this operation.';

$lang->upgrade->line          = 'Product Line';
$lang->upgrade->allLines      = "All Product Lines";
$lang->upgrade->program       = 'Merge Project';
$lang->upgrade->existProgram  = 'Existing programs';
$lang->upgrade->existProject  = 'Existing projects';
$lang->upgrade->existLine     = 'Existing' . $lang->productCommon . ' lines';
$lang->upgrade->product       = $lang->productCommon;
$lang->upgrade->project       = 'Iteration';
$lang->upgrade->repo          = 'Repo';
$lang->upgrade->mergeRepo     = 'Merge Repo';
$lang->upgrade->setProgram    = 'Set the project to which the program belongs';
$lang->upgrade->dataMethod    = 'Data migration method';
$lang->upgrade->begin         = 'Begin Date';
$lang->upgrade->end           = 'End Date';
$lang->upgrade->selectProject = 'The target project';
$lang->upgrade->projectName   = 'Project Name';

$lang->upgrade->newProgram         = 'Create';
$lang->upgrade->projectEmpty       = 'Project must be not empty.';
$lang->upgrade->mergeSummary       = "Dear users, there are %s {$lang->productCommon} and %s {$lang->projectCommon} in your system waiting for Migration. By System Calculation, we recommend your migration plan as follows, you can also adjust according to your own situation:";
$lang->upgrade->mergeByProductLine = "PRODUCTLINE-BASED {$lang->projectCommon}: Consolidate the entire {$lang->productCommon} line and the {$lang->productCommon} and {$lang->projectCommon} below it into one large project.";
$lang->upgrade->mergeByProduct     = "PRODUCT-BASED {$lang->projectCommon}: You can select multiple {$lang->productCommon} and their lower {$lang->projectCommon} to merge into a large project, or you can select a {$lang->productCommon} to merge its lower {$lang->projectCommon} into a larger project";
$lang->upgrade->mergeByProject     = "Independent {$lang->projectCommon}: You can select several {$lang->projectCommon} and merge them into one large project, or merge them independently";
$lang->upgrade->mergeByMoreLink    = "{$lang->projectCommon} that relates multiple {$lang->productCommon}: select which project the {$lang->projectCommon} belongs to.";
$lang->upgrade->mergeRepoTips      = "Merge the selected version library under the selected product.";
$lang->upgrade->needBuild4Add      = 'Full text retrieval has been added in this upgrade. Need create index. Please go [Admin->System->BuildIndex] page to build index.';

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
