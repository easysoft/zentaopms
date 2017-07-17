<?php
/**
 * The sso module English file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->sso = new stdclass();
$lang->sso->settings = 'Settings';
$lang->sso->turnon   = 'On';
$lang->sso->redirect = 'Redirect ranzhi';
$lang->sso->code     = 'Alias';
$lang->sso->key      = 'Key';
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

$lang->sso->help = <<<EOD
<p>1. Interface address is required. If use PATH_INFO, it is http://YOUR RANGER ADDRESS/sys/sso-check.html If GET, it is http://YOUR RANGER ADDRESS/sys/index.php?m=sso&f=check</p>
<p>2. Code and Key must be the same as set in Ranger.</p>
EOD;
$lang->sso->bindNotice     = 'User that is just added has no privilege. You have to ask ZenTao Admin to grant permissions to the User.';
$lang->sso->bindNoPassword = 'Password should not be empty.';
$lang->sso->bindNoUser     = 'Password is wrong/User cannot be found!';
$lang->sso->bindHasAccount = 'This username already exists. Change your username or bind to it.';
