<?php
/**
 * The upgrade module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: en.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        https://www.zentao.pm
 */
$lang->upgrade->common          = 'Mise à jour';
$lang->upgrade->start           = 'Start';
$lang->upgrade->result          = 'Résultat';
$lang->upgrade->fail            = 'Echec';
$lang->upgrade->successTip      = 'Mise à jour effectuée';
$lang->upgrade->success         = 'Mise à jour effectuée';
$lang->upgrade->tohome          = 'Visitez ZenTao';
$lang->upgrade->license         = 'ZenTao est sous Z PUBLIC LICENSE(ZPL) 1.2.';
$lang->upgrade->warnning        = 'Attention!';
$lang->upgrade->checkExtension  = 'Vérifiez Extensions';
$lang->upgrade->consistency     = 'Vérifiez Consistence';
$lang->upgrade->warnningContent = <<<EOT
<p>Sauvegardez votre base de données avant la mise à jour de ZenTao !</p>
<pre>
1. Utilisez phpMyAdmin pour faire la sauvegarde.
2. Utilisez une commande mysql pour faire la sauvegarde.
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span>
   Changez le texte en rouge par les code user et le nom de la base qui correspondent.
   e.g. mysqldump -u root -p zentao >zentao.bak
</pre>
EOT;
$lang->upgrade->createFileWinCMD   = 'Ouvrez la fenêtre Ligne de commandes de windows et exécutez <strong style="color:#ed980f">echo > %s</strong>';
$lang->upgrade->createFileLinuxCMD = 'Executez la ligne de commande suivante: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>Accomplissez les actions suivantes</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>Ou supprimez "<strong style="color:#ed980f">%s</strong>" et créez <strong style="color:#ed980f">ok.txt</strong> et laissez ce fichier vide.</li>
                                      </ul>
                                      <p><strong style="color:red">Vous avez lu et accompli toutes les actions précédentes. <a href="upgrade.php">Continuez la mise à jour.</a></strong></p>';

$lang->upgrade->selectVersion = 'Version';
$lang->upgrade->continue      = 'Continuer';
$lang->upgrade->noteVersion   = "Sélectionnez une version compatible où vous pourriez perdre des données.";
$lang->upgrade->fromVersion   = 'De';
$lang->upgrade->toVersion     = 'à';
$lang->upgrade->confirm       = 'Confirmez SQL';
$lang->upgrade->sureExecute   = 'Executez';
$lang->upgrade->forbiddenExt  = 'Cette extension est incompatible avec la version. Elle a été désactivée :';
$lang->upgrade->updateFile    = "Le fichier information a besoin d'une mise à jour.";
$lang->upgrade->noticeSQL     = 'Votre base de donnée est inconsistente avec le standard et il y a eu un échec pour la corriger. Exécutez la commande SQL suivante et rafraichissez.';
$lang->upgrade->afterDeleted  = "Le fichier n'est pas supprimé. Recommencez après l'avoir supprimé.";
$lang->upgrade->mergeProgram  = 'Data Merge';
$lang->upgrade->mergeTips     = 'Data Migration Tips';
$lang->upgrade->toPMS15Guide  = 'ZenTao open source version 15.0.beta1 upgrade';
$lang->upgrade->toPRO10Guide  = 'ZenTao profession version 10.0.rc1 upgrade';
$lang->upgrade->toBIZ5Guide   = 'ZenTao enterprise version 5.0.rc1 upgrade';
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

$lang->upgrade->to15Mode['classic'] = 'Keep the old version';
$lang->upgrade->to15Mode['new']     = 'New program management mode';

$lang->upgrade->selectedModeTips['classic'] = 'You can also switch to the new program set management mode in the background-Customize in the future.';
$lang->upgrade->selectedModeTips['new']     = 'Switching to the program management mode requires merging the previous data, and the system will guide you to complete this operation.';

