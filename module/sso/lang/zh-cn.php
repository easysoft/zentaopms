<?php
/**
 * The sso module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     sso
 * @version     $Id$
 * @link        https://www.zentao.net
 */
$lang->sso = new stdclass();
$lang->sso->settings = '配置';
$lang->sso->turnon   = '是否打开';
$lang->sso->redirect = '自动跳回ZDOO';
$lang->sso->code     = '代号';
$lang->sso->key      = '密钥';
$lang->sso->addr     = '接口地址';
$lang->sso->bind     = '用户绑定';
$lang->sso->addrNotice = '比如：http://www.ranzhi.com/sys/sso-check.html';

$lang->sso->turnonList = array();
$lang->sso->turnonList[1] = '打开';
$lang->sso->turnonList[0] = '关闭';

$lang->sso->bindType = '绑定方式';
$lang->sso->bindUser = '绑定用户';

$lang->sso->bindTypeList['bind'] = '绑定已有用户';
$lang->sso->bindTypeList['add']  = '添加新用户';

$lang->sso->help = new stdclass();
$lang->sso->help->addr = '接口地址的填写，如果是PATH_INFO ：http://ZDOO网址/sys/sso-check.html，如果是GET：http://ZDOO网址/sys/index.php?m=sso&f=check';
$lang->sso->help->code = '代号必须与ZDOO后台设置的一致';
$lang->sso->help->key  = '密钥必须与ZDOO后台设置的一致';

$lang->sso->deny           = '访问受限';
$lang->sso->bindNotice     = '添加的新用户暂时没有权限，需要联系禅道管理员，给该用户分配权限。';
$lang->sso->bindNoPassword = '密码不能为空';
$lang->sso->bindNoUser     = '该用户的登录密码错误，或该用户不存在！';
$lang->sso->bindHasAccount = '该用户名已经存在，请更换用户名，或直接绑定到该用户。';

$lang->sso->homeURL             = '飞书主页配置URL：';
$lang->sso->redirectURL         = '飞书重定向配置URL：';
$lang->sso->feishuConfigEmpty   = '请在[后台][通知][Webhook]功能中配置(飞书工作消息通知)';
$lang->sso->feishuResponseEmpty = '请求响应信息为空';
$lang->sso->unbound             = '当前飞书用户未在禅道webhook功能中进行用户关系绑定';
