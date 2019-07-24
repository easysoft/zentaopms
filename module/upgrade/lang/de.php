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

include dirname(__FILE__) . '/version.php';
