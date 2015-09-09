<?php
/**
 * The sso module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->sso = new stdclass();
$lang->sso->settings = '配置';
$lang->sso->turnon   = '是否打开';
$lang->sso->code     = '代号';
$lang->sso->key      = '密钥';
$lang->sso->addr     = '接口地址';
$lang->sso->addrNotice = '比如：http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList = array();
$lang->sso->turnonList[1] = '打开';
$lang->sso->turnonList[0] = '关闭';

$lang->sso->help = <<<EOD
<p>1、接口地址的填写，如果是PATH_INFO ：http://然之网址/sys/sso-check.html，如果是GET：http://然之网址/sys/index.php?m=sso&f=check</p>
<p>2、代号和密钥必须与然之后台设置的一致。</p>
<p>3、然之的用户名必须和禅道里面的一致，否则无法从然之关联登录禅道</p>
EOD;
