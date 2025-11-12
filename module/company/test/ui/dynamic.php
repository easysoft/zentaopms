#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/dynamic.ui.class.php';

/**

title=开源版m=company&f=dynamic测试
timeout=0
cid=1

- 开源版m=company&f=dynamic测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=company&f=dynamic测试成功

*/

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range('admin,dev,qa');
$user->gender->range('f,m');
$user->gen(3);

$userGroup = zenData('usergroup');
$userGroup->account->range('1,2')->prefix('user');
$userGroup->group->range('2-3');
$userGroup->gen(2);

$groupPriv = zenData('grouppriv');
$groupPriv->group->range('2');
$groupPriv->module->range('my');
$groupPriv->method->range('team');
$groupPriv->gen(1);

$product = zenData('product');
$product->id->range('1');
$product->name->range('产品1');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(1);

$project = zenData('project');
$project->id->range('1');
$project->name->range('项目1');
$project->model->range('scrum');
$project->status->range('doing');
$project->type->range('project');
$project->multiple->range('1');
$project->vision->range('rnd');
$project->deleted->range('0');
$project->gen(1);

$execution = zenData('project');
$execution->id->range('101-102');
$execution->project->range('1');
$execution->type->range('sprint');
$execution->name->range('101-102')->prefix('执行');
$execution->status->range('wait,doing');
$execution->deleted->range('0');
$execution->multiple->range('1');
$execution->vision->range('rnd');
$execution->parent->range('1');
$execution->path->range('`,1,101`,`,1,102`');
$execution->gen(2, false);

$task = zenData('task');
$task->id->range('1-60');
$task->story->range('1');
$task->project->range('1-2');
$task->execution->range('101-106');
$task->name->range('1-60')->prefix('任务');
$task->assignedTo->range('admin,user1,user2');
$task->status->range('wait,doing,done');
$task->deleted->range('0');
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
    $data->product->range('1-2');
    $data->project->range('1-2');
    $data->execution->range('101-106');
    $data->objectType->range('task');
    $data->objectID->range('1-60');
    $data->actor->range('admin,user1,user2');
    $data->action->range('created');
    $data->date->range($perUserTimeRange)->type('timestamp')->format('YYYY-MM-DD hh:mm:ss');
    $data->gen(60);
}

$actionProduct = zenData('actionproduct');
$actionProduct->action->range('1-60');
$actionProduct->product->range('1-2');
$actionProduct->gen(60);

global $uiTester;
$tester = new dynamicTester();
r($tester->verifyCompanyDynamicContent()) && p('status,message') && e('SUCCESS,开源版m=company&f=dynamic测试成功'); //开源版m=company&f=dynamic测试
$tester->closeBrowser();