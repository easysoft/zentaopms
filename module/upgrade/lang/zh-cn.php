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
$lang->upgrade->common  = '升级';
$lang->upgrade->result  = '升级结果';
$lang->upgrade->fail    = '升级失败';
$lang->upgrade->success = "<p><i class='icon icon-check-circle'></i></p><p>恭喜您！</p><p>您的禅道已经成功升级。</p>";
$lang->upgrade->tohome  = '访问禅道';
$lang->upgrade->license = '禅道项目管理软件已更换授权协议至 Z PUBLIC LICENSE(ZPL) 1.2';
$lang->upgrade->warnning= '警告';
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
$lang->upgrade->to20Demo       = '查看20版本Demo';
$lang->upgrade->demoURL        = 'https://demo.qcmmi.com';
$lang->upgrade->videoURL       = 'http://zentao20.demo.zentao.net/zentao20.mp4';
$lang->upgrade->to20Tips       = '禅道20版本升级提示';
$lang->upgrade->to20Button     = '我已经做好备份，开始升级吧！';
$lang->upgrade->to20TipsHeader = "<p>尊敬的用户，感谢对禅道的支持。自20版本开始，禅道全面升级成为通用的项目管理平台。相关介绍请看如下视频(如视频无法正常播放，请直接访问 <a href='http://zentao20.demo.zentao.net/zentao20.mp4' target='_blank'><u>禅道20版本介绍</u></a>)：</p><br />";
$lang->upgrade->to20Desc       = <<<EOD
<div class='text-warning'>
  <p>友情提示：</p>
  <ol>
    <li>您可以先安装一个20版本的禅道，体验一下里边的概念和流程。</li>
    <li>20版本禅道改动比较大，升级之前请做好备份。</li>
    <li>请放心升级，即使第一次升级不到位，后续还可以再调整，不会影响系统数据。</li>
  </ol>
</div>
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

$lang->upgrade->line     = '产品线';
$lang->upgrade->program  = '目标项目集和项目';
$lang->upgrade->existPGM = '已有项目集';
$lang->upgrade->existPRJ = '已有项目';
$lang->upgrade->product  = $lang->productCommon;
$lang->upgrade->project  = '迭代';

$lang->upgrade->newProgram         = '新建';
$lang->upgrade->mergeSummary       = "尊敬的用户，您的系统中共有%s个产品，%s个迭代等待迁移。";
$lang->upgrade->mergeByProductLine = "以产品线组织的产品和迭代：将整个产品线及其下面的产品和迭代归并到一个项目集和项目中，也可以分开归并。";
$lang->upgrade->mergeByProduct     = "以产品组织的迭代：可以选择多个产品及其下面的迭代归并到一个项目集和项目中，也可以选择某一个产品将其下面所属的迭代归并到项目集和项目中。";
$lang->upgrade->mergeByProject     = "独立的迭代：可以选择若干迭代归并到一个项目中，也可以独立归并。";
$lang->upgrade->mergeByMoreLink    = "关联多个产品的迭代：选择这个迭代归属于哪一个产品。";

include dirname(__FILE__) . '/version.php';
