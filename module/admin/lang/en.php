<?php
/**
 * The admin module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: en.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->admin->common  = 'Admin';
$lang->admin->index   = 'Index';
$lang->admin->checkDB = 'Repair data';
$lang->admin->company = 'Company';
$lang->admin->user    = 'User';
$lang->admin->group   = 'Group';
$lang->admin->welcome = 'Welcome to ZenTaoPMS.';

$lang->admin->browseCompany = 'Browse Company';

$lang->admin->clearData             = 'Reset';
$lang->admin->pleaseInputYes        = "Input 'yes' to reset zentao:";
$lang->admin->confirmClearData      = 'Are you sure to reset zentao?';
$lang->admin->clearDataFailed       = 'Failed to reset zentao!';
$lang->admin->clearDataSuccessfully = 'Successfully reset zentao';
$lang->admin->clearDataDesc    = <<<EOT
When you finish testing zentao, you can reset data inf zentao by using the reset feature. All data in database will be cleared except the data of company, department, user, group and priviledge. <br />
<strong class='text-danger'>This action is very dangerous, think over before you do it!</strong>
EOT;

$lang->admin->info = new stdclass();
$lang->admin->info->caption = 'zentao information';
$lang->admin->info->version = 'The current version of the system is %s,';
$lang->admin->info->links   = 'You can visit the following link:';
$lang->admin->info->account = 'Your account in zentao community is %s. ';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "Tips: You have not registered in zentao community (www.zentao.net), %s to register and get the latest information about ZentaoPMS.";
$lang->admin->notice->ignore   = "ignore";

$lang->admin->register = new stdclass();
$lang->admin->register->caption    = 'Register zentao community';
$lang->admin->register->click      = 'click';
$lang->admin->register->lblAccount = 'Numbers and letters, at least three';
$lang->admin->register->lblPasswd  = 'Numbers and letters, at least six';
$lang->admin->register->submit     = 'Register';
$lang->admin->register->bind       = "If you have community account, %s to bind it";
$lang->admin->register->success    = "Register success";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Bind with community account';
$lang->admin->bind->action  = 'bind';
$lang->admin->bind->success = "Bind success";
