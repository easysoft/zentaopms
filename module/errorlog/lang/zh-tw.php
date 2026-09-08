<?php
$lang->errorlog->common      = '錯誤日誌';
$lang->errorlog->browse      = '瀏覽錯誤日誌';
$lang->errorlog->browseAbbr  = '瀏覽';
$lang->errorlog->view        = '查看錯誤日誌';
$lang->errorlog->viewAbbr    = '查看';
$lang->errorlog->delete      = '刪除';
$lang->errorlog->batchDelete = '批量刪除';
$lang->errorlog->setting     = '設置';

$lang->errorlog->days               = '保存天數';
$lang->errorlog->info               = '超過保存天數的錯誤日誌會被刪除，需要開啟計劃任務。';
$lang->errorlog->notFound           = '沒有找到對應的錯誤日誌。';
$lang->errorlog->empty              = '暫無錯誤日誌。';
$lang->errorlog->confirmDelete      = '您確認要刪除該錯誤日誌嗎？';
$lang->errorlog->confirmBatchDelete = '您確認要刪除選中的錯誤日誌嗎？';

$lang->errorlog->requestID   = '請求ID';
$lang->errorlog->account     = '賬號';
$lang->errorlog->module      = '模組';
$lang->errorlog->method      = '方法';
$lang->errorlog->url         = '請求地址';
$lang->errorlog->level       = '錯誤級別';
$lang->errorlog->message     = '錯誤資訊';
$lang->errorlog->file        = '檔案';
$lang->errorlog->line        = '行號';
$lang->errorlog->trace       = '堆疊';
$lang->errorlog->createdDate = '發生時間';

$lang->errorlog->featureBar = array();
$lang->errorlog->featureBar['browse'] = array('all' => '全部');

$lang->errorlog->notice = new stdclass();
$lang->errorlog->notice->int = '『%s』應當是正整數。';

$lang->errorlog->levelList = array();
$lang->errorlog->levelList[E_ERROR]             = 'Fatal Error';
$lang->errorlog->levelList[E_WARNING]           = 'Warning';
$lang->errorlog->levelList[E_PARSE]             = 'Parse Error';
$lang->errorlog->levelList[E_NOTICE]            = 'Notice';
$lang->errorlog->levelList[E_CORE_ERROR]        = 'Core Error';
$lang->errorlog->levelList[E_CORE_WARNING]      = 'Core Warning';
$lang->errorlog->levelList[E_COMPILE_ERROR]     = 'Compile Error';
$lang->errorlog->levelList[E_COMPILE_WARNING]   = 'Compile Warning';
$lang->errorlog->levelList[E_USER_ERROR]        = 'User Error';
$lang->errorlog->levelList[E_USER_WARNING]      = 'User Warning';
$lang->errorlog->levelList[E_USER_NOTICE]       = 'User Notice';
if(PHP_VERSION_ID < 80400) $lang->errorlog->levelList[E_STRICT] = 'Strict'; // E_STRICT自PHP 8.4起棄用，僅低版本需要顯示。
$lang->errorlog->levelList[E_RECOVERABLE_ERROR] = 'Recoverable Error';
$lang->errorlog->levelList[E_DEPRECATED]        = 'Deprecated';
$lang->errorlog->levelList[E_USER_DEPRECATED]   = 'User Deprecated';
