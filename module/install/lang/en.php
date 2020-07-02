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
$lang->install->reload  = 'Refresh';
$lang->install->error   = 'Error ';

$lang->install->officeDomain     = 'https://www.zentao.pm';

$lang->install->start            = 'Start';
$lang->install->keepInstalling   = 'Continue installing this version';
$lang->install->seeLatestRelease = 'View the latest version';
$lang->install->welcome          = 'Thanks for choosing ZenTao!';
$lang->install->license          = 'ZenTao is under Z PUBLIC LICENSE(ZPL) 1.2';
$lang->install->desc             = <<<EOT
ZenTao ALM is an open source software released under <a href='http://zpl.pub/page/zplv12.html' target='_blank'>Z Public License</a>. It integrates with Product Management, Project Management, Test Management, Document Management, CI Management, etc. ZenTao is a perfect choice for managing software development projects.

ZenTao ALM is built on PHP + MySQL + zentaoPHP which is an independent framework developed by EasyCorp. Third-party developers/organizations can develop extensions or customize ZenTao accordingly.
EOT;
$lang->install->links = <<<EOT
ZenTao ALM is developed by <strong><a href='http://en.easysoft.ltd' target='_blank' class='text-danger'>EasyCorp</a></strong>.
Official Website: <a href='https://www.zentao.pm' target='_blank'>https://www.zentao.pm</a>
Technical Support: <a href='https://www.zentao.pm/forum/' target='_blank'>https://www.zentao.pm/forum/</a>
LinkedIn: <a href='https://www.linkedin.com/company/1156596/' target='_blank'>EasyCorp</a>
Facebook: <a href='https://www.facebook.com/natureeasysoft' target='_blank'>EasyCorp</a>
Twitter: <a href='https://twitter.com/ZentaoA' target='_blank'>ZenTao ALM</a>

You are installing ZenTao <strong class='text-danger'>%s</strong>.
EOT;

$lang->install->newReleased= "<strong class='text-danger'>Notice</strong>: Official Website has the latest version<strong class='text-danger'>%s</strong>, released on %s.";
$lang->install->or         = 'Or';
$lang->install->checking   = 'System Checkup';
$lang->install->ok         = 'Passed(√)';
$lang->install->fail       = 'Failed(×)';
$lang->install->loaded     = 'Loaded';
$lang->install->unloaded   = 'Not loaded';
$lang->install->exists     = 'Found ';
$lang->install->notExists  = 'Not found ';
$lang->install->writable   = 'Writable ';
$lang->install->notWritable= 'Not writable ';
$lang->install->phpINI     = 'PHP ini File';
$lang->install->checkItem  = 'Item';
$lang->install->current    = 'Current Setting';
$lang->install->result     = 'Result';
$lang->install->action     = 'Action';

$lang->install->phpVersion = 'PHP Version';
$lang->install->phpFail    = 'PHP Version should be 5.2.0+';

$lang->install->pdo          = 'PDO';
$lang->install->pdoFail      = 'Edit php.ini to load PDO extension.';
$lang->install->pdoMySQL     = 'PDO_MySQL';
$lang->install->pdoMySQLFail = 'Edit php.ini to load PDO_MySQL extension.';
$lang->install->json         = 'JSON Extension';
$lang->install->jsonFail     = 'Edit php.ini to load JSON extension.';
$lang->install->openssl      = 'OpenSSL Extension';
$lang->install->opensslFail  = 'Edit php.ini to load openssl extension.';
$lang->install->mbstring     = 'Mbstring Extension';
$lang->install->mbstringFail = 'Edit php.ini to load mbstring extension.';
$lang->install->zlib         = 'Zlib Extension';
$lang->install->zlibFail     = 'Edit php.ini to load zlib extension.';
$lang->install->curl         = 'Curl Extension';
$lang->install->curlFail     = 'Edit php.ini to load curl extension.';
$lang->install->filter       = 'Filter Extension';
$lang->install->filterFail   = 'Edit the php.ini file to load filter extension.';
$lang->install->gd           = 'GD Extension';
$lang->install->gdFail       = 'Edit the php.ini file to load gd extension.';
$lang->install->iconv        = 'Iconv Extension';
$lang->install->iconvFail    = 'Edit the php.ini file to load iconv extension.';
$lang->install->tmpRoot      = 'Temp Directory';
$lang->install->dataRoot     = 'Uploaded File Directory';
$lang->install->session      = 'Session Save Path';
$lang->install->sessionFail  = 'Edit the php.ini file to set session.save_path.';
$lang->install->mkdirWin     = '<p>%s directory has to be created.<br /> Run <code>mkdir %s</code> to create it.</p>';
$lang->install->chmodWin     = ' "%s" privilege has to be changed.';
$lang->install->mkdirLinux   = '<p>%s directory has to be created.<br /> Run <code>mkdir -p %s</code> to create it.</p>';
$lang->install->chmodLinux   = ' "%s" permison has to be changed.<br /> Run <code>chmod o=rwx -R %s</code> to change it.';

