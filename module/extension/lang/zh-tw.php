<?php
/**
 * The extension module zh-tw file of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青島易軟天創網絡科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     extension
 * @version     $Id$
 * @link        http://www.zentao.net
 */
$lang->extension->common        = '插件管理';
$lang->extension->browse        = '瀏覽插件';
$lang->extension->install       = '安裝插件';
$lang->extension->installAuto   = '自動安裝';
$lang->extension->installForce  = '強制安裝';
$lang->extension->uninstall     = '卸載';
$lang->extension->activate      = '激活';
$lang->extension->deactivate    = '禁用';
$lang->extension->obtain        = '獲得插件';
$lang->extension->view          = '詳情';
$lang->extension->download      = '下載插件';
$lang->extension->downloadAB    = '下載';
$lang->extension->upload        = '本地安裝';
$lang->extension->erase         = '清除';
$lang->extension->upgrade       = '升級插件';
$lang->extension->agreeLicense  = '我同意該授權';

$lang->extension->structure   = '目錄結構';
$lang->extension->installed   = '已安裝';
$lang->extension->deactivated = '被禁用';
$lang->extension->available   = '已下載';

$lang->extension->id          = '編號';
$lang->extension->name        = '名稱';
$lang->extension->code        = '插件代號';
$lang->extension->version     = '版本';
$lang->extension->compatible  = '適用版本';
$lang->extension->latest      = '<small>最新版本<strong><a href="%s" target="_blank" class="extension">%s</a></strong>，兼容禪道<a href="http://api.zentao.net/goto.php?item=latest" target="_blank" class="alert-link"><strong>%s</strong></a></small>';
$lang->extension->author      = '作者';
$lang->extension->license     = '授權';
$lang->extension->intro       = '詳情';
$lang->extension->abstract    = '簡介';
$lang->extension->site        = '官網';
$lang->extension->addedTime   = '添加時間';
$lang->extension->updatedTime = '更新時間';
$lang->extension->downloads   = '下載量';
$lang->extension->public      = '下載方式';
$lang->extension->compatible  = '兼容性';
$lang->extension->grade       = '評分';
$lang->extension->depends     = '依賴';

$lang->extension->publicList[0] = '手工下載';
$lang->extension->publicList[1] = '直接下載';

$lang->extension->compatibleList[0] = '未知';
$lang->extension->compatibleList[1] = '兼容';

$lang->extension->byDownloads   = '最多下載';
$lang->extension->byAddedTime   = '最新添加';
$lang->extension->byUpdatedTime = '最近更新';
$lang->extension->bySearch      = '搜索';
$lang->extension->byCategory    = '分類瀏覽';

$lang->extension->installFailed            = '%s失敗，錯誤原因如下:';
$lang->extension->uninstallFailed          = '卸載失敗，錯誤原因如下:';
$lang->extension->confirmUninstall         = '卸載插件會刪除或修改相關的資料庫，是否繼續卸載？';
$lang->extension->noticeBackupDB           = '卸載前，建議備份資料庫。';
$lang->extension->installFinished          = '恭喜您，插件順利的%s成功！';
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
$lang->extension->backDBFile               = '插件相關數據已經備份到 %s 檔案中！';

$lang->extension->upgradeExt     = '升級';
$lang->extension->installExt     = '安裝';
$lang->extension->upgradeVersion = '（從%s升級到%s）';

$lang->extension->waring = '警告';

$lang->extension->errorOccurs                  = '錯誤：';
$lang->extension->errorGetModules              = '從www.zentao.net獲得插件分類失敗。可能是因為網絡方面的原因，請檢查後重新刷新頁面。';
$lang->extension->errorGetExtensions           = '從www.zentao.net獲得插件失敗。可能是因為網絡方面的原因，您可以到 <a href="http://www.zentao.net/extension/" target="_blank" class="alert-link">www.zentao.net</a> 手工下載插件，然後上傳安裝。';
$lang->extension->errorDownloadPathNotFound    = '插件下載存儲路徑<strong>%s</strong>不存在。<br />linux下面請執行命令：<strong>mkdir -p %s</strong>來修正。';
$lang->extension->errorDownloadPathNotWritable = '插件下載存儲路徑<strong>%s</strong>不可寫。<br />linux下面請執行命令：<strong>sudo chmod 777 %s</strong>來修正。';
$lang->extension->errorPackageFileExists       = '下載路徑已經有一個名為的<strong>%s</strong>附件。<h5>重新%s，<a href="%s" class="alert-link">請點擊此連結</a></h5>';
$lang->extension->errorDownloadFailed          = '下載失敗，請重新下載。如果多次重試還不行，請嘗試手工下載，然後通過上傳功能上傳。';
$lang->extension->errorMd5Checking             = '下載檔案不完整，請重新下載。如果多次重試還不行，請嘗試手工下載，然後通過上傳功能上傳。';
$lang->extension->errorExtracted               = '包檔案<strong> %s </strong>解壓縮失敗，可能不是一個有效的zip檔案。錯誤信息如下：<br />%s';
$lang->extension->errorCheckIncompatible       = '該插件與禪道版本不兼容，%s後可能無法使用。<h3>您可以選擇 <a href="%s">強制%s</a> 或者 <a href="#" onclick=parent.location.href="%s">取消</a></h3>';
$lang->extension->errorFileConflicted          = '有以下檔案衝突：<br />%s <h3>您可以選擇 <a href="%s">覆蓋</a> 或者 <a href="#" onclick=parent.location.href="%s">取消</a></h3>';
$lang->extension->errorPackageNotFound         = '包檔案 <strong>%s </strong>沒有找到，可能是因為自動下載失敗。您可以嘗試再次下載。';
$lang->extension->errorTargetPathNotWritable   = '目標路徑 <strong>%s </strong>不可寫。';
$lang->extension->errorTargetPathNotExists     = '目標路徑 <strong>%s </strong>不存在。';
$lang->extension->errorInstallDB               = '執行資料庫語句失敗。錯誤信息如下：%s';
$lang->extension->errorConflicts               = '與插件“%s”衝突！';
$lang->extension->errorDepends                 = '以下依賴插件沒有安裝或版本不正確：<br /><br /> %s';
$lang->extension->errorIncompatible            = '該插件與您的禪道版本不兼容';
$lang->extension->errorUninstallDepends        = '插件“%s”依賴該插件，不能卸載';