$lang->upgrade->demoURL       = 'http://zentao20.demo.zentao.net';
$lang->upgrade->videoURL      = 'https://qc.zentao.net/zentao20.mp4';
$lang->upgrade->to20Tips      = 'Zentao 20 upgrade tips';
$lang->upgrade->to20Button    = 'I have done the backup, start the upgrade!！';
$lang->upgrade->to20TipsHeader= "<p>Dear user, thank you for your support of ZenTao。Since version 20, Zendo has been fully upgraded to a universal project management platform. Please see the following video for more information：</p><br />";
$lang->upgrade->to20Desc      = <<<EOD
<div class='text-warning'>
  <p>Friendly reminder：</p>
  <ol>
    <li>You can start by installing a version 20 of ZenTao to experience the concepts and processes inside.</li>
    <li>Version 20 of Zendo has made some major changes, please make a backup before upgrading.</li>
    <li>Please feel free to upgrade, even if the first upgrade is not in place, subsequent adjustments can be made without affecting system data.</li>
  </ol>
</div>
EOD;
$lang->upgrade->mergeProgramDesc = <<<EOD
<p>Next, ZenTao will migrate the existing data of {$lang->productCommon} and {$lang->projectCommon} to Program and Project. It will be one of the followings:</p><br />
<h4>1. Manage {$lang->productCommon} and {$lang->projectCommon} by Product Line </h4>
<p>Migrate the data of {$lang->productCommon} and {$lang->projectCommon} by Product Line to a Program. You can also migrate it separately.</p>
<h4>2. Manage {$lang->projectCommon} by {$lang->productCommon}</h4>
<p>You can migrate the data of several {$lang->productCommon}s and {$lang->projectCommon}s to one Program. Or select {$lang->projectCommon}s of a {$lang->productCommon} and {$lang->productCommon} to a Program.</p>
<h4>2. Independent {$lang->projectCommon}</h4>
<p>Choose several {$lang->projectCommon}s and migrate them to one Program, or migrate them separately.</p>
<h4>4. Link {$lang->projectCommon}s of several {$lang->productCommon}s</h4>
<p>You can set {$lang->projectCommon}s as one new project.</p>
EOD;

$lang->upgrade->line          = 'Product Line';
$lang->upgrade->allLines      = 'All Lines';
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

$lang->upgrade->newProgram         = 'Create';
$lang->upgrade->projectEmpty       = 'Project must be not empty.';
$lang->upgrade->mergeSummary       = "Dear users, there are %s products and %s iterations in your system waiting for Migration. By System Calculation, we recommend your migration plan as follows, you can also adjust according to your own situation:";
$lang->upgrade->mergeByProductLine = "PRODUCTLINE-BASED iterations: Consolidate the entire product line and the products and iterations below it into one large project.";
$lang->upgrade->mergeByProduct     = "PRODUCT-BASED iterations: You can select multiple products and their lower iterations to merge into a large project, or you can select a product to merge its lower iterations into a larger project";
$lang->upgrade->mergeByProject     = "Independent iterations: You can select several iterations and merge them into one large project, or merge them independently";
$lang->upgrade->mergeByMoreLink    = "{$lang->projectCommon} that relates multiple {$lang->productCommon}: select which project the {$lang->projectCommon} belongs to.";
$lang->upgrade->mergeRepoTips      = "Merge the selected version library under the selected product.";

$lang->upgrade->needBuild4Add    = 'Full text retrieval has been added in this upgrad. Please create an index.';
$lang->upgrade->needBuild4Adjust = 'Full text retrieval has been adjusted. Please create an index.';
$lang->upgrade->buildIndex       = 'Create Index';

$lang->upgrade->projectType['project']   = "Upgrade the historical {$lang->projectCommon} as a project";
$lang->upgrade->projectType['execution'] = "Upgrade the historical {$lang->projectCommon} as an execution";

$lang->upgrade->createProjectTip = <<<EOT
<p>After the upgrade, the existing {$lang->projectCommon} will be Project in the new version.</p>
<p>ZenTao will create an item in Execute with the same name of {$lang->projectCommon} according to the data in {$lang->projectCommon}, and move the tasks, stories, and bugs in {$lang->projectCommon} to it.</p>
EOT;
$lang->upgrade->createExecutionTip = <<<EOT
<p>ZenTao will upgrade existing {$lang->projectCommon} as Iteration.</p>
<p>After the upgrade, the data of existing {$lang->projectCommon} will be in a Project - Execute of the new version .</p>
EOT;

include dirname(__FILE__) . '/version.php';
