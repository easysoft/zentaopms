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
$lang->sso->settings = 'Configuration';
$lang->sso->turnon   = 'Access ZDOO';
$lang->sso->redirect = 'Redirect to ZDOO';
$lang->sso->code     = 'Code';
$lang->sso->key      = 'Secret Key';
$lang->sso->addr     = 'Endpoint URL';
$lang->sso->bind     = 'User Linking';
$lang->sso->addrNotice = 'Example: http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList = array();
$lang->sso->turnonList[1] = 'On';
$lang->sso->turnonList[0] = 'Off';

$lang->sso->bindType = 'Connection Methods';
$lang->sso->bindUser = 'Connect User';

$lang->sso->bindTypeList['bind'] = 'Connect Existing User';
$lang->sso->bindTypeList['add']  = 'Add User';

$lang->sso->help = new stdclass();
$lang->sso->help->addr = 'Endpoint URL format:
For PATH_INFO: http://your zdoo url/sys/sso-check.html
For GET: http://your zdoo url/sys/index.php?m=sso&f=check';
$lang->sso->help->code = 'Ensure the code is identical to the one in ZDOO.';
$lang->sso->help->key  = 'Ensure the Secret Key is identical to the one in ZDOO.';

$lang->sso->deny           = 'Access Restricted';
$lang->sso->bindNotice     = 'No permissions. Please ask ZenTao Admin for access.';
$lang->sso->bindNoPassword = 'Password is required';
$lang->sso->bindNoUser     = 'Invalid username or password';
$lang->sso->bindHasAccount = 'Username already exists. Please choose another one or link to the existing account.';

$lang->sso->homeURL             = 'Feishu Homepage URL:';
$lang->sso->redirectURL         = 'Feishu Redirect URL:';
$lang->sso->feishuConfigEmpty   = 'Configure (Feishu Messenger Notifications) in [Admin]-[Notifications]-[Webhook].';
$lang->sso->feishuResponseEmpty = 'Empty response received';
$lang->sso->unbound             = 'User linking missing for Feishu in ZenTao Webhook settings.';
