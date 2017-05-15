<?php
/**
 * The admin module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id: zh-cn.php 4767 2013-05-05 06:10:13Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->admin->common        = '后台管理';
$lang->admin->index         = '后台管理首页';
$lang->admin->checkDB       = '检查数据库';
$lang->admin->sso           = '然之集成';
$lang->admin->safeIndex     = '安全';
$lang->admin->checkWeak     = '弱口令检查';
$lang->admin->certifyMobile = '认证手机';
$lang->admin->certifyEmail  = '认证邮箱';
$lang->admin->ztCompany     = '认证公司';
$lang->admin->captcha       = '验证码';
$lang->admin->getCaptcha    = '获取验证码';

$lang->admin->info = new stdclass();
$lang->admin->info->version = '当前系统的版本是%s，';
$lang->admin->info->links   = '您可以访问以下链接：';
$lang->admin->info->account = "您的禅道社区账户为%s。";

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "友情提示：您还未在禅道社区(www.zentao.net)登记，%s进行登记，以及时获得禅道最新信息。";
$lang->admin->notice->ignore   = "不再提示";

$lang->admin->register = new stdclass();
$lang->admin->register->common     = '注册新帐号绑定';
$lang->admin->register->caption    = '禅道社区登记';
$lang->admin->register->click      = '点击此处';
$lang->admin->register->lblAccount = '请设置您的用户名，英文字母和数字的组合，三位以上。';
$lang->admin->register->lblPasswd  = '请设置您的密码。数字和字母的组合，六位以上。';
$lang->admin->register->submit     = '登记';
$lang->admin->register->bind       = "绑定已有帐号";
$lang->admin->register->success    = "登记账户成功";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption = '关联社区帐号';
$lang->admin->bind->success = "关联账户成功";

$lang->admin->safe = new stdclass();
$lang->admin->safe->common     = '安全策略';
$lang->admin->safe->set        = '密码安全设置';
$lang->admin->safe->password   = '密码安全';
$lang->admin->safe->weak       = '常用弱口令';
$lang->admin->safe->reason     = '类型';
$lang->admin->safe->checkWeak  = '弱口令扫描';
$lang->admin->safe->changeWeak = '修改弱口令密码';
$lang->admin->safe->modifyPasswordFirstLogin = '首次登陆修改密码';

$lang->admin->safe->modeList[0] = '不检查';
$lang->admin->safe->modeList[1] = '中';
$lang->admin->safe->modeList[2] = '强';

$lang->admin->safe->modeRuleList[1] = '6位以上，包含大小写字母，数字。';
$lang->admin->safe->modeRuleList[2] = '10位以上，包含字母，数字，特殊字符。';

$lang->admin->safe->reasonList['weak']     = '常用弱口令';
$lang->admin->safe->reasonList['account']  = '与帐号相同';
$lang->admin->safe->reasonList['mobile']   = '与手机相同';
$lang->admin->safe->reasonList['phone']    = '与电话相同';
$lang->admin->safe->reasonList['birthday'] = '与生日相同';

$lang->admin->safe->modifyPasswordList[1] = '必须修改';
$lang->admin->safe->modifyPasswordList[0] = '不强制';

$lang->admin->safe->noticeMode   = '系统会在登录、创建和修改用户、修改密码的时候检查用户口令。';
$lang->admin->safe->noticeStrong = '密码长度越长，含有大写字母或数字或特殊符号越多，密码字母越不重复，安全度越强！';
