<?php
/**
 * The upgrade module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     upgrade
 * @version     $Id: zh-cn.php 5119 2013-07-12 08:06:42Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
$lang->upgrade->common          = '升级';
$lang->upgrade->start           = '开始';
$lang->upgrade->result          = '升级结果';
$lang->upgrade->fail            = '升级失败';
$lang->upgrade->successTip      = '升级成功';
$lang->upgrade->success         = "<p><i class='icon icon-check-circle'></i></p><p>恭喜您！</p><p>您的禅道已经成功升级。</p>";
$lang->upgrade->tohome          = '访问禅道';
$lang->upgrade->license         = '禅道项目管理软件已更换授权协议至 Z PUBLIC LICENSE(ZPL) 1.2';
$lang->upgrade->warnning        = '警告';
$lang->upgrade->checkExtension  = '检查插件';
$lang->upgrade->consistency     = '一致性检查';
$lang->upgrade->warnningContent = <<<EOT
<p>升级有危险，请先备份数据库，以防万一。</p>
<pre>
1. 可以通过phpMyAdmin进行备份。
2. 使用mysql命令行的工具。
   $> mysqldump -u <span class='text-danger'>username</span> -p <span class='text-danger'>dbname</span> > <span class='text-danger'>filename</span>
   要将上面红色的部分分别替换成对应的用户名和禅道系统的数据库名。
   比如： mysqldump -u root -p zentao >zentao.bak
</pre>
EOT;

$lang->upgrade->createFileWinCMD   = '打开命令行，执行<strong style="color:#ed980f">echo > %s</strong>';
$lang->upgrade->createFileLinuxCMD = '在命令行执行: <strong style="color:#ed980f">touch %s</strong>';
$lang->upgrade->setStatusFile      = '<h4>升级之前请先完成下面的操作：</h4>
                                      <ul style="line-height:1.5;font-size:13px;">
                                      <li>%s</li>
                                      <li>或者删掉"<strong style="color:#ed980f">%s</strong>" 这个文件 ，重新创建一个<strong style="color:#ed980f">ok.txt</strong>文件，不需要内容。</li>
                                      </ul>
                                      <p><strong style="color:red">我已经仔细阅读上面提示且完成上述工作，<a href="#" onclick="location.reload()">继续更新</a></strong></p>';

$lang->upgrade->selectVersion  = '选择版本';
$lang->upgrade->continue       = '继续';
$lang->upgrade->noteVersion    = "务必选择正确的版本，否则会造成数据丢失。";
$lang->upgrade->fromVersion    = '原来的版本';
$lang->upgrade->toVersion      = '升级到';
$lang->upgrade->confirm        = '确认要执行的SQL语句';
$lang->upgrade->sureExecute    = '确认执行';
$lang->upgrade->forbiddenExt   = '以下插件与新版本不兼容，已经自动禁用：';
$lang->upgrade->updateFile     = '需要更新附件信息。';
$lang->upgrade->noticeSQL      = '检查到你的数据库跟标准不一致，尝试修复失败。请执行以下SQL语句，再刷新页面检查。';
$lang->upgrade->afterDeleted   = '以上文件未能删除， 删除后刷新！';
$lang->upgrade->mergeProgram   = '数据迁移';
$lang->upgrade->mergeTips      = '数据迁移提示';
$lang->upgrade->toPMS15Guide   = '禅道开源版15版本升级';
$lang->upgrade->toPRO10Guide   = '禅道专业版10版本升级';
$lang->upgrade->toBIZ5Guide    = '禅道企业版5版本升级';
$lang->upgrade->toMAXGuide     = '禅道旗舰版版本升级';
$lang->upgrade->to15Desc       = <<<EOD
<p>尊敬的用户，禅道从15版本开始对导航和概念做了调整，主要改动如下：</p>
<ol>
<p><li>增加了项目集概念。一个项目集可以包括多个产品和多个项目。</li></p>
<p><li>细分了项目和迭代概念，一个项目可以包含多个迭代。</li></p>
<p><li>导航增加了左侧菜单并支持多页面操作。</li></p>
</ol>
<br/>
<p>您可以在线体验最新版本的功能，以决定是否启用的模式：<a class='text-info' href='http://zentaomax.demo.zentao.net' target='_blank'>演示demo</a></p>
</br>
<p><strong>请问您计划如何使用禅道的新版本呢？</strong></p>
EOD;

$lang->upgrade->mergeProgramDesc = <<<EOD
<p>接下来我们会把之前历史产品和迭代数据迁移到项目集和项目下，迁移的方案如下：</p><br />
<h4>方案一：以产品线组织的产品和迭代 </h4>
<p>可以将整个产品线及其下面的产品和迭代迁移到一个项目集和项目中，当然您也可以根据需要分开迁移。</p>
<h4>方案二：以产品组织的迭代 </h4>
<p>可以选择多个产品及其下面的迭代迁移到一个项目集和项目中，也可以选择某一个产品和产品下面的迭代迁移到项目集和项目中。</p>
<h4>方案三：独立的迭代</h4>
<p>可以选择若干个迭代迁移到一个项目集中，也可以独立迁移。</p>
<h4>方案四：关联多个产品的迭代</h4>
<p>可以选择这些迭代归属于某个新项目下。</p>
EOD;

$lang->upgrade->to15Mode['classic'] = '保持老版本的习惯';
$lang->upgrade->to15Mode['new']     = '全新项目集管理模式';

$lang->upgrade->selectedModeTips['classic'] = '后续您还可以在后台-自定义里面切换为全新项目集管理的模式。';
$lang->upgrade->selectedModeTips['new']     = '切换为项目集管理模式需要对之前的数据进行归并处理，系统会引导您完成这个操作。';

$lang->upgrade->line         = '产品线';
$lang->upgrade->program      = '目标项目集和项目';
$lang->upgrade->existProgram = '已有项目集';
$lang->upgrade->existProject = '已有项目';
$lang->upgrade->existLine    = '已有' . $lang->productCommon . '线';
$lang->upgrade->product      = $lang->productCommon;
$lang->upgrade->project      = '迭代';
$lang->upgrade->repo         = '版本库';
$lang->upgrade->mergeRepo    = '归并版本库';

$lang->upgrade->newProgram         = '新建';
$lang->upgrade->projectEmpty       = '所属项目不能为空！';
$lang->upgrade->mergeSummary       = "尊敬的用户，您的系统中共有%s个产品，%s个迭代等待迁移。";
$lang->upgrade->mergeByProductLine = "以产品线组织的产品和迭代：将整个产品线及其下面的产品和迭代归并到一个项目集和项目中，也可以分开归并。";
$lang->upgrade->mergeByProduct     = "以产品组织的迭代：可以选择多个产品及其下面的迭代归并到一个项目集和项目中，也可以选择某一个产品将其下面所属的迭代归并到项目集和项目中。";
$lang->upgrade->mergeByProject     = "独立的迭代：可以选择若干迭代归并到一个项目中，也可以独立归并。";
$lang->upgrade->mergeByMoreLink    = "关联多个产品的迭代：选择这个迭代归属于哪一个产品。";
$lang->upgrade->mergeRepoTips      = "将选中的版本库归并到所选产品下。";

$lang->upgrade->needBuild4Add    = '本次升级新增全文检索功能，需要创建索引。';
$lang->upgrade->needBuild4Adjust = '本次升级全文检索功能有调整，需要创建索引。';
$lang->upgrade->buildIndex       = '创建索引';

include dirname(__FILE__) . '/version.php';
