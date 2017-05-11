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

$lang->install->common  = 'Install';
$lang->install->next    = 'Next';
$lang->install->pre     = 'Back';
$lang->install->reload  = 'Reload';
$lang->install->error   = 'Error ';

$lang->install->start            = 'Start to Install';
$lang->install->keepInstalling   = 'Continue installing this version.';
$lang->install->seeLatestRelease = 'View Latest Version';
$lang->install->welcome          = 'Welcome to ZenTao Project Management Software!';
$lang->install->license          = 'ZenTao PMS is under  Z PUBLIC LICENSE(ZPL) 1.2';
$lang->install->desc             = <<<EOT
ZenTao Project Management Software (ZenTao PMS) is originated in China and under <a href='http://zpl.pub' target='_blank'>ZPL</a>. It us an open source and free project management software, integrated with Product Management、Project Management、Testing Management, as weel as To-Dos Management, Company Managementetc, which is the best choice for small and medium organizations to manage projects.

ZenTao PMS uses PHP + MySQL as programming langugages and is based on ZenTaoPHP,  an independent framwork developed by our team. Third party developers/organizations can develop extensions or customization tailored to their needs.
EOT;
$lang->install->links = <<<EOT
ZenTao PMS is developed by <strong><a href='http://www.cnezsoft.com' target='_blank' class='text-danger'>Qingdao Nature Easy Soft Co., LTD</a></strong>.
Officila Wensite <a href='http://www.zentao.net' target='_blank'>http://www.zentao.net</a>
Technical Support <a href='http://www.zentao.net/ask/' target='_blank'>http://www.zentao.net/ask/</a>
新浪微博：<a href='http://weibo.com/easysoft' target='_blank'>http://weibo.com/easysoft</a>

You are installing ZenTao <strong class='text-danger'>%s</strong> Version.
EOT;

$lang->install->newReleased= "<strong class='text-danger'>Notice</strong>：Official Website has the latest version<strong class='text-danger'>%s</strong>, released on %s.";
$lang->install->or         = 'Or';
$lang->install->checking   = 'System Checking';
$lang->install->ok         = 'Passed(√)';
$lang->install->fail       = 'Failed(×)';
$lang->install->loaded     = 'Loaded';
$lang->install->unloaded   = 'Not Loaded';
$lang->install->exists     = 'Found ';
$lang->install->notExists  = 'Not found ';
$lang->install->writable   = 'Writable ';
$lang->install->notWritable= 'Not Writable ';
$lang->install->phpINI     = 'PHP ini File';
$lang->install->checkItem  = 'Item';
$lang->install->current    = 'Current Settings';
$lang->install->result     = 'Results';
$lang->install->action     = 'Actions';

$lang->install->phpVersion = 'PHP Version';
$lang->install->phpFail    = 'PHP Version has to be 5.2.0+';

$lang->install->pdo          = 'PDO';
$lang->install->pdoFail      = 'Edit PHP ini file to load PDO extsion.';
$lang->install->pdoMySQL     = 'PDO_MySQL';
$lang->install->pdoMySQLFail = 'Edit PHP ini file to load PDO_MySQL extsion.';
$lang->install->json         = 'JSON Extension';
$lang->install->jsonFail     = 'Edit the php.ini file to load JSON extension.';
$lang->install->tmpRoot      = 'Temp File Directory';
$lang->install->dataRoot     = 'Uploaded File Directory';
$lang->install->session      = 'Session Save Path';
$lang->install->sessionFail  = 'Edit the php.ini file to set session.save_path.';
$lang->install->mkdirWin     = '<p>%s directory has to be created.<br /> Run <br /> mkdir %s</p> command line.';
$lang->install->chmodWin     = ' "%s" privilege has to be changed.';
$lang->install->mkdirLinux   = '<p>%s directory has to be created.<br /> Run <br /> mkdir -p %s</p> command line.';
$lang->install->chmodLinux   = ' "%s" privilege has to be changed.<br /> Run <br />chmod o=rwx -R %s command line.';

$lang->install->defaultLang    = 'Default Lang';
$lang->install->dbHost         = 'Database Host';
$lang->install->dbHostNote     = 'If 127.0.0.1 is not accessible, try localhost.';
$lang->install->dbPort         = 'host Port';
$lang->install->dbUser         = 'Database User';
$lang->install->dbPassword     = 'Database Password';
$lang->install->dbName         = 'Database Library';
$lang->install->dbPrefix       = 'Database Table Prefix';
$lang->install->clearDB        = 'Clear Database';
$lang->install->importDemoData = 'Import Demo Data';
$lang->install->working        = 'Working way';

$lang->install->requestTypes['GET']       = 'GET';
$lang->install->requestTypes['PATH_INFO'] = 'PATH_INFO';

$lang->install->workingList['full']      = 'Full';
$lang->install->workingList['onlyTest']  = 'Only Test';
$lang->install->workingList['onlyStory'] = 'Only Story';
$lang->install->workingList['onlyTask']  = 'Only Task';

