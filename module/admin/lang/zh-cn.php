<?php
/**
 * The admin module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     admin
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->admin->common  = '后台管理';
$lang->admin->index   = '后台管理首页';
$lang->admin->checkDB = '修正数据';
$lang->admin->company = '公司管理';
$lang->admin->user    = '用户管理';
$lang->admin->group   = '分组管理';
$lang->admin->welcome = '欢迎使用禅道管理软件后台管理系统';

$lang->admin->browseCompany = '浏览公司';

$lang->admin->clearData             = '清除数据';
$lang->admin->pleaseInputYes        = '确认清除数据请输入yes：';
$lang->admin->confirmClearData      = '您确认要清除数据吗?';
$lang->admin->clearDataFailed       = '清除数据失败！';
$lang->admin->clearDataSuccessfully = '清除数据成功！';
$lang->admin->clearDataDesc    = <<<EOT
<strong><font color='red'>清除数据存在一定的风险，清楚数据之前，我们强烈建议您备份数据库及相应的数据文件，并保证清除数据的时候，没有其他人进行操作。</font></strong>\n
清除数据对数据库的影响如下：
1、清除数据不会对company, group, groupPriv表进行操作。
2、如果安装的时候有导入demo数据，则会删除config表key=showDemoUsers的记录，并删除user表中的所有demo用户。
3、<font color='red'>对于其他表则进行全部清除操作。</font>
EOT;

$lang->admin->info = new stdclass();
$lang->admin->info->caption = '禅道系统信息';
$lang->admin->info->version = '当前系统的版本是%s，';
$lang->admin->info->links   = '您可以访问以下链接：';
$lang->admin->info->account = "您的禅道社区账户为%s。";

$lang->admin->notice = new stdclass();
$lang->admin->notice->register = "友情提示：您还未在禅道社区(www.zentao.net)登记，%s进行登记，以及时获得禅道最新信息。";
$lang->admin->notice->ignore   = "不再提示";

$lang->admin->register = new stdclass();
$lang->admin->register->caption    = '禅道社区登记';
$lang->admin->register->click      = '点击此处';
$lang->admin->register->lblAccount = '请设置您的用户名，英文字母和数字的组合，三位以上。';
$lang->admin->register->lblPasswd  = '请设置您的密码。数字和字母的组合，六位以上。';
$lang->admin->register->submit     = '登记';
$lang->admin->register->bind       = "如果您已经拥有社区账号，%s关联账户";
$lang->admin->register->success    = "登记账户成功";

$lang->admin->bind = new stdclass();
$lang->admin->bind->caption  = '关联社区账号';
$lang->admin->bind->action   = '关联';
$lang->admin->bind->success  = "关联账户成功";

$lang->admin->selectFlow = '您计划如何使用禅道？';

$lang->admin->flowList['full']      = '所有功能（包括产品、项目、需求、计划、发布、任务、Bug、用例、测试任务和文档等功能。）';
$lang->admin->flowList['onlyTest']  = '仅测试管理（包括产品、版本、Bug、用例、测试任务和文档管理等功能。)';
$lang->admin->flowList['onlyTask']  = '仅任务管理（包括项目、任务和文档管理。)';
$lang->admin->flowList['onlyStory'] = '仅需求管理（包括产品、需求、计划、发布和文档管理等功能。）';

$lang->admin->flowNotice = "<span class='red'>注：如果您使用的不是所有功能，后续可以到“管理->插件”中，卸载相应的插件，即可重新使用禅道所有功能。</span>";
