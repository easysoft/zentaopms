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
$lang->sso->turnon   = 'Turnon';
$lang->sso->code     = 'Code';
$lang->sso->key      = 'Key';
$lang->sso->addr     = 'Address';
$lang->sso->bind     = 'Bind user';
$lang->sso->addrNotice = 'Example: http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList = array();
$lang->sso->turnonList[1] = 'on';
$lang->sso->turnonList[0] = 'off';

$lang->sso->bindType = 'Bind type';
$lang->sso->bindUser = 'Bind user';

$lang->sso->bindTypeList['bind'] = 'Binding existing user';
$lang->sso->bindTypeList['add']  = 'Add new user';

$lang->sso->help = <<<EOD
<p>1. Fill in Address, PATH_INFO ：http://ranzhi site/sys/sso-check.html, GET：http://ranzhi site/sys/index.php?m=sso&f=check</p>
<p>2. Code and Key must be the same of setting of RanZhi.</p>
EOD;
$lang->sso->bindNotice     = 'Add new user do not have power. Contact the ZenTao administrator, to the user assigned authority.';
$lang->sso->bindNoPassword = 'Password can not empty';
$lang->sso->bindNoUser     = "The user's login password error, or the user does not exist!";
$lang->sso->bindHasAccount = 'The user name already exists, please change the user name, or bind to the user.';
