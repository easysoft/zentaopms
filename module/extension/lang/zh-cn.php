<?php
/**
 * The extension module zh-cn file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->extension->common    = '插件管理';
$lang->extension->browse    = '浏览插件';
$lang->extension->install   = '安装插件';
$lang->extension->installAB = '安装';
$lang->extension->uninstall = '卸载';
$lang->extension->activate  = '激活';
$lang->extension->deactivate= '禁用';
$lang->extension->obtain    = '获得插件';
$lang->extension->download  = '下载插件';
$lang->extension->downloadAB= '下载';
$lang->extension->upload    = '上传插件';
$lang->extension->erase     = '清除';

$lang->extension->installed   = '已安装';
$lang->extension->deactivated = '已禁用';
$lang->extension->available   = '可安装';

$lang->extension->id          = '编号';
$lang->extension->name        = '名称';
$lang->extension->version     = '版本';
$lang->extension->author      = '作者';
$lang->extension->license     = '授权';
$lang->extension->desc        = '描述';
$lang->extension->site        = '官方网站';
$lang->extension->addedTime   = '添加时间';
$lang->extension->updatedTime = '更新时间';
$lang->extension->downloads   = '下载';

$lang->extension->byDownloads   = '最多下载';
$lang->extension->byAddedTime   = '最新添加';
$lang->extension->byUpdatedTime = '最近更新';
$lang->extension->bySearch      = '搜索';
$lang->extension->byCategory    = '分类浏览';

$lang->extension->installFailed            = '安装失败，错误原因如下:';
$lang->extension->installFinished          = '恭喜您，插件顺利的安装成功！';
$lang->extension->refreshPage              = '刷新页面';
$lang->extension->uninstallFinished        = '插件已经成功卸载';
$lang->extension->deactivateFinished       = '插件已经成功禁用';
$lang->extension->activateFinished         = '插件已经成功激活';
$lang->extension->eraseFinished            = '插件已经成功清除';
$lang->extension->unremovedFiles           = '有一些文件或目录未能删除，需要手工删除';
$lang->extension->refreshPage              = '刷新页面';
$lang->extension->executeCommands          = '<h3>执行下面的命令来修正这些问题：</h3>';
$lang->extension->successDownloadedPackage = '成功下载插件';
$lang->extension->successCopiedFiles       = '成功拷贝文件';
$lang->extension->successInstallDB         = '成功安装数据库';
$lang->extension->viewInstalled            = '查看已安装插件';
$lang->extension->viewAvailable            = '查看可安装插件';
$lang->extension->viewDeactivated          = '查看已禁用插件';

$lang->extension->errorDownloadPathNotFound    = '插件下载存储路径<strong>%s</strong>不存在。<br />linux下面请执行命令：<strong>mkdir %s</strong>来修正。';
$lang->extension->errorDownloadPathNotWritable = '插件下载存储路径<strong>%s</strong>不可写。<br />linux下面请执行命令：<strong>sudo chmod 777 %s</strong>来修正。';
$lang->extension->errorPackageFileExists       = '下载路径已经有一个名为的<strong>%s</strong>附件。<h3>重新安装，<a href="%s">请点击此链接</a></h3>';
$lang->extension->errorDownloadFailed          = '下载失败，请重新下载。如果多次重试还不行，请尝试手工下载，然后通过上传功能上传。';
$lang->extension->errorMd5Checking             = '下载文件不完整，请重新下载。如果多次重试还不行，请尝试手工下载，然后通过上传功能上传。';
$lang->extension->errorExtracted               = '包文件<strong> %s </strong>解压缩失败，可能不是一个有效的zip文件。错误信息如下：<br />%s';
$lang->extension->errorRepeatFile              = '有以下安装文件重复：<br />%s。<h3>是否继续安装，<a href="%s">覆盖安装</a>&nbsp;&nbsp;<a href="#" onclick=parent.location.href="%s">取消</a></h3>';
$lang->extension->errorChangeFile              = '有以下安装文件做过改动：<br />%s。<h3>是否继续，<a href="%s">确定删除</a>&nbsp;&nbsp;<a href="#" onclick=parent.location.href="%s">取消</a></h3>';
$lang->extension->errorPackageNotFound         = '包文件 <strong>%s </strong>没有找到，可能是因为自动下载失败。您可以尝试再次下载。';
$lang->extension->errorTargetPathNotWritable   = '目标路径 <strong>%s </strong>不可写。';
$lang->extension->errorTargetPathNotExists     = '目标路径 <strong>%s </strong>不存在。';
$lang->extension->errorInstallDB               = '执行数据库语句失败。错误信息如下：%s';
