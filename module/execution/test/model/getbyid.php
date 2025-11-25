#!/usr/bin/env php
<?php

/**

title=测试 executionModel::getByID();
timeout=0
cid=16303

- 执行executionTest模块的getByIDTest方法，参数是3
 - 属性name @迭代1
 - 属性type @sprint
 - 属性status @doing
- 执行executionTest模块的getByIDTest方法，参数是999  @0
- 执行executionTest模块的getByIDTest方法  @0
- 执行executionTest模块的getByIDTest方法，参数是8
 - 属性name @延迟执行
 - 属性delay @680
- 执行executionTest模块的getByIDTest方法，参数是3, true
 - 属性name @迭代1
 - 属性desc @包含<img src="/test.jpg"/>的描述
- 执行executionTest模块的getByIDTest方法，参数是3
 - 属性name @迭代1
 - 属性totalHours @240
- 执行executionTest模块的getByIDTest方法，参数是3
 - 属性name @迭代1
 - 属性isParent @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

// 准备execution测试数据
$execution = zenData('project');
$execution->id->range('1-10');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3,子迭代1,子迭代2,延迟执行,已完成执行,挂起执行');
$execution->type->range('project{2},sprint,waterfall,kanban,sprint{5}');
$execution->status->range('doing{3},closed,doing,suspended,done,doing{3}');
$execution->parent->range('0,0,1,1,2,3,3,0,0,0');
$execution->grade->range('2{2},1{8}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`,`1,3,6`,`1,3,7`,8,9,10')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0,20230212 000000:0,20220101 000000:0,20230212 000000:0,20230212 000000:0,20230212 000000:0,20230212 000000:0,20220101 000000:0,20230212 000000:0,20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->estimate->range('100,200,150,300,250,180,120,400,350,280');
$execution->consumed->range('50,120,80,180,150,90,60,200,350,140');
$execution->left->range('50,80,70,120,100,90,60,200,0,140');
$execution->days->range('10,15,12,20,18,14,10,25,22,16');
$execution->desc->range('描述1,描述2,包含<img src="/test.jpg"/>的描述,描述4,描述5,描述6,描述7,描述8,描述9,描述10');
$execution->deleted->range('0{10}');
$execution->gen(10);

// 准备team测试数据以测试工时计算
$team = zenData('team');
$team->id->range('1-15');
$team->root->range('3{3},4{2},6{2},8{3},9{2},10{3}');
$team->type->range('execution{15}');
$team->account->range('admin,user1,user2,admin,user1,user3,user4,admin,user1,user2,admin,user1,user2,user3,user4');
$team->days->range('10{15}');
$team->hours->range('8{15}');
$team->gen(15);

// 准备user测试数据
$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->deleted->range('0{10}');
$user->gen(10);

su('admin');

$executionTest = new executionTest();

// 测试步骤1：正常获取存在的执行信息
r($executionTest->getByIDTest(3)) && p('name,type,status') && e('迭代1,sprint,doing');

// 测试步骤2：测试不存在的执行ID
r($executionTest->getByIDTest(999)) && p() && e('0');

// 测试步骤3：测试边界值ID为0
r($executionTest->getByIDTest(0)) && p() && e('0');

// 测试步骤4：测试延迟执行的延迟计算功能（延迟680天）
r($executionTest->getByIDTest(8)) && p('name,delay') && e('延迟执行,680');

// 测试步骤5：测试图片链接替换功能
r($executionTest->getByIDTest(3, true)) && p('name,desc') && e('迭代1,包含<img src="/test.jpg"/>的描述');

// 测试步骤6：测试团队工时计算功能
r($executionTest->getByIDTest(3)) && p('name,totalHours') && e('迭代1,240');

// 测试步骤7：测试父级执行检查
r($executionTest->getByIDTest(3)) && p('name,isParent') && e('迭代1,1');