$lang->install->errorConnectDB      = 'Connection to database Failed. ';
$lang->install->errorDBName         = 'Database name should exclude “.” ';
$lang->install->errorCreateDB       = 'Database creation failed.';
$lang->install->errorTableExists    = 'Data table has existed. If ZenTao has been installed before, please go back to clear data and continue installation.';
$lang->install->errorCreateTable    = 'Table creation failed.';
$lang->install->errorImportDemoData = 'Import demo data failed.';

$lang->install->setConfig  = 'Create Config File';
$lang->install->key        = 'Item';
$lang->install->value      = 'Value';
$lang->install->saveConfig = 'Save Config';
$lang->install->save2File  = '<div class="alert alert-warning">Copy content in the text box above and save it to "<strong> %s </strong>". You can change this config file later.</div>';
$lang->install->saved2File = 'Config file has been saved to " <strong>%s</strong> ". You can change this file later.';
$lang->install->errorNotSaveConfig = 'Config file is not saved.';

$lang->install->getPriv  = 'Set Admin';
$lang->install->company  = 'Company Name';
$lang->install->account  = 'Admin Account';
$lang->install->password = 'Admin Password';
$lang->install->errorEmptyPassword = 'Password should not be blank.';

$lang->install->groupList['ADMIN']['name']  = 'Admin';
$lang->install->groupList['ADMIN']['desc']  = 'System Admin';
$lang->install->groupList['DEV']['name']    = 'Dev';
$lang->install->groupList['DEV']['desc']    = 'Dev Team';
$lang->install->groupList['QA']['name']     = 'Testing';
$lang->install->groupList['QA']['desc']     = 'Testing Team';
$lang->install->groupList['PM']['name']     = 'Project Manager';
$lang->install->groupList['PM']['desc']     = 'for Project Manager';
$lang->install->groupList['PO']['name']     = 'Product Owner';
$lang->install->groupList['PO']['desc']     = 'for Product Owner';
$lang->install->groupList['TD']['name']     = 'Dev Supervisor';
$lang->install->groupList['TD']['desc']     = 'for Dev Supervisor';
$lang->install->groupList['PD']['name']     = 'Product Supervisor';
$lang->install->groupList['PD']['desc']     = 'for Product Supervisor';
$lang->install->groupList['QD']['name']     = 'Testing Supervisor';
$lang->install->groupList['QD']['desc']     = 'for Testing Supervisor';
$lang->install->groupList['TOP']['name']    = 'Senior Manager';
$lang->install->groupList['TOP']['desc']    = 'for Senior Manager';
$lang->install->groupList['OTHERS']['name'] = 'Other';
$lang->install->groupList['OTHERS']['desc'] = 'for Other';

$lang->install->cronList[''] = 'Monitor cron';
$lang->install->cronList['moduleName=project&methodName=computeburn'] = 'Compute burn';
$lang->install->cronList['moduleName=report&methodName=remind']       = 'Daily task reminder';
$lang->install->cronList['moduleName=svn&methodName=run']             = 'Synchronize SVN';
$lang->install->cronList['moduleName=git&methodName=run']             = 'Synchronize GIT';
$lang->install->cronList['moduleName=backup&methodName=backup']       = 'Backup data and attachment';
$lang->install->cronList['moduleName=mail&methodName=asyncSend']      = 'Asynchronous sending';

$lang->install->success  = "Installed!";
$lang->install->login    = 'Login ZenTao';
$lang->install->register = 'Register in ZenTao';

$lang->install->joinZentao = <<<EOT
<p>Tou have installed ZenTao %s.<strong class='text-danger'> Please delete install.php asap</strong>.</p><p>Note: In order to get the latest news of ZenTao, please register in ZenTao(<a href='http://www.zentao.net' class='alert-link' target='_blank'>www.zentao.net</a>).</p>

EOT;

$lang->install->promotion = "Products of Nature Easy Soft:";
$lang->install->chanzhi   = new stdclass();
$lang->install->chanzhi->name = 'Changer CMS';
$lang->install->chanzhi->desc = <<<EOD
<ul>
  <li>Professional Content Management Sysytem</li>
  <li>Rich functionality and simple to operate</li>
  <li>Aims at detaild for SEO</li>
  <li>Open source and free</li>
</ul>
EOD;
$lang->install->ranzhi = new stdclass();
$lang->install->ranzhi->name = 'Ranger Collaborative System';
$lang->install->ranzhi->desc = <<<EOD
<ul>
  <li>Customer Management and Order Tracking</li>
  <li>Project/Task and Announcement/Document</li>
  <li>Income/Expenditur and Account In/Out </li>
  <li>Forum/Blog and Dynamic/News</li>
</ul>
EOD;
$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name = 'Integrated Cloud Platform';
$lang->install->zdoo->desc = <<<EOD
<ul>
  <li>Safe, Stable and Effective</li>
  <li>Data isolation by docker</li>
  <li>Code level customization</li>
  <li>An integrated and collaborative platform</li>
</ul>
EOD;
