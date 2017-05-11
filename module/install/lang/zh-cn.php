<?php
/**
 * The install module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     install
 * @version     $Id: zh-cn.php 4972 2013-07-02 06:50:10Z zhujinyonging@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->install = new stdclass();

$lang->install->common  = '安装';
$lang->install->next    = '下一步';
$lang->install->pre     = '返回';
$lang->install->reload  = '刷新';
$lang->install->error   = '错误 ';

$lang->install->start            = '开始安装';
$lang->install->keepInstalling   = '继续安装当前版本';
$lang->install->seeLatestRelease = '看看最新的版本';
$lang->install->welcome          = '欢迎使用禅道项目管理软件！';
$lang->install->license          = '禅道项目管理软件使用 Z PUBLIC LICENSE(ZPL) 1.2 授权协议';
$lang->install->desc             = <<<EOT
禅道项目管理软件(ZenTaoPMS)是一款国产的，基于<a href='http://zpl.pub' target='_blank'>ZPL</a>协议，开源免费的项目管理软件，它集产品管理、项目管理、测试管理于一体，同时还包含了事务管理、组织管理等诸多功能，是中小型企业项目管理的首选。

禅道项目管理软件使用PHP + MySQL开发，基于自主的PHP开发框架──ZenTaoPHP而成。第三方开发者或者企业可以非常方便的开发插件或者进行定制。
EOT;
$lang->install->links = <<<EOT
禅道项目管理软件由<strong><a href='http://www.cnezsoft.com' target='_blank' class='text-danger'>青岛易软天创网络科技有限公司</a>开发</strong>。
官方网站：<a href='http://www.zentao.net' target='_blank'>http://www.zentao.net</a>
技术支持：<a href='http://www.zentao.net/ask/' target='_blank'>http://www.zentao.net/ask/</a>
新浪微博：<a href='http://weibo.com/easysoft' target='_blank'>http://weibo.com/easysoft</a>

您现在正在安装的版本是 <strong class='text-danger'>%s</strong>。
EOT;

$lang->install->newReleased= "<strong class='text-danger'>提示</strong>：官网网站已有最新版本<strong class='text-danger'>%s</strong>, 发布日期于 %s。";
$lang->install->or         = '或者';
$lang->install->checking   = '系统检查';
$lang->install->ok         = '检查通过(√)';
$lang->install->fail       = '检查失败(×)';
$lang->install->loaded     = '已加载';
$lang->install->unloaded   = '未加载';
$lang->install->exists     = '目录存在 ';
$lang->install->notExists  = '目录不存在 ';
$lang->install->writable   = '目录可写 ';
$lang->install->notWritable= '目录不可写 ';
$lang->install->phpINI     = 'PHP配置文件';
$lang->install->checkItem  = '检查项';
$lang->install->current    = '当前配置';
$lang->install->result     = '检查结果';
$lang->install->action     = '如何修改';

$lang->install->phpVersion = 'PHP版本';
$lang->install->phpFail    = 'PHP版本必须大于5.2.0';

$lang->install->pdo          = 'PDO扩展';
$lang->install->pdoFail      = '修改PHP配置文件，加载PDO扩展。';
$lang->install->pdoMySQL     = 'PDO_MySQL扩展';
$lang->install->pdoMySQLFail = '修改PHP配置文件，加载pdo_mysql扩展。';
$lang->install->json         = 'JSON扩展';
$lang->install->jsonFail     = '修改PHP配置文件，加载JSON扩展。';
$lang->install->tmpRoot      = '临时文件目录';
$lang->install->dataRoot     = '上传文件目录';
$lang->install->session      = 'Session存储目录';
$lang->install->sessionFail  = '修改PHP配置文件，设置session.save_path';
$lang->install->mkdirWin     = '<p>需要创建目录%s。命令行下面命令为：<br /> mkdir %s</p>';
$lang->install->chmodWin     = '需要修改目录 "%s" 的权限。';
$lang->install->mkdirLinux   = '<p>需要创建目录%s。<br /> 命令行下面命令为：<br /> mkdir -p %s</p>';
$lang->install->chmodLinux   = '需要修改目录 "%s" 的权限。<br />命令行下面命令为：<br />chmod o=rwx -R %s';

$lang->install->defaultLang    = '默认语言';
$lang->install->dbHost         = '数据库服务器';
$lang->install->dbHostNote     = '如果127.0.0.1无法访问，尝试使用localhost';
$lang->install->dbPort         = '服务器端口';
$lang->install->dbUser         = '数据库用户名';
$lang->install->dbPassword     = '数据库密码';
$lang->install->dbName         = 'PMS使用的库';
$lang->install->dbPrefix       = '建表使用的前缀';
$lang->install->clearDB        = '清空现有数据';
$lang->install->importDemoData = '导入demo数据';
$lang->install->working        = '工作方式';

$lang->install->requestTypes['GET']       = '普通方式';
$lang->install->requestTypes['PATH_INFO'] = '静态友好方式';

$lang->install->workingList['full']      = '完整研发管理工具';
$lang->install->workingList['onlyTest']  = '测试管理工具';
$lang->install->workingList['onlyStory'] = '需求管理工具';
$lang->install->workingList['onlyTask']  = '任务管理工具';

$lang->install->errorConnectDB      = '数据库连接失败 ';
$lang->install->errorDBName         = '数据库名不能含有 “.” ';
$lang->install->errorCreateDB       = '数据库创建失败';
$lang->install->errorTableExists    = '数据表已经存在，您之前应该有安装过禅道，继续安装请返回前页并选择清空数据';
$lang->install->errorCreateTable    = '创建表失败';
$lang->install->errorImportDemoData = '导入demo数据失败';

$lang->install->setConfig  = '生成配置文件';
$lang->install->key        = '配置项';
$lang->install->value      = '值';
$lang->install->saveConfig = '保存配置文件';
$lang->install->save2File  = '<div class="alert alert-warning">拷贝上面文本框中的内容，将其保存到 "<strong> %s </strong>"中。您以后还可继续修改此配置文件。</div>';
$lang->install->saved2File = '配置信息已经成功保存到" <strong>%s</strong> "中。您后面还可继续修改此文件。';
$lang->install->errorNotSaveConfig = '还没有保存配置文件';

$lang->install->getPriv  = '设置帐号';
$lang->install->company  = '公司名称';
$lang->install->account  = '管理员帐号';
$lang->install->password = '管理员密码';
$lang->install->errorEmptyPassword = '密码不能为空';

$lang->install->groupList['ADMIN']['name']  = '管理员';
$lang->install->groupList['ADMIN']['desc']  = '系统管理员';
$lang->install->groupList['DEV']['name']    = '研发';
$lang->install->groupList['DEV']['desc']    = '研发人员';
$lang->install->groupList['QA']['name']     = '测试';
$lang->install->groupList['QA']['desc']     = '测试人员';
$lang->install->groupList['PM']['name']     = '项目经理';
$lang->install->groupList['PM']['desc']     = '项目经理';
$lang->install->groupList['PO']['name']     = '产品经理';
$lang->install->groupList['PO']['desc']     = '产品经理';
$lang->install->groupList['TD']['name']     = '研发主管';
$lang->install->groupList['TD']['desc']     = '研发主管';
$lang->install->groupList['PD']['name']     = '产品主管';
$lang->install->groupList['PD']['desc']     = '产品主管';
$lang->install->groupList['QD']['name']     = '测试主管';
$lang->install->groupList['QD']['desc']     = '测试主管';
$lang->install->groupList['TOP']['name']    = '高层管理';
$lang->install->groupList['TOP']['desc']    = '高层管理';
$lang->install->groupList['OTHERS']['name'] = '其他';
$lang->install->groupList['OTHERS']['desc'] = '其他';

$lang->install->cronList[''] = '监控定时任务';
$lang->install->cronList['moduleName=project&methodName=computeburn'] = '更新燃尽图';
$lang->install->cronList['moduleName=report&methodName=remind']       = '每日任务提醒';
$lang->install->cronList['moduleName=svn&methodName=run']             = '同步SVN';
$lang->install->cronList['moduleName=git&methodName=run']             = '同步GIT';
$lang->install->cronList['moduleName=backup&methodName=backup']       = '备份数据和附件';
$lang->install->cronList['moduleName=mail&methodName=asyncSend']      = '异步发信';

$lang->install->success  = "安装成功";
$lang->install->login    = '登录禅道管理系统';
$lang->install->register = '禅道社区注册';

$lang->install->joinZentao = <<<EOT
<p>您已经成功安装禅道管理系统%s，<strong class='text-danger'>请及时删除install.php</strong>。</p><p>友情提示：为了您及时获得禅道的最新动态，请在禅道社区(<a href='http://www.zentao.net' class='alert-link' target='_blank'>www.zentao.net</a>)进行登记。</p>

EOT;

$lang->install->promotion = "为您推荐易软天创旗下其他产品：";
$lang->install->chanzhi   = new stdclass();
$lang->install->chanzhi->name = '蝉知企业门户系统';
$lang->install->chanzhi->desc = <<<EOD
<ul>
  <li>专业的企业营销门户系统</li>
  <li>功能丰富，操作简洁方便</li>
  <li>大量细节针对SEO优化</li>
  <li>开源免费，不限商用！</li>
</ul>
EOD;
$lang->install->ranzhi = new stdclass();
$lang->install->ranzhi->name = '然之协同管理系统';
$lang->install->ranzhi->desc = <<<EOD
<ul>
  <li>客户管理，订单跟踪</li>
  <li>项目任务，公告文档</li>
  <li>收入支出，出帐入账</li>
  <li>论坛博客，动态消息</li>
</ul>
EOD;
$lang->install->zdoo = new stdclass();
$lang->install->zdoo->name = '可深度定制的云端一体化协作平台';
$lang->install->zdoo->desc = <<<EOD
<ul>
  <li>安全、稳定、高效</li>
  <li>以容器为交付单位</li>
  <li>租户隔离，可深度定制</li>
  <li>提供一体化管理平台</li>
</ul>
EOD;
