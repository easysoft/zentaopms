<?php
/**
 * The sso module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv11.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->sso = new stdclass();
$lang->sso->settings = '配置';
$lang->sso->turnon   = '是否打開';
$lang->sso->code     = '代號';
$lang->sso->key      = '密鑰';
$lang->sso->addr     = '介面地址';
$lang->sso->addrNotice = '比如：http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList = array();
$lang->sso->turnonList[1] = '打開';
$lang->sso->turnonList[0] = '關閉';

$lang->sso->help = <<<EOD
<p>1、介面地址的填寫，如果是PATH_INFO ：http://然之網址/sys/sso-check.html，如果是GET：http://然之網址/sys/index.php?m=sso&f=check</p>
<p>2、代號和密鑰必須與然之後台設置的一致。</p>
<p>3、然之的用戶名必須和禪道里面的一致，否則無法從然之關聯登錄禪道</p>
EOD;