$lang->install->timezone       = 'Set Timezone';
$lang->install->defaultLang    = 'Default Language';
$lang->install->dbHost         = 'Database Host';
$lang->install->dbHostNote     = 'If 127.0.0.1 is not accessible, try localhost.';
$lang->install->dbPort         = 'Host Port';
$lang->install->dbEncoding     = 'Database Charset';
$lang->install->dbUser         = 'Database Username';
$lang->install->dbPassword     = 'Database Password';
$lang->install->dbName         = 'Database Name';
$lang->install->dbPrefix       = 'Table Prefix';
$lang->install->clearDB        = 'Clean up existing data';
$lang->install->importDemoData = 'Import Demo Data';
$lang->install->working        = 'Operation Mode';

$lang->install->requestTypes['GET']       = 'GET';
$lang->install->requestTypes['PATH_INFO'] = 'PATH_INFO';

$lang->install->workingList['full']      = 'Application Lifecycle Management';
$lang->install->workingList['onlyTest']  = 'Only Test Management';
$lang->install->workingList['onlyStory'] = 'Only Story Management';
$lang->install->workingList['onlyTask']  = 'Only Task Management';

$lang->install->errorConnectDB      = 'Connection to the database Failed. ';
$lang->install->errorDBName         = 'Database name should exclude “.” ';
$lang->install->errorCreateDB       = 'Failed to create the database.';
$lang->install->errorTableExists    = 'The data table has existed. If ZenTao has been installed before, please return to the previous step and clear data, then continue the installation.';
$lang->install->errorCreateTable    = 'Failed to create the table.';
$lang->install->errorImportDemoData = 'Failed to import the demo data.';

$lang->install->setConfig  = 'Create config file';
$lang->install->key        = 'Item';
$lang->install->value      = 'Value';
$lang->install->saveConfig = 'Save config file';
$lang->install->save2File  = '<div class="alert alert-warning">Copy the content in the text box above and save it to "<strong> %s </strong>". You can change this configuration file later.</div>';
$lang->install->saved2File = 'The configuration file has been saved to " <strong>%s</strong> ". You can change this file later.';
$lang->install->errorNotSaveConfig = 'The configuration file is not saved.';

$lang->install->getPriv  = 'Set Admin';
$lang->install->company  = 'Company Name';
$lang->install->account  = 'Admin Account';
$lang->install->password = 'Admin Password';
$lang->install->errorEmptyPassword = 'Password should not be blank.';

$lang->install->groupList['ADMIN']['name']   = 'Admin';
$lang->install->groupList['ADMIN']['desc']   = 'System Admin';
$lang->install->groupList['DEV']['name']     = 'Dev';
$lang->install->groupList['DEV']['desc']     = 'Developer';
$lang->install->groupList['QA']['name']      = 'Test';
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
$lang->install->groupList['QD']['desc']      = 'Test Director';
$lang->install->groupList['TOP']['name']     = 'Senior';
$lang->install->groupList['TOP']['desc']     = 'Senior Manager';
$lang->install->groupList['OTHERS']['name']  = 'Others';
$lang->install->groupList['OTHERS']['desc']  = 'other users';
$lang->install->groupList['LIMITED']['name'] = 'Limited User';
$lang->install->groupList['LIMITED']['desc'] = 'Users can only edit contents related to themselves.';

