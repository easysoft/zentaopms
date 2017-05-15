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
$lang->admin->common        = 'Administration';
$lang->admin->index         = 'Admin Home';
$lang->admin->checkDB       = 'Check Database';
$lang->admin->sso           = 'RangerTeam';
$lang->admin->safeIndex     = 'Security';
$lang->admin->checkWeak     = 'Weak Password Check';
$lang->admin->certifyMobile = 'Verify your mobilephone';
$lang->admin->certifyEmail  = 'Verifu your Email';
$lang->admin->ztCompany     = 'Verifu your company';
$lang->admin->captcha       = 'Verification Code';
$lang->admin->getCaptcha    = 'Get Verification Code';

$lang->admin->info = new stdclass();
$lang->admin->info->version = 'Current Version is %s. ';
$lang->admin->info->links   = 'You can visit ';
$lang->admin->info->account = "Your ZenTao account is %s.";

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "Note: You haven't registered in ZenTao(www.zentao.net), register %s and get the latest ZenTao news.";
$lang->admin->notice->ignore   = "Ignore";

$lang->admin->register = new stdclass();
$lang->admin->register->common     = 'Account Binding';
$lang->admin->register->caption    = 'Register';
$lang->admin->register->click      = 'Click Here';
$lang->admin->register->lblAccount = 'must be 3 characters combination of letters and numbers at least.';
$lang->admin->register->lblPasswd  = 'must be 6 characters combination of letters and numbers at least.';
$lang->admin->register->submit     = 'Register';
$lang->admin->register->bind       = "If you have already registered an account, link your account %s.";
$lang->admin->register->success    = "You have registered with us!";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = 'Link an Account';
$lang->admin->bind->success = "Account has been linked!";

$lang->admin->safe = new stdclass();
$lang->admin->safe->common     = 'Security Policy';
$lang->admin->safe->set        = 'Password Security Settings';
$lang->admin->safe->password   = 'Password Security';
$lang->admin->safe->weak       = 'Weak Password';
$lang->admin->safe->reason     = 'Type';
$lang->admin->safe->checkWeak  = 'Weak Password Check';
$lang->admin->safe->changeWeak = 'Modify weak password';
$lang->admin->safe->modifyPasswordFirstLogin = 'Modify password when first login';

$lang->admin->safe->modeList[0] = 'N/A';
$lang->admin->safe->modeList[1] = 'Medium';
$lang->admin->safe->modeList[2] = 'Strong';

$lang->admin->safe->modeRuleList[1] = 'Should be contain letters and numbers, at lease six characters.';
$lang->admin->safe->modeRuleList[2] = 'Should be contain letters, numbers and special characters, at lease 10 characters.';

$lang->admin->safe->reasonList['weak']     = 'Common Weak Password';
$lang->admin->safe->reasonList['account']  = 'Same as your account';
$lang->admin->safe->reasonList['mobile']   = 'Same as your mobile number';
$lang->admin->safe->reasonList['phone']    = 'Same as your phone number';
$lang->admin->safe->reasonList['birthday'] = 'Same as your DOB';

$lang->admin->safe->modifyPasswordList[1] = 'Must';
$lang->admin->safe->modifyPasswordList[0] = 'No';

$lang->admin->safe->noticeMode   = 'User password will be checked when login, add/edit user password.';
$lang->admin->safe->noticeStrong = 'The more capitalized letters and numbers a password has, the more secure it is!';
