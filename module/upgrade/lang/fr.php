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
$lang->upgrade->common  = 'Mise à jour';
$lang->upgrade->result  = 'Résultat';
$lang->upgrade->fail    = 'Echec';
$lang->upgrade->success = 'Mise à jour effectuée';
$lang->upgrade->tohome  = 'Visitez ZenTao';
$lang->upgrade->license = 'ZenTao est sous Z PUBLIC LICENSE(ZPL) 1.2.';
$lang->upgrade->warnning= 'Attention!';
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
$lang->upgrade->to20Tips      = 'Zentao 20 upgrade tips';
$lang->upgrade->to20Button    = 'I have done the backup, start the upgrade!！';
$lang->upgrade->to20Desc      = <<<EOD
<p>Dear users, thank you for your support of Zentao. Since version 20, Zentao Buddhism has been upgraded to a general purpose project management platform. Compared to previous versions, Zentao 20 adds the concept of a large project and management model. Next we will help you with this upgrade by using the wizard to go to. This upgrade is divided into two parts: Project data merge and permission reset.</p>
<br />
<h4>1、Project merge</h4>
<p>We will merge the previous product and project data under the big project concept, and adjust the concept according to your choice of management model as follows：</p>
<ul>
  <li class='strong'>Scrum:Project > Product > Sprint > Task </li>
  <li class='strong'>Waterfall:Project > Product > Stage > Task</li>
  <li class='strong'>Kanban:Project > Product > Kanban > Card</li>
</ul>
<br />
<h4>2、Permission Reset</h4>
<p>Since the 20th version of Zentao, permissions are granted on a project basis, and the mechanism of authorization is:</p>
<p class='strong'>The administrator delegates authority to the project manager > The project manager delegates authority to the project members</p>
<br />
<div class='text-warning'>
  <p>Tips：</p>
  <ol>
    <li>You can start by installing a 20 version of Zen and experiencing the concepts and processes.</li>
    <li>Zentao version 20 changes a lot, please make a backup before you upgrade.</li>
  </ol>
</div>
EOD;

$lang->upgrade->line     = 'Product Line';
$lang->upgrade->program  = 'Merge Project';
$lang->upgrade->existPGM = 'Existing projects';
$lang->upgrade->PRJAdmin = 'Project Admin';
$lang->upgrade->product  = $lang->productCommon;
$lang->upgrade->project  = $lang->projectCommon;

$lang->upgrade->newProgram         = 'Create';
$lang->upgrade->mergeSummary       = "Dear users, there are %s products and %s iterations in your system waiting for Migration. By System Calculation, we recommend your migration plan as follows, you can also adjust according to your own situation:";
$lang->upgrade->mergeByProductLine = "PRODUCTLINE-BASED iterations: Consolidate the entire product line and the products and iterations below it into one large project.";
$lang->upgrade->mergeByProduct     = "PRODUCT-BASED iterations: You can select multiple products and their lower iterations to merge into a large project, or you can select a product to merge its lower iterations into a larger project";
$lang->upgrade->mergeByProject     = "Independent iterations: You can select several iterations and merge them into one large project, or merge them independently";
$lang->upgrade->mergeByMoreLink    = "Iteration that relates multiple products: select which product the iteration belongs to.";

include dirname(__FILE__) . '/version.php';
