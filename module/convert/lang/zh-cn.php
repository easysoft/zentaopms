<?php
/**
 * The convert module zh-cn file of ZenTaoMS.
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
 * @package     convert
 * @version     $Id: zh-cn.php 246 2010-01-01 14:36:09Z wwccss $
 * @link        http://www.zentao.cn
 */
$lang->convert->common  = '从其他系统导入';
$lang->convert->next    = '下一步';
$lang->convert->pre     = '返回';
$lang->convert->reload  = '刷新';
$lang->convert->error   = '错误 ';

$lang->convert->start   = '开始转换';
$lang->convert->desc    = <<<EOT
<p>欢迎使用系统转换向导，本程序会帮助您将其他系统的数据转换到禅道项目管理系统中。</p>
<strong>转换存在一定的风险，转换之前，我们强烈建议您备份数据库及相应的数据文件，并保证转换的时候，没有其他人进行操作。</strong>
EOT;

$lang->convert->selectSource = '选择来源系统及版本';
$lang->convert->source       = '来源系统';
$lang->convert->version      = '版本';

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x');
$lang->convert->sourceList['Mantis'] = array('mantis_1' => '1.x');

$lang->convert->setting     = '进入设置页面';

$lang->convert->checking   = '系统检查';
$lang->convert->ok         = '检查通过(√)';
$lang->convert->fail       = '检查失败(×)';
$lang->convert->loaded     = '已加载';
$lang->convert->unloaded   = '未加载';
$lang->convert->exists     = '目录存在 ';
$lang->convert->notExists  = '目录不存在 ';
$lang->convert->writable   = '目录可写 ';
$lang->convert->notWritable= '目录不可写 ';
$lang->convert->phpINI     = 'PHP配置文件';
$lang->convert->checkItem  = '检查项';
$lang->convert->current    = '当前配置';
$lang->convert->result     = '检查结果';
$lang->convert->action     = '如何修改';

$lang->convert->phpVersion = 'PHP版本';
$lang->convert->phpFail    = 'PHP版本必须大于5.2.0';

$lang->convert->pdo          = 'PDO扩展';
$lang->convert->pdoFail      = '修改PHP配置文件，加载PDO扩展。';
$lang->convert->pdoMySQL     = 'PDO_MySQL扩展';
$lang->convert->pdoMySQLFail = '修改PHP配置文件，加载pdo_mysql扩展。';
$lang->convert->tmpRoot      = '临时文件目录';
$lang->convert->dataRoot     = '上传文件目录';
$lang->convert->mkdir        = '<p>需要创建目录%s。<br /> linux下面命令为：<br /> mkdir -p %s</p>';
$lang->convert->chmod        = '需要修改目录 "%s" 的权限。<br />linux下面命令为：<br />chmod o=rwx -R %s';

$lang->convert->settingDB    = '设置数据库';
$lang->convert->dbHost     = '数据库服务器';
$lang->convert->dbPort     = '服务器端口';
$lang->convert->dbUser     = '数据库用户名';
$lang->convert->dbPassword = '数据库密码';
$lang->convert->dbName     = '%s使用的库';
$lang->convert->dbPrefix   = '%s表前缀';
$lang->convert->clearDB    = '清空现有数据';

$lang->convert->errorConnectDB     = '数据库连接失败 ';
$lang->convert->errorCreateDB      = '数据库创建失败';
$lang->convert->errorCreateTable   = '创建表失败';

$lang->convert->setConfig  = '生成配置文件';
$lang->convert->key        = '配置项';
$lang->convert->value      = '值';
$lang->convert->saveConfig = '保存配置文件';
$lang->convert->save2File  = '拷贝上面文本框中的内容，将其保存到 " %s "中。';
$lang->convert->errorNotSaveConfig = '还没有保存配置文件';

$lang->convert->getPriv  = '设置帐号';
$lang->convert->company  = '公司名称';
$lang->convert->pms      = 'PMS地址';
$lang->convert->pmsNote  = '即通过什么地址可以访问到禅道项目管理，设置域名或者IP地址即可，不需要http';
$lang->convert->account  = '管理员帐号';
$lang->convert->password = '管理员密码';
$lang->convert->errorEmptyPassword = '密码不能为空';

$lang->convert->success = "安装成功！请删除convert.php，登录禅道管理系统，设置用户及分组！";

