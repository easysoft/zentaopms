<?php
/**
 * The sso module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id$
 * @link        https://www.zentao.net
 */
$lang->sso = new stdclass();
$lang->sso->settings = 'Settings';
$lang->sso->turnon   = 'Zdoo';
$lang->sso->redirect = 'Auto Jump to Zdoo';
$lang->sso->code     = 'Code';
$lang->sso->key      = 'Secret Key';
$lang->sso->addr     = 'Address';
$lang->sso->bind     = 'User Binding';
$lang->sso->addrNotice = 'Example http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList = array();
$lang->sso->turnonList[1] = 'On';
$lang->sso->turnonList[0] = 'Off';

$lang->sso->bindType = 'Binding Type';
$lang->sso->bindUser = 'User Binding';

$lang->sso->bindTypeList['bind'] = 'Bind to existing User';
$lang->sso->bindTypeList['add']  = 'Add User';

$lang->sso->help = new stdclass();
$lang->sso->help->addr = 'Zdoo address is required. If use PATH_INFO, it is http://YOUR ZDOO ADDRESS/sys/sso-check.html If GET, it is http://YOUR ZDOO ADDRESS/sys/index.php?m=sso&f=check';
$lang->sso->help->code = 'Code must be the same as set in Zdoo';
$lang->sso->help->key  = 'Secret Key must be the same as set in Zdoo';

$lang->sso->deny           = 'Access Limited';
$lang->sso->bindNotice     = 'User that is just added has no permissions. You have to ask ZenTao Admin to grant permissions to the User.';
$lang->sso->bindNoPassword = 'Password should not be empty.';
$lang->sso->bindNoUser     = 'Password is wrong/User cannot be found!';
$lang->sso->bindHasAccount = 'This username already exists. Change your username or bind to it.';

$lang->sso->homeURL             = 'Feishu Page Config URL：';
$lang->sso->redirectURL         = 'Feishu Redirect URL：';
$lang->sso->feishuConfigEmpty   = 'Go to [Admin]-[Notification]-[Webhook] to set ( Feishu Work Notification)';
$lang->sso->feishuResponseEmpty = 'Request response is empty';
$lang->sso->unbound             = 'Current Feishu user is not bound in ZenTao-Wwebhook.';
