#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

$project = zenData('team')->gen(0);
$project = zenData('project');
$project->id->range('1-4');
$project->name->range('项目集1,项目1,项目2,项目3');
$project->type->range('program,project{3}');
$project->model->range('[],scrum,waterfall,kanban');
$project->parent->range('0');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`');
$project->status->range('doing');
$project->openedBy->range('admin,user1');
$project->begin->range('(-3M)-(+M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5w)-(+2M):1D')->type('timestamp')->format('YY/MM/DD');
$project->gen(4);

/**

title=测试executionModel->create();
timeout=0
cid=16288

- 测试创建敏捷私有执行
 - 属性name @新增私有敏捷执行
 - 属性type @sprint
- 测试创建敏捷公开执行
 - 属性code @新增公开敏捷执行code
 - 属性type @sprint
- 测试创建迭代团队分配
 - 属性PO @user1
 - 属性QD @user2
 - 属性PM @user3
 - 属性RD @user4
- 测试不输入项目第project条的0属性 @『所属项目』不能为空。
- 测试不输入执行名称第name条的0属性 @『项目名称』不能为空。
- 测试不输入执行代号第code条的0属性 @『项目代号』不能为空。
- 测试一样的执行名称第name条的0属性 @『项目名称』已经有『新增私有敏捷执行』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。
- 测试一样的执行代号第code条的0属性 @『项目代号』已经有『新增私有敏捷执行code』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。

*/

$executionTester = new executionTest();

$projectIDList = array(1, 2, 3, 4);
$teamMembers   = array('admin');
$days          = '5';
$products      = array('1', '0');

$prvExecution            = array('name' => '新增私有敏捷执行', 'code' => '新增私有敏捷执行code', 'products' => $products, 'attribute' => 'devel');
$openExecution           = array('name' => '新增公开敏捷执行', 'code' => '新增公开敏捷执行code', 'products' => $products, 'acl' => 'open', 'attribute' => 'devel');
$prvExecution_waterfall  = array('name' => '新增私有瀑布执行', 'code' => '新增私有瀑布执行code', 'products' => $products, 'attribute' => 'devel');
$openExecution_waterfall = array('name' => '新增公开瀑布执行', 'code' => '新增公开瀑布执行code', 'products' => $products, 'acl' => 'open', 'attribute' => 'devel');
$prvExecution_kanban     = array('name' => '新增私有看板执行', 'code' => '新增私有看板执行code', 'products' => $products, 'attribute' => 'devel');
$openExecution_kanban    = array('name' => '新增公开看板执行', 'code' => '新增公开看板执行code', 'products' => $products, 'acl' => 'open', 'attribute' => 'devel');
$teamExecution           = array('name' => '新增团队执行',     'code' => '新增团队执行code', 'products' => $products, 'PO' => 'user1' ,'QD' => 'user2', 'PM' => 'user3', 'RD' => 'user4', 'attribute' => 'devel');
$noProjectID             = array('name' => '新增私有敏捷执行', 'code' => '新增私有敏捷执行code', 'products' => $products, 'attribute' => 'devel');
$noProductID             = array('name' => '新增私有敏捷执行', 'code' => '新增私有敏捷执行code', 'attribute' => 'devel');
$noName                  = array('name' => '',                'code' => '名称校验code', 'products' => $products, 'attribute' => 'devel');
$noCode                  = array('name' => 'code校验',        'code' => '', 'products' => $products, 'attribute' => 'devel');
$equallyName             = array('name' => '新增私有敏捷执行', 'code' => '一样名称校验code', 'products' => $products, 'attribute' => 'devel');
$equallyCode             = array('name' => '一样code校验',    'code' => '新增私有敏捷执行code', 'products' => $products, 'attribute' => 'devel');

r($executionTester->createTest($prvExecution, $projectIDList[1], $teamMembers, $days))            && p('name,type')   && e('新增私有敏捷执行,sprint');                                // 测试创建敏捷私有执行
r($executionTester->createTest($openExecution, $projectIDList[1], $teamMembers, $days))           && p('code,type')   && e('新增公开敏捷执行code,sprint');                            // 测试创建敏捷公开执行
r($executionTester->createTest($teamExecution, $projectIDList[1], $teamMembers, $days))           && p('PO,QD,PM,RD') && e('user1,user2,user3,user4');                                // 测试创建迭代团队分配
r($executionTester->createTest($prvExecution, '', $teamMembers, $days))                           && p('project:0')   && e('『所属项目』不能为空。');                                     // 测试不输入项目
r($executionTester->createTest($noName, $projectIDList[1], $teamMembers, $days))                  && p('name:0')      && e('『项目名称』不能为空。');                                 // 测试不输入执行名称
r($executionTester->createTest($noCode, $projectIDList[1], $teamMembers, $days))                  && p('code:0')      && e('『项目代号』不能为空。');                                 // 测试不输入执行代号
r($executionTester->createTest($equallyName, $projectIDList[1], $teamMembers, $days))             && p('name:0')      && e('『项目名称』已经有『新增私有敏捷执行』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。');     // 测试一样的执行名称
r($executionTester->createTest($equallyCode, $projectIDList[1], $teamMembers, $days))             && p('code:0')      && e('『项目代号』已经有『新增私有敏捷执行code』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); // 测试一样的执行代号