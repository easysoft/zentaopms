<?php
/**
 * The upgrade module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: zh-cn.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
global $config;
$lang->upgrade->common          = '升级';
$lang->upgrade->start           = '开始';
$lang->upgrade->result          = '升级结果';
$lang->upgrade->fail            = '升级失败';
$lang->upgrade->successTip      = '升级成功';
$lang->upgrade->success         = "<p>恭喜您！您的禅道已经成功升级。</p>";
$lang->upgrade->tohome          = '访问禅道';
$lang->upgrade->license         = '禅道项目管理软件已更换授权协议至 Z PUBLIC LICENSE(ZPL) 1.2';
$lang->upgrade->warnning        = '警告';
$lang->upgrade->checkExtension  = '检查插件';
$lang->upgrade->consistency     = '一致性检查';
$lang->upgrade->warnningContent = <<<EOT
<p>升级对数据库权限要求较高，请使用root用户。</p>
<p>升级有危险，请先备份数据库，以防万一。</p>
<pre class='bg-white space-y-2 p-3'>
1. 可以通过phpMyAdmin进行备份。
2. 使用mysql命令行的工具。
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span>
   要将上面红色的部分分别替换成对应的用户名和禅道系统的数据库名。
   比如： mysqldump -u root -p zentao > zentao.bak
</pre>
EOT;

if($config->db->driver == 'dm')
{
    $lang->upgrade->warnningContent = <<<EOT
<p>升级对数据库权限要求较高，请使用管理员用户。<br>
   升级有危险，请先备份数据库，以防万一。</p>
<pre>
1. 可以通过图形化客户端工具进行备份。
2. 使用DIsql工具进行备份。
   $> BACKUP DATABASE BACKUPSET  <span class='text-danger'>'filename'</span>;
   语句执行完后会在默认的备份路径下生成名为“filename”的备份集目录。
   默认的备份路径为 dm.ini 中 BAK_PATH 配置的路径，若未配置 BAK_PATH，则默认使用 SYSTEM_PATH 下的 bak 目录。
   这是最简单的数据库备份语句，如果要设置其他的备份选项需了解联机备份数据库的语法。
</pre>
EOT;
}

$lang->upgrade->createFileWinCMD   = '打开命令行，执行<strong style="color:#ed980f">echo > %s</strong>';
$lang->upgrade->createFileLinuxCMD = '在命令行执行: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>升级之前请先完成下面的操作：</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>或者删掉"<strong style="color:#ed980f">%s</strong>" 这个文件 ，重新创建一个<strong style="color:#ed980f">ok.txt</strong>文件，不需要内容。</li>
                                      </ul>
                                      <p><strong style="color:red">我已经仔细阅读上面提示且完成上述工作，<a href="#" onclick="location.reload()">继续更新</a></strong></p>';

$lang->upgrade->selectVersion = '选择版本';
$lang->upgrade->continue      = '继续';
$lang->upgrade->noteVersion   = "务必选择正确的版本，否则会造成数据丢失。";
$lang->upgrade->fromVersion   = '原来的版本';
$lang->upgrade->toVersion     = '升级到';
$lang->upgrade->confirm       = '确认要执行的SQL语句';
$lang->upgrade->sureExecute   = '确认执行';
$lang->upgrade->upgradingTips = '正在升级中，请耐心等待，切勿刷新页面、断电、关机！';
$lang->upgrade->forbiddenExt  = '以下插件与新版本不兼容，已经自动禁用：';
$lang->upgrade->updateFile    = '需要更新附件信息。';
$lang->upgrade->noticeSQL     = '检查到你的数据库跟标准不一致，尝试修复失败。请执行以下SQL语句，再刷新页面检查。';
$lang->upgrade->afterDeleted  = '请执行上面命令删除文件， 删除后刷新！';
$lang->upgrade->afterExec     = '请根据以上报错信息手动修改数据库，修改后刷新！';
$lang->upgrade->mergeProgram  = '数据迁移';
$lang->upgrade->mergeTips     = '数据迁移提示';
$lang->upgrade->toPMS15Guide  = '禅道开源版15版本升级';
$lang->upgrade->toPRO10Guide  = '禅道专业版10版本升级';
$lang->upgrade->toBIZ5Guide   = '禅道企业版5版本升级';
$lang->upgrade->toMAXGuide    = '禅道旗舰版版本升级';

$lang->upgrade->line            = '产品线';
$lang->upgrade->allLines        = "所有产品线";
$lang->upgrade->program         = '目标项目集和项目';
$lang->upgrade->existProgram    = '已有项目集';
$lang->upgrade->existProject    = '已有项目';
$lang->upgrade->existLine       = '已有产品线';
$lang->upgrade->product         = $lang->productCommon;
$lang->upgrade->project         = '迭代';
$lang->upgrade->repo            = '版本库';
$lang->upgrade->mergeRepo       = '归并版本库';
$lang->upgrade->setProgram      = '设置项目所属项目集';
$lang->upgrade->setProject      = "设置{$lang->executionCommon}所属项目";
$lang->upgrade->dataMethod      = '数据迁移方式';
$lang->upgrade->selectMergeMode = '请选择数据归并方式';
$lang->upgrade->mergeMode       = '数据归并方式：';
$lang->upgrade->begin           = '开始日期';
$lang->upgrade->end             = '结束日期';
$lang->upgrade->unknownDate     = '无明确时间的项目';
$lang->upgrade->selectProject   = '目标项目';
$lang->upgrade->programName     = '项目集名称';
$lang->upgrade->projectName     = '项目名称';
$lang->upgrade->compatibleEXT   = '扩展机制兼容';
$lang->upgrade->fileName        = '文件名称';
$lang->upgrade->next            = '下一步';
$lang->upgrade->back            = '上一步';

$lang->upgrade->newProgram        = '新建';
$lang->upgrade->editedName        = '调整后名称';
$lang->upgrade->projectEmpty      = '所属项目不能为空！';
$lang->upgrade->mergeSummary      = "尊敬的用户，您的系统中共有%s等待迁移。";
$lang->upgrade->productCount      = "%s个{$lang->productCommon}";
$lang->upgrade->projectCount      = "%s个{$lang->projectCommon}";
$lang->upgrade->mergeByProject    = "当前提供如下2种数据迁移方式，如果历史的{$lang->projectCommon}都是长周期的，那么我们建议把历史的{$lang->projectCommon}作为项目升级。</br>如果历史的{$lang->projectCommon}都是短周期的，那么我们建议把历史的{$lang->projectCommon}作为{$lang->executionCommon}升级。";
$lang->upgrade->mergeRepoTips     = "将选中的版本库归并到所选产品下。";
$lang->upgrade->needBuild4Add     = '本次升级需要创建索引。请到 [后台->系统设置->重建索引] 页面，重新创建索引。';
$lang->upgrade->needChangeEngine  = '本次升级需要更换表引擎， [后台->系统设置->表引擎] 页面更换引擎。';
$lang->upgrade->errorEngineInnodb = '您当前的数据库不支持使用InnoDB数据表引擎，请修改为MyISAM后重试。';
$lang->upgrade->duplicateProject  = "同一个项目集内项目名称不能重复，请调整重名的项目名称";
$lang->upgrade->upgradeTips       = "历史删除数据不参与升级，升级后将不支持还原，请知悉";
$lang->upgrade->moveEXTFileFail   = '迁移文件失败， 请执行上面命令后刷新！';
$lang->upgrade->deleteDirTip      = '升级后，如下文件夹会影响系统功能的使用，请删除。';
$lang->upgrade->errorNoProduct    = "请选择需要归并的{$lang->productCommon}。";
$lang->upgrade->errorNoExecution  = "请选择需要归并的{$lang->projectCommon}。";
$lang->upgrade->moveExtFileTip    = <<<EOT
<p>新版本将对历史的定制/插件进行扩展机制兼容处理，需要将定制/插件相关的文件迁移到extension/custom下，否则定制/插件功能将无法使用。</p>
<p>请您确认系统是否有做过定制/插件，如没有做过定制/插件，可取消勾选如下文件；如果不清楚是否做过定制/插件，也可保持文件勾选。</p>
EOT;

$lang->upgrade->projectType['project']   = "把历史的{$lang->projectCommon}作为项目升级";
$lang->upgrade->projectType['execution'] = "把历史的{$lang->projectCommon}作为{$lang->executionCommon}升级";

$lang->upgrade->createProjectTip = <<<EOT
<p>升级后历史的{$lang->projectCommon}一一对应新版本中的项目。</p>
<p>系统会根据历史{$lang->projectCommon}分别创建一个与该{$lang->projectCommon}同名的{$lang->executionCommon}，并将之前{$lang->projectCommon}的任务、需求、Bug等数据迁移至{$lang->executionCommon}中。</p>
EOT;

$lang->upgrade->createExecutionTip = <<<EOT
<p>系统会把历史的{$lang->projectCommon}作为{$lang->executionCommon}进行升级。</p>
<p>升级后历史的{$lang->projectCommon}数据将对应新版本中项目下的{$lang->executionCommon}。</p>
EOT;

$lang->upgrade->mergeModes = array();
$lang->upgrade->mergeModes['project']   = "自动归并数据，将历史的{$lang->projectCommon}作为项目升级";
$lang->upgrade->mergeModes['execution'] = "自动归并数据，将历史的{$lang->projectCommon}作为{$lang->executionCommon}升级";
$lang->upgrade->mergeModes['manually']  = '手工归并数据';

$lang->upgrade->mergeProjectTip   = "历史的{$lang->projectCommon}将直接同步到新版本的项目中，同时系统将会根据历史{$lang->projectCommon}分别创建一个与该{$lang->projectCommon}同名的{$lang->executionCommon}，并将之前{$lang->projectCommon}下的任务、需求、Bug等数据迁移至{$lang->executionCommon}中。";
$lang->upgrade->mergeExecutionTip = "系统将自动按年创建项目，将历史的{$lang->projectCommon}数据按照年份归并到对应的项目下。";
$lang->upgrade->createProgramTip  = "同时系统将自动创建一个默认的项目集，将所有的{$lang->projectCommon}都放在默认的项目集下。";
$lang->upgrade->mergeManuallyTip  = '可以手工选择数据归并的方式。';

$lang->upgrade->defaultGroup = '默认分组';

include dirname(__FILE__) . '/version.php';

$lang->upgrade->recoveryActions = new stdclass();
$lang->upgrade->recoveryActions->cancel = '取消';
$lang->upgrade->recoveryActions->review = '评审';

$lang->upgrade->remark     = '备注';
$lang->upgrade->remarkDesc = '后续您还可以在禅道的后台-系统设置-模式中进行切换。';

$lang->upgrade->upgradingTip = '系统正在升级中，请耐心等待...';
