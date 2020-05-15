<?php
/**
 * The install module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: en.php 4972 2013-07-02 06:50:10Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->install = new stdclass();

$lang->install->common  = 'Installieren';
$lang->install->next    = 'Weiter';
$lang->install->pre     = 'Zurück';
$lang->install->reload  = 'Neu laden';
$lang->install->error   = 'Fehler ';

$lang->install->officeDomain     = 'https://www.zentao.pm';

$lang->install->start            = 'Start der Installation';
$lang->install->keepInstalling   = 'Installation dieser Version fortsetzen.';
$lang->install->seeLatestRelease = 'Letzte Version anzeigen';
$lang->install->welcome          = 'Danke, dass sie sich für ZenTao als Projektmanagement Software entschieden haben!';
$lang->install->license          = 'ZenTao PMS läuft unter der Z PUBLIC LICENSE(ZPL) 1.2';
$lang->install->desc             = <<<EOT
ZenTao Project Management Software ist eine Open Source Software veröffentlicht under der <a href='http://zpl.pub/page/zplv12.html' target='_blank'>ZPL</a> Lizenz. ZenTao enthält Product Management, Project Management, QA Management, Document Management, Todos Management, Company Management etc. ZenTao ist die beste Wahl als Projekt Management Lösung.

ZenTao PMS setzt auf PHP + MySQL und das ZenTaoPHP Framework, ein unabhängiges Framework von unserem Team entwickelt. Third party Entwickler/Unternhemen können Erweiterungen entwickeln oder nach Ihren Bedürfnissen anpassen.
EOT;
$lang->install->links = <<<EOT
ZenTao ALM wurde entwickelt von <strong><a href='http://easysoft.ltd' target='_blank' class='text-danger'>Nature Easy Soft Co., LTD</a></strong>.
Offizielle Website : <a href='http://www.zentao.pm' target='_blank'>http://www.zentao.pm</a>
Technischer Support : <a href='http://www.zentao.pm/forum/' target='_blank'>http://www.zentao.pm/forum/</a>
LinkedIn:e<a href='https://www.linkedin.com/company/1156596/' target='_blank'>Nature Easy Soft</a>
Facebook: <a href='https://www.facebook.com/natureeasysoft' target='_blank'>Nature Easy Soft</a>
Twitter: <a href='https://twitter.com/ZentaoA' target='_blank'>ZenTao ALM</a>

Sie Installieren ZenTao <strong class='text-danger'>%s</strong> Version.
EOT;

$lang->install->newReleased= "<strong class='text-danger'>Notice</strong>：Offizielle Webseite hat die letzte Version<strong class='text-danger'>%s</strong>, veröffentlicht am %s.";
$lang->install->or         = 'Oder';
$lang->install->checking   = 'Systemprüfung';
$lang->install->ok         = 'Bestanden(√)';
$lang->install->fail       = 'Fehlgeschlagen(×)';
$lang->install->loaded     = 'Geladen';
$lang->install->unloaded   = 'Nicht geladen';
$lang->install->exists     = 'Gefunden ';
$lang->install->notExists  = 'Nicht gefunden ';
$lang->install->writable   = 'Beschreibbar ';
$lang->install->notWritable= 'Nicht beschreibbar ';
$lang->install->phpINI     = 'PHP ini Datei';
$lang->install->checkItem  = 'Eintrag';
$lang->install->current    = 'Aktuelle Einstellung';
$lang->install->result     = 'Ergebnisse';
$lang->install->action     = 'Aktionen';

$lang->install->phpVersion = 'PHP Version';
$lang->install->phpFail    = 'PHP Version muss 5.2.0+ sein';

$lang->install->pdo          = 'PDO';
$lang->install->pdoFail      = 'Bearbeiten Sie die PHP ini Datei um die PDO Erweiterung zu laden.';
$lang->install->pdoMySQL     = 'PDO_MySQL';
$lang->install->pdoMySQLFail = 'Bearbeiten Sie die PHP ini Datei um die PDO_MySQL Erweiterung zu laden.';
$lang->install->json         = 'JSON Extension';
$lang->install->jsonFail     = 'Bearbeiten Sie die PHP ini Datei um die JSON Erweiterung zu laden.';
$lang->install->openssl      = 'OpenSSL Extension';
$lang->install->opensslFail  = 'Bearbeiten Sie die PHP ini Datei um die openssl Erweiterung zu laden.';
$lang->install->mbstring     = 'Mbstring Extension';
$lang->install->mbstringFail = 'Bearbeiten Sie die PHP ini Datei um die mbstring Erweiterung zu laden.';
$lang->install->zlib         = 'Zlib Extension';
$lang->install->zlibFail     = 'Bearbeiten Sie die PHP ini Datei um die zlib Erweiterung zu laden.';
$lang->install->curl         = 'Curl Extension';
$lang->install->curlFail     = 'Bearbeiten Sie die PHP ini Datei um die curl Erweiterung zu laden.';
$lang->install->filter       = 'Filter Extension';
$lang->install->filterFail   = 'Bearbeiten Sie die PHP ini Datei um die filter Erweiterung zu laden.';
$lang->install->gd           = 'GD Extension';
$lang->install->gdFail       = 'Bearbeiten Sie die PHP ini Datei um die gd Erweiterung zu laden.';
$lang->install->iconv        = 'Iconv Extension';
$lang->install->iconvFail    = 'Bearbeiten Sie die PHP ini Datei um die iconv Erweiterung zu laden.';
$lang->install->tmpRoot      = 'Temp Directory';
$lang->install->dataRoot     = 'Upload Dateiverzeichnis';
$lang->install->session      = 'Session Speicherpfad';
$lang->install->sessionFail  = 'Bearbeiten Sie die PHP ini Datei um den session.save_path zu setzen.';
$lang->install->mkdirWin     = '<p>%s Verzeichnis muss erstellt werden.<br /> Befehl <code>mkdir %s</code> zur Erstellung.</p>';
$lang->install->chmodWin     = ' "%s" Berechtigung muss geändert werden.';
$lang->install->mkdirLinux   = '<p>%s Verzeichnis muss erstellt werden.<br /> Befehl <code>mkdir -p %s</code> zur Erstellung.</p>';
$lang->install->chmodLinux   = ' "%s" Berechtigung muss geändert werden.<br /> Befehl <code>chmod o=rwx -R %s</code> für die Anpassung.';

$lang->install->timezone       = 'Set Timezone';
$lang->install->defaultLang    = 'Standard Sprache';
$lang->install->dbHost         = 'Datenbank Host';
$lang->install->dbHostNote     = 'Wenn 127.0.0.1 nicht funktioniert, versuchen Sie localhost.';
$lang->install->dbPort         = 'Datenbank Port';
$lang->install->dbEncoding     = 'Datenbank Charset';
$lang->install->dbUser         = 'Datenbank User';
$lang->install->dbPassword     = 'Datenbank Passwort';
$lang->install->dbName         = 'Datenbank Name';
$lang->install->dbPrefix       = 'Tabellen-Prefix';
$lang->install->clearDB        = 'Tabellen leeren sofern diese scon existieren.';
$lang->install->importDemoData = 'Import Demo Daten';
$lang->install->working        = 'Work Mode';

$lang->install->requestTypes['GET']       = 'GET';
$lang->install->requestTypes['PATH_INFO'] = 'PATH_INFO';

$lang->install->workingList['full']      = 'Volles Development Management';
$lang->install->workingList['onlyTest']  = 'Nur Test Management';
$lang->install->workingList['onlyStory'] = 'Nur Story Management';
$lang->install->workingList['onlyTask']  = 'Nur Task Management';

$lang->install->errorConnectDB      = 'Verbindung zur Datenbank fehlgeschlagen. ';
$lang->install->errorDBName         = 'Datenbankname darf keinen Punkt enthalten';
$lang->install->errorCreateDB       = 'Erstellung der Datenbank fehlgeschlagen.';
$lang->install->errorTableExists    = 'Die Tabellen existieren bereits. Wenn ZenTao bereits installiert war, kehren Sie zum letzen Schritt zurück und wählen Sie den Punkt Tabellen leeren. Dann fahren Sie fort.';
$lang->install->errorCreateTable    = 'Erstellung der Tabellen fehlgeschlagen.';
$lang->install->errorImportDemoData = 'Import der Demodaten fehlgeschlagen.';

$lang->install->setConfig  = 'Konfigurationsdatei erstellen';
$lang->install->key        = 'Eintrag';
$lang->install->value      = 'Wert';
$lang->install->saveConfig = 'Konfiguration speichern';
$lang->install->save2File  = '<div class="alert alert-warning">Kopieren Sie den Inhalt aus der Textbox und speichern Sie diesen als "<strong> %s </strong>". Sie können die Konfiguration später ändern.</div>';
$lang->install->saved2File = 'Die Konfigurationsdatei wurde gespeichert unter " <strong>%s</strong> ". Sie können die Konfiguration später ändern.';
$lang->install->errorNotSaveConfig = 'Die Konfigurationsdatei wurde nicht gespeichert.';

$lang->install->getPriv  = 'Als Admin setzen';
$lang->install->company  = 'Firmenname';
$lang->install->account  = 'Admin Konto';
$lang->install->password = 'Admin Passwort';
$lang->install->errorEmptyPassword = 'Passwort sollte nicht leer sein.';

$lang->install->groupList['ADMIN']['name']   = 'Admin';
$lang->install->groupList['ADMIN']['desc']   = 'System Administrator';
$lang->install->groupList['DEV']['name']     = 'Dev';
$lang->install->groupList['DEV']['desc']     = 'Entwickler';
$lang->install->groupList['QA']['name']      = 'QS';
$lang->install->groupList['QA']['desc']      = 'Tester';
$lang->install->groupList['PM']['name']      = 'PM';
$lang->install->groupList['PM']['desc']      = 'Project Manager';
$lang->install->groupList['PO']['name']      = 'PO';
$lang->install->groupList['PO']['desc']      = 'Product Owner';
$lang->install->groupList['TD']['name']      = 'Dev Manager';
$lang->install->groupList['TD']['desc']      = 'Development Manager';
$lang->install->groupList['PD']['name']      = 'PD';
$lang->install->groupList['PD']['desc']      = 'Product Director';
$lang->install->groupList['QD']['name']      = 'QD';
$lang->install->groupList['QD']['desc']      = 'QD Director';
$lang->install->groupList['TOP']['name']     = 'Senior';
$lang->install->groupList['TOP']['desc']     = 'Senior Manager';
$lang->install->groupList['OTHERS']['name']  = 'Andere';
$lang->install->groupList['OTHERS']['desc']  = 'Andere Benutzer';
$lang->install->groupList['LIMITED']['name'] = 'Eingeschränkte Benutzer';
$lang->install->groupList['LIMITED']['desc'] = 'Können nur Inhalte bearbeiten, die sie selbst betreffen';

$lang->install->cronList[''] = 'Cron Anzeigen';
$lang->install->cronList['moduleName=project&methodName=computeburn']   = 'Update Burndown Chart';
$lang->install->cronList['moduleName=report&methodName=remind']         = 'Täglicher Aufgaben reminder';
$lang->install->cronList['moduleName=svn&methodName=run']               = 'Synchronisiere SVN';
$lang->install->cronList['moduleName=git&methodName=run']               = 'Synchronisiere GIT';
$lang->install->cronList['moduleName=backup&methodName=backup']         = 'Backup Daten & Dateien';
$lang->install->cronList['moduleName=mail&methodName=asyncSend']        = 'Async Nachrichten Sendung';
$lang->install->cronList['moduleName=webhook&methodName=asyncSend']     = 'Async Webhook Sendung';
$lang->install->cronList['moduleName=admin&methodName=deleteLog']       = 'Überfällige Logs löschen';
$lang->install->cronList['moduleName=todo&methodName=createCycle']      = 'Erstelle wiederkehrende ToDos';
$lang->install->cronList['moduleName=ci&methodName=initQueue']          = 'Create recurring jenkins';
$lang->install->cronList['moduleName=ci&methodName=checkCompileStatus'] = 'Synchronize Jenkins Status';
$lang->install->cronList['moduleName=ci&methodName=exec']               = 'Execute Jenkins';

$lang->install->success  = "Installiert!";
$lang->install->login    = 'Login ZenTao';
$lang->install->register = 'Bei ZenTao registieren';

$lang->install->joinZentao = <<<EOT
<p>Sie haben ZenTao %s installiert.<strong class='text-danger'> Bitte löschen Sie die install.php schnellstmöglich</strong>.</p><p>Hinweis: Wenn Sie über ZenTao informiert bleiben möchten, registieren Sie sich bitte unter (<a href='http://www.zentao.pm' class='alert-link' target='_blank'>www.zentao.pm</a>).</p>
EOT;

$lang->install->product = array('chanzhi', 'zdoo');

$lang->install->promotion     = "Produkte von Nature Easy Soft:";
$lang->install->chanzhi       = new stdclass();
$lang->install->chanzhi->name = 'ZSITE';
$lang->install->chanzhi->logo = 'images/main/chanzhi.ico';
$lang->install->chanzhi->url  = 'http://www.zsite.net';
$lang->install->chanzhi->desc = <<<EOD
<ul>
  <li>Artikel, Blog, Handbucher, Mitglieder, Shop, Forum, Feedback……</li>
  <li>Frei anpassbar durch Themes, Effekte, Widgets, css, js und Layout</li>
  <li>Unterstützt Desktops und Mobilgeräte in einem System</li>
  <li>Stark optimiert für Suchmaschienen</li>
</ul>
EOD;

$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name = 'ZDOO';
$lang->install->zdoo->logo = 'images/main/zdoo.ico';
$lang->install->zdoo->url  = 'http://www.zdoo.com';
$lang->install->zdoo->desc = <<<EOD
<ul>
  <li>CRM: Customer Management und Bestellvervollgung</li>
  <li>OA: Freigaben, Ankündigungen, Reisen, Abwesenheiten und mehr. </li>
  <li>Projekte， Aufgaben und Dokumentenmanagement </li>
  <li>Geld: Einkommen, Ausgaben, Transfer, Investitionen und Kredite</li>
</ul>
EOD;














$lang->install->ydisk = new stdclass();
$lang->install->ydisk->name  = 'Y Disk-Free NetDisk';
$lang->install->ydisk->logo  = 'images/main/ydisk.ico';
$lang->install->ydisk->url   = 'http://www.ydisk.cn';
$lang->install->ydisk->desc  = <<<EOD
<ul>
  <li>Self-Hosted: deploy on your own machine</li>
  <li>Storage: depend on your hard drive size</li>
  <li>Transmission: as fast as your bandwidth allows</li>
  <li>Secure: 12 permissions for any strict settings</li>
</ul>
EOD;

$lang->install->meshiot = new stdclass();
$lang->install->meshiot->name  = 'MeshIoT';
$lang->install->meshiot->logo  = 'images/main/meshiot.ico';
$lang->install->meshiot->url   = 'https://www.meshiot.com';
$lang->install->meshiot->desc  = <<<EOD
<ul>
  <li>Performance: one gateway can monitor 65,536 equipments</li>
  <li>Accessibility: unique radio communication protocol covers 2,500m radius</li>
  <li>Dimming System: 200+ sensors and monitors</li>
  <li>Battery Available: no requirements to any equipment on your site</li>
</ul>
EOD;
