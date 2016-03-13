<?php
/**
 * The extension module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->extension->common        = '插件管理';
$lang->extension->browse        = '浏览插件';
$lang->extension->install       = '安装插件';
$lang->extension->installAuto   = '自动安装';
$lang->extension->installForce  = '强制安装';
$lang->extension->uninstall     = '卸载';
$lang->extension->activate      = '激活';
$lang->extension->deactivate    = '禁用';
$lang->extension->obtain        = '获得插件';
$lang->extension->view          = '详情';
$lang->extension->downloadAB    = '下载';
$lang->extension->upload        = '本地安装';
$lang->extension->erase         = '清除';
$lang->extension->upgrade       = '升级插件';
$lang->extension->agreeLicense  = '我同意该授权';

$lang->extension->structure   = '目录结构';
$lang->extension->installed   = '已安装';
$lang->extension->deactivated = '被禁用';
$lang->extension->available   = '已下载';

$lang->extension->version     = '版本';
$lang->extension->compatible  = '适用版本';
$lang->extension->latest      = '<small>最新版本<strong><a href="%s" target="_blank" class="extension">%s</a></strong>，兼容禅道<a href="http://api.zentao.net/goto.php?item=latest" target="_blank" class="alert-link"><strong>%s</strong></a></small>';
$lang->extension->author      = '作者';
$lang->extension->license     = '授权';
$lang->extension->site        = '官网';
$lang->extension->downloads   = '下载量';
$lang->extension->compatible  = '兼容性';
$lang->extension->grade       = '评分';
$lang->extension->depends     = '依赖';

$lang->extension->publicList[0] = '手工下载';
$lang->extension->publicList[1] = '直接下载';

$lang->extension->compatibleList[0] = '未知';
$lang->extension->compatibleList[1] = '兼容';

$lang->extension->byDownloads   = '最多下载';
$lang->extension->byAddedTime   = '最新添加';
$lang->extension->byUpdatedTime = '最近更新';
$lang->extension->bySearch      = '搜索';
$lang->extension->byCategory    = '分类浏览';

$lang->extension->installFailed            = '%s失败，错误原因如下:';
$lang->extension->uninstallFailed          = '卸载失败，错误原因如下:';
$lang->extension->confirmUninstall         = '卸载插件会删除或修改相关的数据库，是否继续卸载？';
$lang->extension->installFinished          = '恭喜您，插件顺利的%s成功！';
$lang->extension->refreshPage              = '刷新页面';
$lang->extension->uninstallFinished        = '插件已经成功卸载';
$lang->extension->deactivateFinished       = '插件已经成功禁用';
$lang->extension->activateFinished         = '插件已经成功激活';
$lang->extension->eraseFinished            = '插件已经成功清除';
$lang->extension->unremovedFiles           = '有一些文件或目录未能删除，需要手工删除';
$lang->extension->executeCommands          = '<h3>执行下面的命令来修正这些问题：</h3>';
$lang->extension->successDownloadedPackage = '成功下载插件';
$lang->extension->successCopiedFiles       = '成功拷贝文件';
$lang->extension->successInstallDB         = '成功安装数据库';
$lang->extension->viewInstalled            = '查看已安装插件';
$lang->extension->viewAvailable            = '查看可安装插件';
$lang->extension->viewDeactivated          = '查看已禁用插件';
$lang->extension->backDBFile               = '插件相关数据已经备份到 %s 文件中！';
$lang->extension->noticeOkFile             = '<h5>为了安全起见，系统需要确认您的管理员身份</h5>
    <h5>请登录禅道所在的服务器，创建%s文件。</h5>
    <p>注意：</p>
    <ol>
    <li>文件内容为空。</li>
    <li>如果之前文件存在，删除之后重新创建。</li>
    </ol>'; 

$lang->extension->upgradeExt     = '升级';
$lang->extension->installExt     = '安装';
$lang->extension->upgradeVersion = '（从%s升级到%s）';

$lang->extension->waring = '警告';

$lang->extension->errorOccurs                  = '错误：';
$lang->extension->errorGetModules              = '从www.zentao.net获得插件分类失败。可能是因为网络方面的原因，请检查后重新刷新页面。';
$lang->extension->errorGetExtensions           = '从www.zentao.net获得插件失败。可能是因为网络方面的原因，您可以到 <a href="http://www.zentao.net/extension/" target="_blank" class="alert-link">www.zentao.net</a> 手工下载插件，然后上传安装。';
$lang->extension->errorDownloadPathNotFound    = '插件下载存储路径<strong>%s</strong>不存在。<br />linux下面请执行命令：<strong>mkdir -p %s</strong>来修正。';
$lang->extension->errorDownloadPathNotWritable = '插件下载存储路径<strong>%s</strong>不可写。<br />linux下面请执行命令：<strong>sudo chmod 777 %s</strong>来修正。';
$lang->extension->errorPackageFileExists       = '下载路径已经有一个名为的<strong>%s</strong>附件。<h5>重新%s，<a href="%s" class="alert-link">请点击此链接</a></h5>';
$lang->extension->errorDownloadFailed          = '下载失败，请重新下载。如果多次重试还不行，请尝试手工下载，然后通过上传功能上传。';
$lang->extension->errorMd5Checking             = '下载文件不完整，请重新下载。如果多次重试还不行，请尝试手工下载，然后通过上传功能上传。';
$lang->extension->errorExtracted               = '包文件<strong> %s </strong>解压缩失败，可能不是一个有效的zip文件。错误信息如下：<br />%s';
$lang->extension->errorCheckIncompatible       = '该插件与禅道版本不兼容，%s后可能无法使用。<h3>您可以选择 <a href="%s">强制%s</a> 或者 <a href="#" onclick=parent.location.href="%s">取消</a></h3>';
$lang->extension->errorFileConflicted          = '有以下文件冲突：<br />%s <h3>您可以选择 <a href="%s">覆盖</a> 或者 <a href="#" onclick=parent.location.href="%s">取消</a></h3>';
$lang->extension->errorPackageNotFound         = '包文件 <strong>%s </strong>没有找到，可能是因为自动下载失败。您可以尝试再次下载。';
$lang->extension->errorTargetPathNotWritable   = '目标路径 <strong>%s </strong>不可写。';
$lang->extension->errorTargetPathNotExists     = '目标路径 <strong>%s </strong>不存在。';
$lang->extension->errorInstallDB               = '执行数据库语句失败。错误信息如下：%s';
$lang->extension->errorConflicts               = '与插件“%s”冲突！';
$lang->extension->errorDepends                 = '以下依赖插件没有安装或版本不正确：<br /><br /> %s';
$lang->extension->errorIncompatible            = '该插件与您的禅道版本不兼容';
$lang->extension->errorUninstallDepends        = '插件“%s”依赖该插件，不能卸载';
