#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/dynamic.ui.class.php';

/**

title=开源版m=user&f=dynamic测试
timeout=0
cid=1

- 开源版m=user&f=dynamic测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=user&f=dynamic测试成功

*/

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range('admin,dev,qa');
$user->gender->range('f,m');
$user->gen(3);

$task = zenData('task');
$task->project->range('1');
$task->story->range('1');
$task->gen(60);

$today       = date('Y-m-d H:i:s');
$baseTS      = strtotime($today);
$seriesCount = 5;
$stepSeconds = 60;

$todayStartTS     = date('Ymd His', $baseTS);
$todayEndTS       = date('Ymd His', $baseTS + ($seriesCount) * $stepSeconds);
$yesterdayStartTS = date('Ymd His', strtotime('-1 day',  $baseTS));
$yesterdayEndTS   = date('Ymd His', strtotime('-1 day',  $baseTS) + ($seriesCount) * $stepSeconds);
$weekStartTS      = date('Ymd His', strtotime('-1 week', $baseTS));
$weekEndTS        = date('Ymd His', strtotime('-1 week', $baseTS) + ($seriesCount) * $stepSeconds);
$monthStartTS     = date('Ymd His', strtotime('-1 month', $baseTS));
$monthEndTS       = date('Ymd His', strtotime('-1 month', $baseTS) + ($seriesCount) * $stepSeconds);

// 每个用户每个时间段5个,即：今天{5}，昨天{5}，上周{5}，上月{5}
// 注意：不单独考虑昨天是否属于上周， 或者上月
$perUserTimeRange = "{$todayStartTS}-{$todayEndTS}:{$stepSeconds},{$yesterdayStartTS}-{$yesterdayEndTS}:{$stepSeconds},{$weekStartTS}-{$weekEndTS}:{$stepSeconds},{$monthStartTS}-{$monthEndTS}:{$stepSeconds}";

foreach(['action', 'actionrecent'] as $table)
{
    $data = zenData($table);
    $data->id->range('1-60');
    $data->objectType->range('task');
    $data->objectID->range('1-60');
    $data->actor->range('admin{20},user1{20},user2{20}');
    $data->action->range('created');
    $data->date->range($perUserTimeRange)->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
    $data->gen(60);
}

global $uiTester;
$users = $uiTester->dao->select('*')->from(TABLE_USER)->fetchAll();

// 按用户保存数据
$actions = array();
foreach($users as $user)
{
    $userActions = $uiTester->dao->select('*')
        ->from(TABLE_ACTION)
        ->where('actor')->eq($user->account)
        ->orderBy('date DESC')
        ->fetchAll('id');

    $actions[$user->account] = $userActions;
}

$tester = new dynamicTester();

r($tester->verifyUserDynamicContent($users, $actions)) && p('status,message') && e('SUCCESS,开源版m=user&f=dynamic测试成功'); //开源版m=user&f=dynamic测试

$tester->closeBrowser();