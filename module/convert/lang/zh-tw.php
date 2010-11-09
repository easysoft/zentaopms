<?php
/**
 * The convert module zh-tw file of ZenTaoMS.
 *
 * @copyright   Copyright 2009-2010 QingDao Nature Easy Soft Network Technology Co,LTD (www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     convert
 * @version     $Id: zh-tw.php 1068 2010-09-11 07:11:57Z wwccss $
 * @link        http://www.zentao.net
 */
$lang->convert->common  = '從其他系統導入';
$lang->convert->next    = '下一步';
$lang->convert->pre     = '返回';
$lang->convert->reload  = '刷新';
$lang->convert->error   = '錯誤 ';

$lang->convert->start   = '開始轉換';
$lang->convert->desc    = <<<EOT
<p>歡迎使用系統轉換嚮導，本程序會幫助您將其他系統的數據轉換到禪道項目管理系統中。</p>
<strong>轉換存在一定的風險，轉換之前，我們強烈建議您備份資料庫及相應的數據檔案，並保證轉換的時候，沒有其他人進行操作。</strong>
EOT;

$lang->convert->selectSource     = '選擇來源系統及版本';
$lang->convert->source           = '來源系統';
$lang->convert->version          = '版本';
$lang->convert->mustSelectSource = "必須選擇一個來源。";

$lang->convert->sourceList['BugFree'] = array('bugfree_1' => '1.x', 'bugfree_2' => '2.x');

$lang->convert->setting     = '設置';
$lang->convert->checkConfig = '檢查配置';

$lang->convert->ok         = '檢查通過(√)';
$lang->convert->fail       = '檢查失敗(×)';

$lang->convert->settingDB  = '設置資料庫';
$lang->convert->dbHost     = '資料庫伺服器';
$lang->convert->dbPort     = '伺服器連接埠';
$lang->convert->dbUser     = '資料庫用戶名';
$lang->convert->dbPassword = '資料庫密碼';
$lang->convert->dbName     = '%s使用的庫';
$lang->convert->dbCharset  = '%s資料庫編碼';
$lang->convert->dbPrefix   = '%s表首碼';
$lang->convert->installPath= '%s安裝的根目錄';

$lang->convert->checkDB    = '資料庫';
$lang->convert->checkTable = '表';
$lang->convert->checkPath  = '安裝路徑';

$lang->convert->execute    = '執行轉換';
$lang->convert->item       = '轉換項';
$lang->convert->count      = '轉換數量';
$lang->convert->info       = '轉換信息';

$lang->convert->bugfree->users    = '用戶';
$lang->convert->bugfree->projects = '項目';
$lang->convert->bugfree->modules  = '模組';
$lang->convert->bugfree->bugs     = 'Bug';
$lang->convert->bugfree->cases    = '測試用例';
$lang->convert->bugfree->results  = '測試結果';
$lang->convert->bugfree->actions  = '歷史記錄';
$lang->convert->bugfree->files    = '附件';

$lang->convert->errorConnectDB     = '資料庫連接失敗 ';
$lang->convert->errorFileNotExits  = '檔案 %s 不存在';
$lang->convert->errorUserExists    = '用戶 %s 已存在';
$lang->convert->errorCopyFailed    = '檔案 %s 拷貝失敗';
