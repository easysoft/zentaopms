<?php
$lang->cron->common      = '計劃任務';
$lang->cron->index       = '首頁';
$lang->cron->list        = '任務列表';
$lang->cron->create      = '添加';
$lang->cron->edit        = '編輯';
$lang->cron->delete      = '刪除';
$lang->cron->toggle      = '激活/禁用';
$lang->cron->turnon      = '打開/關閉';
$lang->cron->openProcess = '重啟';

$lang->cron->m        = '分';
$lang->cron->h        = '小時';
$lang->cron->dom      = '天';
$lang->cron->mon      = '月';
$lang->cron->dow      = '周';
$lang->cron->command  = '命令';
$lang->cron->status   = '狀態';
$lang->cron->type     = '任務類型';
$lang->cron->remark   = '備註';
$lang->cron->lastTime = '最後執行';

$lang->cron->turnonList['1'] = '打開';
$lang->cron->turnonList['0'] = '關閉';

$lang->cron->statusList['normal']  = '正常';
$lang->cron->statusList['running'] = '運行中';
$lang->cron->statusList['stop']    = '停止';

$lang->cron->typeList['zentao'] = '禪道自調用';
$lang->cron->typeList['system'] = '操作系統命令';

$lang->cron->toggleList['start'] = '激活';
$lang->cron->toggleList['stop']  = '禁用';

$lang->cron->confirmDelete = '是否刪除該計劃任務？';
$lang->cron->confirmTurnon = '是否關閉計劃任務？';
$lang->cron->introduction  = <<<EOD
<p>計劃任務功能可以定時執行諸如更新燃盡圖、備份等操作，免除自己佈置計劃任務。</p>
<p>該功能還有待完善，所以預設關閉該功能</p>
EOD;
$lang->cron->confirmOpen = <<<EOD
<p>是否開啟該功能？<a href="%s" target='hiddenwin'><strong>打開計劃任務</strong></a></p>
EOD;

$lang->cron->notice = new stdclass();
$lang->cron->notice->m    = '取值範圍:0-59，"*"代表取值範圍內的數字，"/"代表"每"， "-"代表數字範圍。';
$lang->cron->notice->h    = '取值範圍:0-23';
$lang->cron->notice->dom  = '取值範圍:1-31';
$lang->cron->notice->mon  = '取值範圍:1-12';
$lang->cron->notice->dow  = '取值範圍:0-6';
$lang->cron->notice->help = '註：如果伺服器重啟，或者發現計劃任務沒有正常工作，那麼計劃任務已經停止工作。需要手動點擊【重啟】按鈕，或者一分鐘後刷新頁面，來開啟計劃任務。如果任務列表中第一條記錄的最後執行時間改變，說明任務開啟成功。';
$lang->cron->notice->errorRule = '"%s" 填寫的不是合法的值';
