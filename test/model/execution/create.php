#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
zdTable('user')->gen(5);
su('admin');

$project = zdTable('project');
$project->id->range('1-4');
$project->name->range('项目集1,项目1,项目2,项目3');
$project->type->range('program,project{3}');
$project->model->range('[],scrum,waterfall,kanban');
$project->parent->range('0,1{3}');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`');
$project->status->range('doing');
$project->openedBy->range('admin,user1');
$project->begin->range('(-3M)-(+M):1D')->type('timestamp')->format('YY/MM/DD');
$project->end->range('(+5w)-(+2M):1D')->type('timestamp')->format('YY/MM/DD');
$project->gen(4);

/**

title=测试executionModel->create();
cid=1
pid=1

测试创建敏捷私有执行 >> 新增私有敏捷执行,sprint
测试创建敏捷公开执行 >> 新增公开敏捷执行code,sprint
测试创建瀑布私有执行 >> 新增私有瀑布执行,stage
测试创建瀑布公开执行 >> 新增公开瀑布执行code,stage
测试创建看板私有执行 >> 新增私有看板执行,kanban
测试创建看板公开执行 >> 新增公开看板执行code,kanban
测试创建迭代团队分配 >> user1,user2,user3,user4
测试不输入项目 >> 所属项目不能为空。
测试不输入执行名称 >> 『执行名称』不能为空。
测试不输入执行代号 >> 『执行代号』不能为空。
测试一样的执行名称 >> 『执行名称』已经有『新增私有敏捷执行』这条记录了。
测试一样的执行代号 >> 『执行代号』已经有『新增私有敏捷执行code』这条记录了。

*/

$executionTester = new executionTest();

$projectIDList = array(1, 2, 3, 4);
$dayNum        = '6';
$days          = '5';
$products      = array('1', '0');

$prvExecution            = array('name' => '新增私有敏捷执行', 'code' => '新增私有敏捷执行code', 'products' => $products);
$openExecution           = array('name' => '新增公开敏捷执行', 'code' => '新增公开敏捷执行code', 'products' => $products, 'acl' => 'open');
$prvExecution_waterfall  = array('name' => '新增私有瀑布执行', 'code' => '新增私有瀑布执行code', 'products' => $products);
$openExecution_waterfall = array('name' => '新增公开瀑布执行', 'code' => '新增公开瀑布执行code', 'products' => $products, 'acl' => 'open');
$prvExecution_kanban     = array('name' => '新增私有看板执行', 'code' => '新增私有看板执行code', 'products' => $products);
$openExecution_kanban    = array('name' => '新增公开看板执行', 'code' => '新增公开看板执行code', 'products' => $products, 'acl' => 'open');
$teamExecution           = array('name' => '新增团队执行', 'code' => '新增团队执行code', 'products' => $products, 'PO' => 'user1' ,'QD' => 'user2', 'PM' => 'user3', 'RD' => 'user4');
$noProjectID             = array('name' => '新增私有敏捷执行', 'code' => '新增私有敏捷执行code', 'products' => $products);
$noProductID             = array('name' => '新增私有敏捷执行', 'code' => '新增私有敏捷执行code');
$noName                  = array('name' => '', 'code' => '名称校验code', 'products' => $products);
$noCode                  = array('name' => 'code校验', 'code' => '', 'products' => $products);
$equallyName             = array('name' => '新增私有敏捷执行', 'code' => '一样名称校验code', 'products' => $products);
$equallyCode             = array('name' => '一样code校验', 'code' => '新增私有敏捷执行code', 'products' => $products);

r($executionTester->createTest($prvExecution, $projectIDList[1], $dayNum, $days))            && p('name,type')   && e('新增私有敏捷执行,sprint');                                // 测试创建敏捷私有执行
r($executionTester->createTest($openExecution, $projectIDList[1], $dayNum, $days))           && p('code,type')   && e('新增公开敏捷执行code,sprint');                            // 测试创建敏捷公开执行
r($executionTester->createTest($prvExecution_waterfall, $projectIDList[2], $dayNum, $days))  && p('name,type')   && e('新增私有瀑布执行,stage');                                 // 测试创建瀑布私有执行
r($executionTester->createTest($openExecution_waterfall, $projectIDList[2], $dayNum, $days)) && p('code,type')   && e('新增公开瀑布执行code,stage');                             // 测试创建瀑布公开执行
r($executionTester->createTest($prvExecution_kanban, $projectIDList[3], $dayNum, $days))     && p('name,type')   && e('新增私有看板执行,kanban');                                // 测试创建看板私有执行
r($executionTester->createTest($openExecution_kanban, $projectIDList[3], $dayNum, $days))    && p('code,type')   && e('新增公开看板执行code,kanban');                            // 测试创建看板公开执行
r($executionTester->createTest($teamExecution, $projectIDList[1], $dayNum, $days))           && p('PO,QD,PM,RD') && e('user1,user2,user3,user4');                                // 测试创建迭代团队分配
r($executionTester->createTest($prvExecution, '', $dayNum, $days))                           && p('message:0')   && e('所属项目不能为空。');                                     // 测试不输入项目
r($executionTester->createTest($noName, $projectIDList[1], $dayNum, $days))                  && p('name:0')      && e('『执行名称』不能为空。');                                 // 测试不输入执行名称
r($executionTester->createTest($noCode, $projectIDList[1], $dayNum, $days))                  && p('code:0')      && e('『执行代号』不能为空。');                                 // 测试不输入执行代号
r($executionTester->createTest($equallyName, $projectIDList[1], $dayNum, $days))             && p('name:0')      && e('『执行名称』已经有『新增私有敏捷执行』这条记录了。');     // 测试一样的执行名称
r($executionTester->createTest($equallyCode, $projectIDList[1], $dayNum, $days))             && p('code:0')      && e('『执行代号』已经有『新增私有敏捷执行code』这条记录了。'); // 测试一样的执行代号
