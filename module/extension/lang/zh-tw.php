<?php
/**
 * The extension module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2011 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->extension->common       = '插件管理';
$lang->extension->browse       = '瀏覽插件';
$lang->extension->install      = '安裝插件';
$lang->extension->installAuto  = '自動安裝';
$lang->extension->installForce = '強制安裝';
$lang->extension->uninstall    = '卸載';
$lang->extension->activate     = '激活';
$lang->extension->deactivate   = '禁用';
$lang->extension->obtain       = '獲得插件';
$lang->extension->download     = '下載插件';
$lang->extension->downloadAB   = '下載';
$lang->extension->upload       = '上傳插件';
$lang->extension->erase        = '清除';
$lang->extension->upgrade      = '升級';

$lang->extension->installed   = '已安裝';
$lang->extension->deactivated = '已禁用';
$lang->extension->available   = '可安裝';

$lang->extension->id          = '編號';
$lang->extension->name        = '名稱';
$lang->extension->code        = '插件代號';
$lang->extension->version     = '版本';
$lang->extension->author      = '作者';
$lang->extension->license     = '授權';
$lang->extension->intro       = '詳情';
$lang->extension->abstract    = '簡介';
$lang->extension->site        = '官網';
$lang->extension->addedTime   = '添加時間';
$lang->extension->updatedTime = '更新時間';
$lang->extension->downloads   = '下載量';
$lang->extension->public      = '直接下載';
$lang->extension->compatible  = '兼容性';

$lang->extension->publicList[0] = '手工下載';
$lang->extension->publicList[1] = '直接下載';

$lang->extension->compatibleList[0] = '不兼容';
$lang->extension->compatibleList[1] = '兼容';

$lang->extension->byDownloads   = '最多下載';
$lang->extension->byAddedTime   = '最新添加';
$lang->extension->byUpdatedTime = '最近更新';
$lang->extension->bySearch      = '搜索';
$lang->extension->byCategory    = '分類瀏覽';

$lang->extension->installFailed            = '安裝失敗，錯誤原因如下:';
$lang->extension->installFinished          = '恭喜您，插件順利的安裝成功！';
$lang->extension->refreshPage              = '刷新頁面';
$lang->extension->uninstallFinished        = '插件已經成功卸載';
$lang->extension->deactivateFinished       = '插件已經成功禁用';
$lang->extension->activateFinished         = '插件已經成功激活';
$lang->extension->eraseFinished            = '插件已經成功清除';
$lang->extension->unremovedFiles           = '有一些檔案或目錄未能刪除，需要手工刪除';
$lang->extension->executeCommands          = '<h3>執行下面的命令來修正這些問題：</h3>';
$lang->extension->successDownloadedPackage = '成功下載插件';
$lang->extension->successCopiedFiles       = '成功拷貝檔案';
$lang->extension->successInstallDB         = '成功安裝資料庫';
$lang->extension->viewInstalled            = '查看已安裝插件';
$lang->extension->viewAvailable            = '查看可安裝插件';
$lang->extension->viewDeactivated          = '查看已禁用插件';

$lang->extension->errorGetModules              = '從www.zentao.net獲得插件分類失敗。可能是因為網絡方面的原因，請檢查後重新刷新頁面。';
$lang->extension->errorGetExtensions           = '從www.zentao.net獲得插件失敗。可能是因為網絡方面的原因，您可以到<a href="http://www.zentao.net/extension/" target="_blank">www.zentao.net</a>手工下載插件，然後上傳安裝。';
$lang->extension->errorDownloadPathNotFound    = '插件下載存儲路徑<strong>%s</strong>不存在。<br />linux下面請執行命令：<strong>mkdir -p %s</strong>來修正。';
$lang->extension->errorDownloadPathNotWritable = '插件下載存儲路徑<strong>%s</strong>不可寫。<br />linux下面請執行命令：<strong>sudo chmod 777 %s</strong>來修正。';
$lang->extension->errorPackageFileExists       = '下載路徑已經有一個名為的<strong>%s</strong>附件。<h3>重新安裝，<a href="%s">請點擊此連結</a></h3>';
$lang->extension->errorDownloadFailed          = '下載失敗，請重新下載。如果多次重試還不行，請嘗試手工下載，然後通過上傳功能上傳。';
$lang->extension->errorMd5Checking             = '下載檔案不完整，請重新下載。如果多次重試還不行，請嘗試手工下載，然後通過上傳功能上傳。';
$lang->extension->errorExtracted               = '包檔案<strong> %s </strong>解壓縮失敗，可能不是一個有效的zip檔案。錯誤信息如下：<br />%s';
$lang->extension->errorCheckIncompatible       = '該插件與禪道版本不兼容，安裝後可能無法使用。。<h3>您可以選擇 <a href="%s">強制安裝</a> 或者 <a href="#" onclick=parent.location.href="%s">取消安裝</a></h3>';
$lang->extension->errorFileConflicted          = '有以下安裝檔案衝突：<br />%s <h3>您可以選擇 <a href="%s">覆蓋安裝</a> 或者 <a href="#" onclick=parent.location.href="%s">取消安裝</a></h3>';
$lang->extension->errorPackageNotFound         = '包檔案 <strong>%s </strong>沒有找到，可能是因為自動下載失敗。您可以嘗試再次下載。';
$lang->extension->errorTargetPathNotWritable   = '目標路徑 <strong>%s </strong>不可寫。';
$lang->extension->errorTargetPathNotExists     = '目標路徑 <strong>%s </strong>不存在。';
$lang->extension->errorInstallDB               = '執行資料庫語句失敗。錯誤信息如下：%s';
