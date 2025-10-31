#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/testtask.ui.class.php';

/**

title=开源版m=user&f=testtask测试
timeout=0
cid=1

- 开源版m=user&f=testtask测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=user&f=testtask测试成功

*/

$user = zenData('user');
$user->id->range('1-3');
$user->account->range('admin,user1,user2');
$user->realname->range('管理员,用户1,用户2');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->role->range('admin,dev,qa');
$user->gender->range('f,m');
$user->gen(3);

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
$project->gen(1);

$execution = zenData('project');
$execution->id->range('101-103');
$execution->project->range('1');
$execution->type->range('sprint');
$execution->name->range('101-103')->prefix('迭代');
$execution->status->range('doing');
$execution->deleted->range('0');
$execution->gen(3, false);

$build = zenData('build');
$build->id->range('1-3');
$build->product->range('1');
$build->execution->range('101-103');
$build->name->range('1-3')->prefix('版本');
$build->gen(3);

$userview = zenData('userview');
$userview->account->range('admin,user1,user2');
$userview->programs->range('');
$userview->products->range('1');
$userview->projects->range('1');
$userview->sprints->range('102');
$userview->gen(3);

$testtask = zenData('testtask');
$testtask->id->range('1-18');
$testtask->name->range('1-18')->prefix('测试任务');
$testtask->product->range('1');
$testtask->project->range('1');
$testtask->execution->range('102');
//$testtask->build->range('2,2,3,2,2,3,2,2,3,2,2,3');
$testtask->build->range('1-3');
$testtask->owner->range('admin,user1,user2');
//$testtask->members->range('admin,user1,user2,admin,user1,user2,admin,user1,user2,admin,user1,user2');
$testtask->members->range('admin,user1,user2');
$testtask->pri->range('1-4');
$testtask->status->range('wait{1},blocked{1},doing{2},done{2}');
$testtask->begin->range('`0000-00-00`,`2024-01-01`{5}');
$testtask->end->range('`0000-00-00`{4},`2024-01-31`{2}');
$testtask->mailto->range('`admin@chandao.com`');
$testtask->desc->range('1-18')->prefix('这是测试单描述');
$testtask->report->range('');
$testtask->auto->range('no');
$testtask->subStatus->range('');
$testtask->gen(18);

global $uiTester;

$users = $uiTester->dao->select('u.*, uv.sprints')
    ->from('zt_user')->alias('u')
    ->leftJoin('zt_userview')->alias('uv')->on('u.account = uv.account')
    ->fetchAll();

// 补充 buildName, executionName 字段用于比对
$tasks = $uiTester->dao->select("t.*, b.name AS buildName, pr.name AS executionName")
    ->from('zt_testtask')->alias('t')
    ->leftJoin('zt_product')->alias('p')->on('t.product = p.id')
    ->leftJoin('zt_project')->alias('pr')->on('t.execution = pr.id')
    ->leftJoin('zt_user')->alias('u')->on('t.owner = u.account')
    ->leftJoin('zt_build')->alias('b')->on('t.build = b.id')
    ->where('t.deleted')->eq('0')
    ->fetchAll();

$tester = new testtaskTester();

r($tester->verifyUserTesttask($users, $tasks, 5)) && p('status,message') && e('SUCCESS,开源版m=user&f=testtask测试成功'); // 开源版m=user&f=testtask测试

$tester->closeBrowser();
