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
$lang->upgrade->common  = 'Upgrade';
$lang->upgrade->result  = 'Ergebnis';
$lang->upgrade->fail    = 'Fehlgeschlagen';
$lang->upgrade->success = 'Erfolgreich';
$lang->upgrade->tohome  = 'Home';
$lang->upgrade->license = 'Zentao PMS nutzt jetzt Z PUBLIC LICENSE(ZPL) 1.2.';
$lang->upgrade->warnning= 'Warnung!';
$lang->upgrade->checkExtension  = 'Erweiterung prüfen';
$lang->upgrade->consistency     = 'Check Consistency';
$lang->upgrade->warnningContent = <<<EOT
<p>Bitte sichern Sie die Datenbank bevor Sie das Upgrade durchführen!</p>
<pre>
1. Nutzen Sie phpMyAdmin um die Sicherung zu erstellen.
2. Nutzen Sie mysql Befehle um das Backup zu erstellen.
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>dateiname</span> 
   Passen Sie den roten Text entsprechend Ihres Systems an.
   e.g. mysqldump -u root -p zentao >zentao.bak
</pre>
EOT;
$lang->upgrade->createFileWinCMD   = 'Öffnen Sie ein Konsolenfenster und rufen Sie <strong style="color:#ed980f">echo > %s</strong> auf';
$lang->upgrade->createFileLinuxCMD = 'Ausführen in der Shell: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>Bitte schließen Sie die folgende Aktion ab:</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>Oder löschen Sie "<strong style="color:#ed980f">%s</strong>" und erstellen dann <strong style="color:#ed980f">ok.txt</strong> als leere Datei.</li>
                                      </ul>
                                      <p><strong style="color:red">Ich habe die Einweisungen gelesen und abgearbeitet. <a href="upgrade.php">Fortsetzen.</a></strong></p>';
$lang->upgrade->selectVersion = 'Version auswählen';
$lang->upgrade->continue      = 'Weiter';
$lang->upgrade->noteVersion   = "Wählen Sie eine kompatible Version oder es droht Datenverlust.";
$lang->upgrade->fromVersion   = 'Von Version';
$lang->upgrade->toVersion     = 'Upgrade nach Version';
$lang->upgrade->confirm       = 'SQL Bestätigen';
$lang->upgrade->sureExecute   = 'Ausführen';
$lang->upgrade->forbiddenExt  = 'Die Erweiterung ist nicht kompatibel mit der Upgradeversion. Sie wurde deaktiviert:';
$lang->upgrade->updateFile    = 'Updateinformation wurden hinzugefügt.';
$lang->upgrade->noticeSQL     = 'Your database is inconsistent with the standard and it failed to fix it. Please run the following SQL and refresh.';
$lang->upgrade->afterDeleted  = 'File is not deleted. Please refresh after you delete it.';
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
