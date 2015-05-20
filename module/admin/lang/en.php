<?php
/**
 * The admin module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
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
$lang->admin->safeIndex = 'Safe';
$lang->admin->checkWeak = 'Check weak';
$lang->admin->welcome   = 'Welcome to ZenTaoPMS.';

$lang->admin->browseCompany = 'Browse Company';

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

$lang->admin->safe = new stdclass();
$lang->admin->safe->common    = 'Safe';
$lang->admin->safe->set       = 'Set password safe';
$lang->admin->safe->password  = 'Password safe';
$lang->admin->safe->weak      = 'weak Password';
$lang->admin->safe->reason    = 'Type';
$lang->admin->safe->checkWeak = 'Weak password check';

$lang->admin->safe->modeList[0] = 'Do not check';
$lang->admin->safe->modeList[1] = 'Intermediate';
$lang->admin->safe->modeList[2] = 'Strong';

$lang->admin->safe->reasonList['weak']     = 'Common weak password';
$lang->admin->safe->reasonList['account']  = 'Same account';
$lang->admin->safe->reasonList['mobile']   = 'Same mobile';
$lang->admin->safe->reasonList['phone']    = 'Same phone';
$lang->admin->safe->reasonList['birthday'] = 'Same birthday';

$lang->admin->safe->noticeMode = 'The system will log in, create and modify the user password, check the user password.';
