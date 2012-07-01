<?php
/**
 * The install module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->install->common  = 'Install';
$lang->install->next    = 'Next';
$lang->install->pre     = 'Back';
$lang->install->reload  = 'Reload';
$lang->install->error   = 'Error ';

$lang->install->start            = 'Start install';
$lang->install->keepInstalling   = 'Keep install this version';
$lang->install->seeLatestRelease = 'See the latest release.';
$lang->install->welcome          = 'Welcome to use ZenTaoPMS.';
$lang->install->desc             = <<<EOT
ZenTaoPMS is an opensource project management software licensed under LGPL. It has product manage, project mange, testing mange features, also with organization manage and affair manage.

ZenTaoPMS is developped by PHH and mysql under the zentaophp framework developped by the same team. Through the framework, ZenTaoPMS can be customed and extended very easily.

ZenTaoPMS is developped by <strong class='red'><a href='http://www.cnezsoft.com' target='_blank'>Nature EasySoft Network Tecnology Co.ltd, QingDao, China</a></strong>。
The official website of ZenTaoPMS is <a href='http://en.zentao.net' target='_blank'>http://en.zentao.net</a>
twitter:zentaopms

The version of current release is <strong class='red'>%s</strong>。
EOT;



$lang->install->newReleased= "<strong class='red'>Notice</strong>：There is a new version <strong class='red'>%s</strong>, released on %s。";
$lang->install->choice     = 'You can ';
$lang->install->checking   = 'System checking';
$lang->install->ok         = 'OK(√)';
$lang->install->fail       = 'Failed(×)';
$lang->install->loaded     = 'Loaded';
$lang->install->unloaded   = 'Not loaded';
$lang->install->exists     = 'Exists ';
$lang->install->notExists  = 'Not exists ';
$lang->install->writable   = 'Writable ';
$lang->install->notWritable= 'Not writable ';
$lang->install->phpINI     = 'PHP ini file';
$lang->install->checkItem  = 'Items';
$lang->install->current    = 'Current';
$lang->install->result     = 'Result';
$lang->install->action     = 'How to fix';

$lang->install->phpVersion = 'PHP version';
$lang->install->phpFail    = 'Must > 5.2.0';

$lang->install->pdo          = 'PDO extension';
$lang->install->pdoFail      = 'Edit the php.ini file to load PDO extsion.';
$lang->install->pdoMySQL     = 'PDO_MySQL extension';
$lang->install->pdoMySQLFail = 'Edit the php.ini file to load PDO_MySQL extsion.';
$lang->install->json         = 'JSON extension';
$lang->install->jsonFail     = 'Edit the php.ini file to load JSON extension';
$lang->install->tmpRoot      = 'Temp directory';
$lang->install->dataRoot     = 'Upload directory.';
$lang->install->mkdir        = '<p>Should creat the directory %s。<br /> Under linux, can try<br /> mkdir -p %s</p>';
$lang->install->chmod        = 'Should change the permission of "%s".<br />Under linux, can try<br />chmod o=rwx -R %s';

$lang->install->settingDB      = 'Set database';
$lang->install->webRoot        = 'ZenTaoPMS path';
$lang->install->requestType    = 'URL type';
$lang->install->defaultLang    = 'Default Language';
$lang->install->dbHost         = 'Database host';
$lang->install->dbHostNote     = 'If localhost can not connect, try 127.0.0.1';
$lang->install->dbPort         = 'Host port';
$lang->install->dbUser         = 'Database user';
$lang->install->dbPassword     = 'Database password';
$lang->install->dbName         = 'Database name';
$lang->install->dbPrefix       = 'Table prefix';
$lang->install->createDB       = 'Auto create database';
$lang->install->clearDB        = 'Clear data if database exists.';
$lang->install->importDemoData = 'Import demo data if database exists.';

$lang->install->requestTypes['GET']       = 'GET';
$lang->install->requestTypes['PATH_INFO'] = 'PATH_INFO';

$lang->install->errorConnectDB      = 'Database connect failed.';
$lang->install->errorCreateDB       = 'Database create failed.';
$lang->install->errorDBExists       = 'Database alread exists, to continue install, check the clear db box.';
$lang->install->errorCreateTable    = 'Table create failed.';
$lang->install->errorImportDemoData = 'Import demo data.';

$lang->install->setConfig  = 'Create config file';
$lang->install->key        = 'Item';
$lang->install->value      = 'Value';
$lang->install->saveConfig = 'Save config';
$lang->install->save2File  = '<div class="a-center"><span class="fail">Try to save the config auto, but failed.</span></div>Copy the text of the textareaand save to "<strong> %s </strong>".';
$lang->install->saved2File = 'The config file has saved to "<strong>%s</strong> ".';
$lang->install->errorNotSaveConfig = "Hasn't save the config file. ";

$lang->install->getPriv  = 'Set admin';
$lang->install->company  = 'Company name';
$lang->install->pms      = 'ZenTaoPMS domain';
$lang->install->pmsNote  = 'The domain name or ip address of ZenTaoPMS, no http://';
$lang->install->account  = 'Administrator';
$lang->install->password = 'Admin password';
$lang->install->errorEmptyPassword = "Can't be empty";

$lang->install->success = "Success installed";

$lang->install->joinZentao = <<<EOT
You have installed ZentaoPMS %s successfully. <strong class='red'>Please remove install.php in time</strong>。Now you can <a href='index.php'>login ZenTaoPMS</a>, create groups and grant priviledges.
<i>Tips: For you get zentao news in time, please register Zetao community(<a href='http://www.zentao.net' target='_blank'>www.zentao.net</a>).</i> 
EOT;
