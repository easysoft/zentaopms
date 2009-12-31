<?php
/**
 * The install module zh-cn file of ZenTaoMS.
 *
 * ZenTaoMS is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * ZenTaoMS is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with ZenTaoMS.  If not, see <http://www.gnu.org/licenses/>.  
 *
 * @copyright   Copyright: 2009 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     install
 * @version     $Id$
 * @link        http://www.zentao.cn
 */
$lang->install->common  = '安装';
$lang->install->welcome = '欢迎使用禅道项目管理软件！';
$lang->install->desc    = <<<EOT
禅道项目管理软件(ZenTaoPMS)是一款国产的，基于LGPL协议，开源免费的项目管理软件，它集产品管理、项目管理、测试管理于一体，同时还包含了事务管理、组织管理等诸多功能，是中小型企业项目管理的首选。

禅道项目管理软件使用PHP + MySQL开发，基于自主的PHP开发框架──ZenTaoPHP而成。第三方开发者或者企业可以非常方便的开发插件或者进行定制。
EOT;
$lang->install->start      = '开始安装';
$lang->install->next       = '下一步';
$lang->install->reload     = '刷新';
$lang->install->checking   = '系统检查';
$lang->install->setConfig  = '生成配置文件';
$lang->install->saveConfig = '保存配置文件';
$lang->install->settingDB  = '设置数据库';
$lang->install->save2File  = '拷贝上面文本框中的内容，将其保存到"%s"中。';
$lang->install->ok         = '检查通过(√)';
$lang->install->fail       = '检查失败(×)';
$lang->install->loaded     = '已加载';
$lang->install->unloaded   = '未加载';
$lang->install->exists     = '目录存在 ';
$lang->install->notExists  = '目录不存在 ';
$lang->install->writable   = '目录可写 ';
$lang->install->notWritable= '目录不可写 ';
$lang->install->checkItem  = '检查项';
$lang->install->current    = '当前配置';
$lang->install->result     = '检查结果';
$lang->install->action     = '如何修改';
$lang->install->key        = '配置项';
$lang->install->value      = '值';

$lang->install->phpVersion = 'PHP版本';
$lang->install->phpFail    = 'PHP版本必须大于5.2.0';

$lang->install->pdo          = 'PDO扩展';
$lang->install->pdoFail      = '修改PHP配置文件，加载PDO扩展。';
$lang->install->pdoMySQL     = 'PDO_MySQL扩展';
$lang->install->pdoMySQLFail = '修改PHP配置文件，加载pdo_mysql扩展。';
$lang->install->tmpRoot      = '临时文件目录';
$lang->install->dataRoot     = '上传文件目录';
$lang->install->mkdir        = '<p>需要创建目录%s。<br /> linux下面命令为：<br /> mkdir -p %s</p>';
$lang->install->chmod        = '需要修改目录 "%s" 的权限。<br />linux下面命令为：<br />chmod o=rwx -R %s';

$lang->install->webRoot      = 'PMS所在网站目录';
$lang->install->requestType  = 'URL方式';
$lang->install->requestTypes['GET']       = '普通方式';
$lang->install->requestTypes['PATH_INFO'] = '静态友好方式';
$lang->install->dbHost     = '数据库服务器';
$lang->install->dbPort     = '服务器端口';
$lang->install->dbUser     = '数据库用户名';
$lang->install->dbPassword = '数据库密码';
$lang->install->dbName     = 'PMS使用的库';
$lang->install->dbPrefix   = '建表使用的前缀';