$lang->install->cronList[''] = 'Monitor Cron';
$lang->install->cronList['moduleName=project&methodName=computeburn']   = 'Update Burndown Chart';
$lang->install->cronList['moduleName=report&methodName=remind']         = 'Daily Task Reminder';
$lang->install->cronList['moduleName=svn&methodName=run']               = 'Synchronize SVN';
$lang->install->cronList['moduleName=git&methodName=run']               = 'Synchronize GIT';
$lang->install->cronList['moduleName=backup&methodName=backup']         = 'Backup Data';
$lang->install->cronList['moduleName=mail&methodName=asyncSend']        = 'Asynchronize sending message';
$lang->install->cronList['moduleName=webhook&methodName=asyncSend']     = 'Asynchronize sending webhook';
$lang->install->cronList['moduleName=admin&methodName=deleteLog']       = 'Delete expired logs';
$lang->install->cronList['moduleName=todo&methodName=createCycle']      = 'Create recurring todos';
$lang->install->cronList['moduleName=ci&methodName=initQueue']          = 'Create recurring Jenkins';
$lang->install->cronList['moduleName=ci&methodName=checkCompileStatus'] = 'Synchronize Jenkins Status';
$lang->install->cronList['moduleName=ci&methodName=exec']               = 'Execute Jenkins';

$lang->install->success  = "Installed!";
$lang->install->login    = 'ZenTao Login';
$lang->install->register = 'ZenTao Community Signup';

$lang->install->joinZentao = <<<EOT
<p>You have installed ZenTao %s.<strong class='text-danger'> Please delete install.php</strong>.</p><p>Note: In order to get the latest news of ZenTao, please sign up on ZenTao Community(<a href='https://www.zentao.pm' class='alert-link' target='_blank'>www.zentao.pm</a>).</p>
EOT;

$lang->install->product = array('chanzhi', 'zdoo', 'ydisk', 'meshiot');

$lang->install->promotion      = "Products also from EasyCorp:";
$lang->install->chanzhi        = new stdclass();
$lang->install->chanzhi->name  = 'ZSITE';
$lang->install->chanzhi->logo  = 'images/main/chanzhi.ico';
$lang->install->chanzhi->url   = 'http://www.zsite.net';
$lang->install->chanzhi->desc  = <<<EOD
<ul>
  <li>Article, Blog, Manual, Member, Shop, Forum, Feedback</li>
  <li>Customize page at will by Theme, Effect, Widget, CSS, JS and layout</li>
  <li>Support both desktop and mobile in one system</li>
  <li>Highly optimized for search engines</li>
</ul>
EOD;

$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name  = 'ZDOO';
$lang->install->zdoo->logo  = 'images/main/zdoo.ico';
$lang->install->zdoo->url   = 'http://www.zdoo.com';
$lang->install->zdoo->desc  = <<<EOD
<ul>
  <li>CRM: Customer Management and Order Tracking</li>
  <li>OA: Approve, Announce, Trip, Leave and more </li>
  <li>Project, Task and Document management </li>
  <li>Cash: Income, Expense, Transfer, Invest and Debt</li>
</ul>
EOD;














$lang->install->ydisk = new stdclass();
$lang->install->ydisk->name  = 'YDisk';
$lang->install->ydisk->logo  = 'images/main/ydisk.ico';
$lang->install->ydisk->url   = 'http://www.ydisk.cn';
$lang->install->ydisk->desc  = <<<EOD
<ul>
  <li>Self-Hosted: deploy on your own machine</li>
  <li>Unlimited Storage: depend on your hard drive size</li>
  <li>Fast Transmission: as fast as your bandwidth allows</li>
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
  <li>Battery Available: no changes required to any equipment on your site</li>
</ul>
EOD;
