#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-7');
$execution->name->range('项目集1,项目1,项目2,项目3,迭代1,阶段1,看板1');
$execution->type->range('program,project{3},sprint,stage,kanban');
$execution->model->range('[],scrum,waterfall,kanban,[]{3}');
$execution->parent->range('0,1{3},2,3,4');
$execution->status->range('doing');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(7);

$team = zenData('team');
$team->root->range('5{2},6{2},7{2}');
$team->account->range('admin,user1');
$team->role->range('研发,测试');
$team->type->range('execution');
$team->join->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$team->gen(6);

/**

title=测试executionModel->addProjectMembersTest();
timeout=0
cid=16259

- 敏捷项目根据执行添加团队信息
 - 第0条的account属性 @admin
 - 第0条的role属性 @研发
- 瀑布项目根据执行添加团队信息
 - 第0条的account属性 @admin
 - 第0条的role属性 @研发
- 看板项目根据执行添加团队信息
 - 第0条的account属性 @admin
 - 第0条的role属性 @研发
- 敏捷项目根据执行添加团队信息统计 @2
- 瀑布项目根据执行添加团队信息统计 @2
- 看板项目根据执行添加团队信息统计 @2

*/

$projectIDList   = array(2, 3, 4);
$executionIDList = array(5, 6, 7);
$count           = array(0, 1);

$executionTester = new executionModelTest();
r($executionTester->addProjectMembersTest($projectIDList[0], $executionIDList[0], $count[0])) && p('0:account,role') && e('admin,研发'); // 敏捷项目根据执行添加团队信息
r($executionTester->addProjectMembersTest($projectIDList[1], $executionIDList[1], $count[0])) && p('0:account,role') && e('admin,研发'); // 瀑布项目根据执行添加团队信息
r($executionTester->addProjectMembersTest($projectIDList[2], $executionIDList[2], $count[0])) && p('0:account,role') && e('admin,研发'); // 看板项目根据执行添加团队信息
r($executionTester->addProjectMembersTest($projectIDList[0], $executionIDList[0], $count[1])) && p()                 && e('2');          // 敏捷项目根据执行添加团队信息统计
r($executionTester->addProjectMembersTest($projectIDList[1], $executionIDList[1], $count[1])) && p()                 && e('2');          // 瀑布项目根据执行添加团队信息统计
r($executionTester->addProjectMembersTest($projectIDList[2], $executionIDList[2], $count[1])) && p()                 && e('2');          // 看板项目根据执行添加团队信息统计
