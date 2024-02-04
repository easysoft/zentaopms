#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zdTable('user')->gen(30);

$project = zdTable('project');
$project->id->range('1-5');
$project->project->range('0');
$project->name->prefix("项目")->range('1-5');
$project->code->prefix("project")->range('1-5');
$project->PM->range('admin,user1,user2,user3,user4');
$project->model->range("scrum");
$project->auth->range("[]");
$project->path->range("[]");
$project->type->range("project");
$project->grade->range("1");
$project->days->range("5");
$project->status->range("wait");
$project->desc->range("[]");
$project->budget->range("100000,200000");
$project->budgetUnit->range("CNY");
$project->percent->range("0-0");
$project->gen(5);

$execution = zdTable('project');
$execution->id->range('6-10');
$execution->project->range('1-5');
$execution->name->prefix("执行")->range('1-5');
$execution->code->prefix("execution")->range('1-5');
$execution->PM->range('user4,user5,user6,user7,user8');
$execution->auth->range("[]");
$execution->path->range("[]");
$execution->type->range("sprint");
$execution->grade->range("1");
$execution->days->range("5");
$execution->status->range("wait");
$execution->desc->range("[]");
$execution->gen(5, true, false);

$dept = zdTable('dept')->config('dept');
$dept->manager->range('admin,user1,user2,user3,user4');
$dept->gen(50);

/**

title=commonModel->canOperateEffort();
timeout=0
cid=1

*/

global $tester;
$tester->loadModel('common');

$effort = new stdclass();
$effort->account = 'admin';
r($tester->common->canOperateEffort($effort)) && p() && e('1');  //判断当前用户是管理员

unset($effort->account);
r($tester->common->canOperateEffort($effort)) && p() && e('1');  //判断日志为空的情况

su('user1');
$effort->account = 'user2';
r($tester->common->canOperateEffort($effort)) && p() && e('0');  //判断当前用户不是日志的记录者的情况

$effort->account = 'user1';
r($tester->common->canOperateEffort($effort)) && p() && e('1');  //判断当前用户是日志的记录者的情况

$effort->project = 2;
r($tester->common->canOperateEffort($effort)) && p() && e('1');  //判断当前用户是日志所属项目管理者的情况

$effort->account = 'user4';
$effort->project = 3;
r($tester->common->canOperateEffort($effort)) && p() && e('0');  //判断当前用户不是日志所属项目管理者的情况

su('user5');
$effort->project   = 2;
$effort->execution = 7;
r($tester->common->canOperateEffort($effort)) && p() && e('1');  //判断当前用户是日志所属执行负责人的情况

su('user6');
r($tester->common->canOperateEffort($effort)) && p() && e('0');  //判断当前用户不是日志所属执行负责人的情况

$effort->project   = 0;
$effort->execution = 0;
r($tester->common->canOperateEffort($effort)) && p() && e('0');  //判断当前用户不是部门负责人的情况

su('user2');
$effort->account = 'user23';
r($tester->common->canOperateEffort($effort)) && p() && e('1');  //判断当前用户是部门负责人的情况
