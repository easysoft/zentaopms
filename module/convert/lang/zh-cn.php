<?php
/**
 * The convert module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id$
 * @link        http://www.zentao.net
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

$lang->convert->direction             = '请选择项目问题转换方向';
$lang->convert->questionTypeOfRedmine = 'Redmine中问题类型';
$lang->convert->aimTypeOfZentao       = '转化为Zentao中的类型';

$lang->convert->directionList['bug']   = 'Bug';
$lang->convert->directionList['task']  = '任务';
$lang->convert->directionList['story'] = '需求';

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');
$lang->convert->sourceList['Redmine'] = array('Redmine_1.1' => '1.1');

$lang->convert->setting     = '设置';
$lang->convert->checkConfig = '检查配置';

$lang->convert->ok          = '检查通过(√)';
$lang->convert->fail        = '检查失败(×)';

$lang->convert->settingDB   = '设置数据库';
$lang->convert->dbHost      = '数据库服务器';
$lang->convert->dbPort      = '服务器端口';
$lang->convert->dbUser      = '数据库用户名';
$lang->convert->dbPassword  = '数据库密码';
$lang->convert->dbName      = '%s使用的库';
$lang->convert->dbCharset   = '%s数据库编码';
$lang->convert->dbPrefix    = '%s表前缀';
$lang->convert->installPath = '%s安装的根目录';

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
$lang->convert->bugfree->cases    = '测试用例';
$lang->convert->bugfree->results  = '测试结果';
$lang->convert->bugfree->actions  = '历史记录';
$lang->convert->bugfree->files    = '附件';

$lang->convert->redmine->users        = '用户';
$lang->convert->redmine->groups       = '用户分组';
$lang->convert->redmine->products     = '产品';
$lang->convert->redmine->projects     = '项目';
$lang->convert->redmine->stories      = '需求';
$lang->convert->redmine->tasks        = '任务';
$lang->convert->redmine->bugs         = 'Bug';
$lang->convert->redmine->productPlans = '产品计划';
$lang->convert->redmine->teams        = '团队';
$lang->convert->redmine->releases     = '发布';
$lang->convert->redmine->builds       = 'Build';
$lang->convert->redmine->docLibs      = '文档库';
$lang->convert->redmine->docs         = '文档';
$lang->convert->redmine->files        = '附件';

$lang->convert->errorConnectDB     = '数据库连接失败 ';
$lang->convert->errorFileNotExits  = '文件 %s 不存在';
$lang->convert->errorUserExists    = '用户 %s 已存在';
$lang->convert->errorGroupExists   = '分组 %s 已存在';
$lang->convert->errorBuildExists   = 'Build %s 已存在';
$lang->convert->errorReleaseExists = '发布 %s 已存在';
$lang->convert->errorCopyFailed    = '文件 %s 拷贝失败';

$lang->convert->setParam = '请设置转换参数';

$lang->convert->aimType           = '问题类型转换';
$lang->convert->statusType->bug   = '状态类型转换(Bug状态)';
$lang->convert->statusType->story = '状态类型转换(Story状态)';
$lang->convert->statusType->task  = '状态类型转换(Task状态)';
$lang->convert->priType->bug      = '优先级类型转换(Bug状态)';
$lang->convert->priType->story    = '优先级类型转换(Story状态)';
$lang->convert->priType->task     = '优先级类型转换(Task状态)';

$lang->convert->issue->redmine = 'Redmine';
$lang->convert->issue->zentao  = '禅道';
$lang->convert->issue->goto    = '转换为';
