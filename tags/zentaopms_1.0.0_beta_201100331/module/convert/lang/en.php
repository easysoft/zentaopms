<?php
/**
 * The convert module english file of ZenTaoMS.
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
 * @copyright   Copyright 2009-2010 Chunsheng Wang
 * @author      Chunsheng Wang <wwccss@263.net>
 * @package     convert
 * @version     $Id$
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

$lang->convert->selectSource     = '选择来源系统及版本';
$lang->convert->source           = '来源系统';
$lang->convert->version          = '版本';
$lang->convert->mustSelectSource = "必须选择一个来源。";

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x');

$lang->convert->setting     = '设置';
$lang->convert->checkConfig = '检查配置';

$lang->convert->ok         = '检查通过(√)';
$lang->convert->fail       = '检查失败(×)';

$lang->convert->settingDB  = '设置数据库';
$lang->convert->dbHost     = '数据库服务器';
$lang->convert->dbPort     = '服务器端口';
$lang->convert->dbUser     = '数据库用户名';
$lang->convert->dbPassword = '数据库密码';
$lang->convert->dbName     = '%s使用的库';
$lang->convert->dbPrefix   = '%s表前缀';
$lang->convert->installPath= '%s安装的根目录';

$lang->convert->checkDB    = '数据库';
$lang->convert->checkTable = '表';
$lang->convert->checkPath  = '安装路径';

$lang->convert->execute    = '执行转换';
$lang->convert->item       = '转换项';
$lang->convert->count      = '转换数量';
$lang->convert->info       = '转换信息';

$lang->convert->bugfree->users    = '用户';
$lang->convert->bugfree->projects = '项目';
$lang->convert->bugfree->modules  = '模块';
$lang->convert->bugfree->bugs     = 'Bug';
$lang->convert->bugfree->actions  = '历史记录';
$lang->convert->bugfree->files    = '附件';

$lang->convert->errorConnectDB     = '数据库连接失败 ';
$lang->convert->errorFileNotExits  = '文件 %s 不存在';
$lang->convert->errorUserExists    = '用户 %s 已存在';
$lang->convert->errorCopyFailed    = '文件 %s 拷贝失败';
