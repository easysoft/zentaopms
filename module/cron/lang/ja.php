<?php
$lang->cron->common = 'プランタスク';
$lang->cron->index = 'ホーム';
$lang->cron->list = 'タスクリスト';
$lang->cron->create = '追加';
$lang->cron->createAction = '添加任务';
$lang->cron->edit = '編集';
$lang->cron->delete = '削除';
$lang->cron->toggle = '有効/無効';
$lang->cron->turnon = 'オープン/クローズ';
$lang->cron->openProcess = '再起動';
$lang->cron->restart = '重启计划任务';

$lang->cron->m = '分';
$lang->cron->h = '時間';
$lang->cron->dom = '日';
$lang->cron->mon = '月';
$lang->cron->dow = '週';
$lang->cron->command = 'コマンド';
$lang->cron->status = 'ステータス';
$lang->cron->type = 'タスクタイプ';
$lang->cron->remark = '付記';
$lang->cron->lastTime = '最終実行';

$lang->cron->turnonList['1'] = 'オープン';
$lang->cron->turnonList['0'] = 'クローズ';

$lang->cron->statusList['normal'] = '正常';
$lang->cron->statusList['running'] = '実行中';
$lang->cron->statusList['stop'] = '停止';

$lang->cron->typeList['zentao'] = '禅道自己呼び出し';
global $config;
if($config->features->cronSystemCall) $lang->cron->typeList['system'] = 'OSコマンド';

$lang->cron->toggleList['start'] = 'アクティベーション';
$lang->cron->toggleList['stop'] = '無効';

$lang->cron->confirmDelete = '当該プランタスクを削除してもよろしいですか？';
$lang->cron->confirmTurnon = '当該プランタスクをクローズしてもよろしいですか？';
$lang->cron->introduction  = <<<EOD
<p>计划任务功能可以定时执行诸如更新燃尽图、备份等操作，免除自己布置计划任务。</p>
<p>该功能还有待完善，所以默认关闭该功能。</p>
EOD;
$lang->cron->confirmOpen = <<<EOD
<p>是否开启该功能？<a href="%s" target='hiddenwin'><strong>打开计划任务</strong></a></p>
EOD;

$lang->cron->notice = new stdclass();
$lang->cron->notice->m = '値域範囲:0-59、"*"は値域範囲の数字、"/"は"ごとに"、 "-"は数字範囲。';
$lang->cron->notice->h = '値域範囲:0-23';
$lang->cron->notice->dom = '値域範囲:1-31';
$lang->cron->notice->mon = '値域範囲:1-12';
$lang->cron->notice->dow = '値域範囲:0-6';
$lang->cron->notice->help = '注：サーバーが再起動或いはプランタスクが正常に実行されませんなら、プランタスクは既に停止しました。プランタスクを開始するには、手動で「リセット」をクリックして、或いは一分間後ページを更新してください。もしタスクリスト一番目のレコードの最終実行時間が変えましたら、タスクの開始に成功しました。';
$lang->cron->notice->errorRule = '"%s" ご入力した内容が正しい値ではありません';
