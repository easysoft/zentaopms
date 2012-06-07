<?php
/**
 * The admin module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->admin->common  = 'Admin';
$lang->admin->index   = 'Index';
$lang->admin->company = 'Company';
$lang->admin->user    = 'User';
$lang->admin->group   = 'Group';
$lang->admin->welcome = 'Welcome to ZenTaoPMS.';

$lang->admin->browseCompany = 'Browse Company';

$lang->admin->clearData        = 'Clear Data';
$lang->admin->confirmClearData = 'Are you sure to clear data?';
$lang->admin->clearDataFailed  = 'Failed to clear data!';
$lang->admin->clearDataSucceed = 'Succeed to clear data!';
$lang->admin->clearDataDesc    = <<<EOT
<strong><font color='red'>Clear data is dangerous. Be sure to backup your database and other data files and sure nobody is using pms when importing.</font></strong>\n
The impact of clearing data:
1、Clearing data will have no effect on table company,table group,and table groupPriv.
2、If you have choosed import demo data when installing,then clearing data will delete the item where key equals showDemoUsers on config table, and delete all demo users on user table.
3、For others tables:clear all data.
EOT;

$lang->admin->info->caption        = 'ZentaoPMS information';
$lang->admin->info->version 	   = 'The current version of the system is %s,';
$lang->admin->info->links          = 'You can visit the following link:';
$lang->admin->info->account        = 'Your account in zentao community is %s. ';

$lang->admin->notice->register = "Tips: You have not registered in Zentao community (www.zentao.net), %s to register and get the latest information about ZentaoPMS.";
$lang->admin->notice->ignore   = "ignore";

$lang->admin->register->caption    = 'Register zentao community';
$lang->admin->register->click      = 'click';
$lang->admin->register->lblAccount = 'Numbers and letters, at least three';
$lang->admin->register->lblPasswd  = 'Numbers and letters, at least six';
$lang->admin->register->submit     = 'Register';
$lang->admin->register->bind       = "If you have community account, %s to bind it";
$lang->admin->register->success    = "Register success";

$lang->admin->bind->caption = 'Bind with community account';
$lang->admin->bind->action  = 'bind';
$lang->admin->bind->success = "Bind success";
