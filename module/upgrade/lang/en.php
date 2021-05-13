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
$lang->upgrade->toPMS15Guide  = 'ZenTao open source version 15.0.beta1 upgrade';
$lang->upgrade->toPRO10Guide  = 'ZenTao profession version 10.0.rc1 upgrade';
$lang->upgrade->toBIZ5Guide   = 'ZenTao enterprise version 5.0.rc1 upgrade';
$lang->upgrade->toMAX5Guide   = 'ZenTao ultimate version upgrade';
$lang->upgrade->to15Desc      = <<<EOD
<p>Dear users, ZenTao has made adjustments to navigation and concepts since version 15. The main changes are as follows:</p>
<ol>
<p><li>Added the concept of program. A program set can include multiple products and multiple projects.</li></p>
<p><li>Subdivided the concept of project and iteration, a project can contain multiple iterations.</li></p>
<p><li>The navigation adds a left menu and supports multi-page operations.</li></p>
</ol>
<br/>
<p>You can experience the latest version of the function online to decide whether to enable the mode: <a class='text-info' href='http://zentaomax.demo.zentao.net' target='_blank'>Demo</a></p>
</br>
<p><strong>How do you plan to use the new version of ZenTao?</strong></p>
EOD;

$lang->upgrade->mergeProgramDesc = <<<EOD
<p>Next, we will migrate the previous historical product and iteration data to the project set and under the project, with the following scenario for migration.</p><br />
<h4>Option 1: Product and iteration organized by product line </h4>
<p>It is possible to migrate the entire product line and its following products and iterations into one project set and project, although you can also migrate them separately as needed.</p>
<h4>Option 2: Iteration of product-based organizations </h4>
<p>You can select multiple products and the iterations below them to migrate to a project set and project, or you can select a particular product and the iterations below it to migrate to a project set and project.</p>
<h4>Option 3: Independent iterations </h4>
<p>Several iterations can be selected to migrate to a single project set, or independently.</p>
<h4>Option 4: Iterations linked to multiple products.</h4>
<p>These iterations can be selected to fall under a new project.</p>
EOD;

$lang->upgrade->to15Mode['classic'] = 'Keep the old version';
$lang->upgrade->to15Mode['new']     = 'New program management mode';

$lang->upgrade->selectedModeTips['classic'] = 'You can also switch to the new program set management mode in the background-Customize in the future.';
$lang->upgrade->selectedModeTips['new']     = 'Switching to the program management mode requires merging the previous data, and the system will guide you to complete this operation.';

$lang->upgrade->line         = 'Product Line';
$lang->upgrade->program      = 'Merge Project';
$lang->upgrade->existProgram = 'Existing programs';
$lang->upgrade->existProject = 'Existing projects';
$lang->upgrade->existLine    = 'Existing' . $lang->productCommon . ' lines';
$lang->upgrade->product      = $lang->productCommon;
$lang->upgrade->project      = 'Iteration';
$lang->upgrade->repo         = 'Repo';
$lang->upgrade->mergeRepo    = 'Merge Repo';

$lang->upgrade->newProgram         = 'Create';
$lang->upgrade->projectEmpty       = 'Project must be not empty.';
$lang->upgrade->mergeSummary       = "Dear users, there are %s products and %s iterations in your system waiting for Migration. By System Calculation, we recommend your migration plan as follows, you can also adjust according to your own situation:";
$lang->upgrade->mergeByProductLine = "PRODUCTLINE-BASED iterations: Consolidate the entire product line and the products and iterations below it into one large project.";
$lang->upgrade->mergeByProduct     = "PRODUCT-BASED iterations: You can select multiple products and their lower iterations to merge into a large project, or you can select a product to merge its lower iterations into a larger project";
$lang->upgrade->mergeByProject     = "Independent iterations: You can select several iterations and merge them into one large project, or merge them independently";
$lang->upgrade->mergeByMoreLink    = "Iteration that relates multiple products: select which product the iteration belongs to.";
$lang->upgrade->mergeRepoTips      = "Merge the selected version library under the selected product.";

$lang->upgrade->needBuild4Add    = 'Full text retrieval has been added in this upgrad. Please create an index.';
$lang->upgrade->needBuild4Adjust = 'Full text retrieval has been adjusted. Please create an index.';
$lang->upgrade->buildIndex       = 'Create Index';

include dirname(__FILE__) . '/version.php';
