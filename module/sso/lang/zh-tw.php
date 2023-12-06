<?php
/**
 * The sso module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 青島易軟天創網絡科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id$
 * @link        https://www.zentao.net
 */
$lang->sso = new stdclass();
$lang->sso->settings = '配置';
$lang->sso->turnon   = '是否打開';
$lang->sso->redirect = '自動跳回ZDOO';
$lang->sso->code     = '代號';
$lang->sso->key      = '密鑰';
$lang->sso->addr     = '介面地址';
$lang->sso->bind     = '用戶綁定';
$lang->sso->addrNotice = '比如：http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList = array();
$lang->sso->turnonList[1] = '打開';
$lang->sso->turnonList[0] = '關閉';

$lang->sso->bindType = '綁定方式';
$lang->sso->bindUser = '綁定用戶';

$lang->sso->bindTypeList['bind'] = '綁定已有用戶';
$lang->sso->bindTypeList['add']  = '添加新用戶';

$lang->sso->help = <<<EOD
<p>1、介面地址的填寫，如果是PATH_INFO ：http://ZDOO網址/sys/sso-check.html，如果是GET：http://ZDOO網址/sys/index.php?m=sso&f=check</p>
<p>2、代號和密鑰必須與ZDOO後台設置的一致。</p>
EOD;
$lang->sso->deny           = '訪問受限';
$lang->sso->bindNotice     = '添加的新用戶暫時沒有權限，需要聯繫禪道管理員，給該用戶分配權限。';
$lang->sso->bindNoPassword = '密碼不能為空';
$lang->sso->bindNoUser     = '該用戶的登錄密碼錯誤，或該用戶不存在！';
$lang->sso->bindHasAccount = '該用戶名已經存在，請更換用戶名，或直接綁定到該用戶。';

$lang->sso->homeURL             = '飛書主頁配置URL：';
$lang->sso->redirectURL         = '飛書重定向配置URL：';
$lang->sso->feishuConfigEmpty   = '請在[後台][通知][Webhook]功能中配置(飛書工作消息通知)';
$lang->sso->feishuResponseEmpty = '請求響應信息為空';
$lang->sso->unbound             = '當前飛書用戶未在禪道webhook功能中進行用戶關係綁定';
