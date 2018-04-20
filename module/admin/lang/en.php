<?php
/**
 * The admin module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: en.php 4460 2013-02-26 02:28:02Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->admin->common        = 'Admin';
$lang->admin->index         = 'Admin Home';
$lang->admin->checkDB       = 'Check Database';
$lang->admin->sso           = 'Zdoo';
$lang->admin->safeIndex     = 'Security';
$lang->admin->checkWeak     = 'Weak Password Check';
$lang->admin->certifyMobile = 'Verify your mobilephone';
$lang->admin->certifyEmail  = 'Verify your Email';
$lang->admin->ztCompany     = 'Verify your company';
$lang->admin->captcha       = 'Verification Code';
$lang->admin->getCaptcha    = 'Get Verification Code';

$lang->admin->api           = 'API';
$lang->admin->log           = 'Log';
$lang->admin->setting       = 'Setting';
$lang->admin->days          = 'Valid Days';
$lang->admin->saveSuccess   = 'Saved.';

$lang->admin->info = new stdclass();
$lang->admin->info->version = 'Current Version is %s. ';
$lang->admin->info->links   = 'You can visit links below';
$lang->admin->info->account = 'Your ZenTao account is %s.';
$lang->admin->info->log     = 'Logs that is beyond valid days will be deleted and it has to run cron.';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "Note: You haven't registered in ZenTao(www.zentao.pm). %s then get the Latest ZenTao Upgrades and News.";
$lang->admin->notice->ignore   = "Ignore";
$lang->admin->notice->int      = "『%s』should be a positive integer.";

$lang->admin->register = new stdclass();
$lang->admin->register->common     = 'Binding New Account';
$lang->admin->register->caption    = 'Register in Zentao Community';
$lang->admin->register->click      = 'Please Register here';
$lang->admin->register->lblAccount = 'at least 3 characters pls; contains letters and numbers.';
$lang->admin->register->lblPasswd  = 'at least 6 characters pls; contains letters and numbers.';
$lang->admin->register->submit     = 'Register';
$lang->admin->register->bind       = "Binding Exsit Account";
$lang->admin->register->success    = "You have registered with us!";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Link Account';
$lang->admin->bind->success = "Account has been linked!";

$lang->admin->safe = new stdclass();
$lang->admin->safe->common     = 'Security Policy';
$lang->admin->safe->set        = 'PasswordStrength';
$lang->admin->safe->password   = 'Password Strength';
$lang->admin->safe->weak       = 'Weak Passwords';
$lang->admin->safe->reason     = 'Type';
$lang->admin->safe->checkWeak  = 'WeakPasswords';
$lang->admin->safe->changeWeak = 'Weak password must be strengthen';
$lang->admin->safe->modifyPasswordFirstLogin = 'Change password when first login';

$lang->admin->safe->modeList[0] = 'N/A';
$lang->admin->safe->modeList[1] = 'Medium';
$lang->admin->safe->modeList[2] = 'Strong';

$lang->admin->safe->modeRuleList[1] = 'Contains upper and lower case, numbers. Length >= 6';
$lang->admin->safe->modeRuleList[2] = 'Contains upper and lower case, numbers and special characters.  Length >= 10.';

$lang->admin->safe->reasonList['weak']     = 'Common Weak Password';
$lang->admin->safe->reasonList['account']  = 'Same as your account';
$lang->admin->safe->reasonList['mobile']   = 'Same as your mobile number';
$lang->admin->safe->reasonList['phone']    = 'Same as your phone number';
$lang->admin->safe->reasonList['birthday'] = 'Same as your DOB';

$lang->admin->safe->modifyPasswordList[1] = 'Yes';
$lang->admin->safe->modifyPasswordList[0] = 'No';

$lang->admin->safe->noticeMode   = 'Password will be checked when login, add/edit user.';
$lang->admin->safe->noticeStrong = '';
