#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

$project = zdTable('project');
$project->id->range('1-5');
$project->project->range('0');
$project->name->prefix("项目")->range('1-5');
$project->code->prefix("project")->range('1-5');
$project->model->range("scrum,waterfall,kanban");
$project->auth->range("[]");
$project->path->range("[]");
$project->type->range("project");
$project->grade->range("1");
$project->days->range("20");
$project->status->range("wait");
$project->begin->range("2022\-10\-01");
$project->end->range("2022\-10\-30");
$project->desc->range("[]");
$project->budget->range("100000,200000");
$project->budgetUnit->range("CNY");
$project->percent->range("0-0");

$project->gen(5);

$user = zdTable('user')->gen(100);

/**

title=测试 projectModel->manageMembers();
timeout=0
cid=1

- 查看维护团队之后的成员数量 @3

- 查看维护团队之后的成员数量 @1

- 查看维护团队之后的成员角色第user1条的role属性 @研发

- 超过项目可用工日的情况属性days @可用工日不能大于项目的可用工日『20』

- 查看维护团队之后的成员详情第user2条的role属性 @测试

- 超过每天可用工时的情况属性hours @可用工时/天不能大于『24』

*/

$projectClass = new project();

$members1[0] = new stdclass();
$members1[0]->account  = 'user1';
$members1[0]->role     = '研发';
$members1[0]->days     = 10;
$members1[0]->hours    = 7.5;
$members1[0]->limited  = 'no';

$members1[1] = new stdclass();
$members1[1]->account  = 'user2';
$members1[1]->role     = '测试';
$members1[1]->days     = 8;
$members1[1]->hours    = 8.0;
$members1[1]->limited  = 'yes';

$members1[2] = new stdclass();
$members1[2]->account  = 'user3';
$members1[2]->role     = '产品经理';
$members1[2]->days     = 5;
$members1[2]->hours    = 5.0;
$members1[2]->limited  = 'no';

$members2[0] = new stdclass();
$members2[0]->account  = 'user11';
$members2[0]->role     = '测试';
$members2[0]->days     = 300;
$members2[0]->hours    = 20.5;
$members2[0]->limited  = 'no';

$members2[1] = new stdclass();
$members2[1]->account  = 'user12';
$members2[1]->role     = '项目经理';
$members2[1]->days     = 700;
$members2[1]->hours    = 25;
$members2[1]->limited  = 'yes';

$members2[2] = new stdclass();
$members2[2]->account  = 'user13';
$members2[2]->role     = '产品经理';
$members2[2]->days     = 10;
$members2[2]->hours    = 11.0;
$members2[2]->limited  = 'no';

$members2[3] = new stdclass();
$members2[3]->account  = 'user13';
$members2[3]->role     = '产品经理';
$members2[3]->days     = 10;
$members2[3]->hours    = 11.0;
$members2[3]->limited  = 'no';

$result1 = $projectClass->manageMembers(1, $members1);
$result2 = $projectClass->manageMembers(2, $members2);
$result3 = $projectClass->manageMembers(3, $members1);

$members2[0]->days = 10;
$members2[1]->days = 10;
$result4 = $projectClass->manageMembers(4, $members2);

r(count($result1)) && p('')  && e('3'); // 查看维护团队之后的成员数量

r($result1) && p('user1:role') && e('研发');                                 // 查看维护团队之后的成员角色
r($result2) && p()             && e('可用工日不能大于项目的可用工日『20』'); // 超过项目可用工日的情况
r($result3) && p('user2:role') && e('测试');                                 // 查看维护团队之后的成员详情
r($result4) && p()             && e('可用工时/天不能大于『24』');            // 超过每天可用工时的情况
