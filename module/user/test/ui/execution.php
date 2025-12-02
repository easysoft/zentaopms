#!/usr/bin/env php
<?php
include dirname(__FILE__, 2) . '/lib/ui/execution.ui.class.php';

/**

title=开源版m=user&f=execution测试
timeout=0
cid=1

- 开源版m=user&f=execution测试
 - 最终测试状态 @SUCCESS
 - 测试结果 @开源版m=user&f=execution测试成功

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
$project->id->range('1-2');
$project->name->range('项目1,项目2');
$project->model->range('scrum');
$project->status->range('doing');
$project->type->range('project');
$project->gen(2);

$execution = zenData('project');
$execution->id->range('101-106');
$execution->project->range('1{3},2{3}');
$execution->type->range('sprint');
$execution->name->range('101-106')->prefix('执行');
$execution->status->range('wait,doing,suspended,closed');
$execution->deleted->range('0');
$execution->multiple->range('1');
$execution->vision->range('rnd');
$execution->gen(6, false);

$team = zenData('team');
$team->id->range('1-18');
$team->root->range('101-106{3}');
$team->type->range('execution');
$team->account->range('admin,user1,user2');
$team->role->range('项目经理,开发,测试');
$team->join->range('`2023-01-01`,`2023-01-15`,`2023-02-01`');
$team->hours->range('8,6,4');
$team->gen(18);

$task = zenData('task');
$task->id->range('1-18');
$task->execution->range('101-106{3}');
$task->name->range('1-18')->prefix('任务');
$task->assignedTo->range('admin{6},user1{6},user2{6}');
$task->status->range('wait,doing,done');
$task->deleted->range('0');
$task->gen(18);

global $uiTester;
$users = $uiTester->dao->select('*')->from('zt_user')->fetchAll();

// 按用户保存execution数据，包含team信息
$executions = array();
foreach($users as $user)
{
    $userExecutions = $uiTester->dao->select("e.*, t.account, t.role, t.join, t.hours")
        ->from('zt_project')->alias('e')
        ->leftJoin('zt_team')->alias('t')->on('e.id = t.root AND t.type = "execution"')
        ->where('e.type')->eq('sprint')
        ->andWhere('e.deleted')->eq('0')
        ->andWhere('t.account')->eq($user->account)
        ->fetchAll('id');

    $executions[$user->account] = $userExecutions;
}

$tester = new executionTester();

r($tester->verifyUserExecutionContentAndPagination($users, $executions, 5)) && p('status,message') && e('SUCCESS,开源版m=user&f=execution测试成功'); //开源版m=user&f=execution测试

$tester->closeBrowser();
