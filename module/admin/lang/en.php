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
$lang->admin->ssoAction     = 'Link Zdoo';
$lang->admin->safeIndex     = 'Security';
$lang->admin->checkWeak     = 'Check Weak Password';
$lang->admin->certifyMobile = 'Verify your cellphone';
$lang->admin->certifyEmail  = 'Verify your Email';
$lang->admin->ztCompany     = 'Verify your company';
$lang->admin->captcha       = 'Verification Code';
$lang->admin->getCaptcha    = 'Send Verification Code';

$lang->admin->api     = 'API';
$lang->admin->log     = 'Log';
$lang->admin->setting = 'Setting';
$lang->admin->days    = 'Valid Day';

$lang->admin->info = new stdclass();
$lang->admin->info->version = 'Current Version is %s. ';
$lang->admin->info->links   = 'You can visit links below';
$lang->admin->info->account = 'Your ZenTao account is %s.';
$lang->admin->info->log     = 'Log that exceeds valid days will be deleted and you have to run cron.';

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "Note: You haven't registered in ZenTao official website(www.zentao.pm). %s then get the Latest ZenTao Upgrades and News.";
$lang->admin->notice->ignore   = "Ignore";
$lang->admin->notice->int      = "『%s』should be a positive integer.";

$lang->admin->register = new stdclass();
$lang->admin->register->common     = 'Bind Account';
$lang->admin->register->caption    = 'ZenTao Community Signup';
$lang->admin->register->click      = 'Sign Up';
$lang->admin->register->lblAccount = '>= 3 letters and numbers';
$lang->admin->register->lblPasswd  = '>= 6 letters and numbers';
$lang->admin->register->submit     = 'Submit';
$lang->admin->register->bind       = "Bind Exsiting Account";
$lang->admin->register->success    = "You have signed up!";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Link Account';
$lang->admin->bind->success = "Account is linked!";

$lang->admin->safe = new stdclass();
$lang->admin->safe->common     = 'Security Policy';
$lang->admin->safe->set        = 'Password Settings';
$lang->admin->safe->password   = 'Password Strength';
$lang->admin->safe->weak       = 'Common Weak Passwords';
$lang->admin->safe->reason     = 'Type';
$lang->admin->safe->checkWeak  = 'Weak Password Scan';
$lang->admin->safe->changeWeak = 'Force to change weak password';
$lang->admin->safe->modifyPasswordFirstLogin = 'Force to change password after first login';

$lang->admin->safe->modeList[0] = 'I don\'t care.';
$lang->admin->safe->modeList[1] = 'Medium';
$lang->admin->safe->modeList[2] = 'Strong';

$lang->admin->safe->modeRuleList[1] = ' >= 6 upper and lower case, and numbers';
$lang->admin->safe->modeRuleList[2] = ' >= 10 upper and lower case, numbers and special characters.';

$lang->admin->safe->reasonList['weak']     = 'Common Weak Password';
$lang->admin->safe->reasonList['account']  = 'Same as account';
$lang->admin->safe->reasonList['mobile']   = 'Same as mobilephone number';
$lang->admin->safe->reasonList['phone']    = 'Same as phone number';
$lang->admin->safe->reasonList['birthday'] = 'Same as DOB';

$lang->admin->safe->modifyPasswordList[1] = 'Yes';
$lang->admin->safe->modifyPasswordList[0] = 'No';

$lang->admin->safe->noticeMode   = 'Password will be checked when a user logs in, or a user is added or edited.';
$lang->admin->safe->noticeStrong = '';
