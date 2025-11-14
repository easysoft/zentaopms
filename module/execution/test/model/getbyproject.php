#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';
zenData('user')->gen(5);
su('admin');

$execution = zenData('project');
$execution->id->range('1-7');
$execution->name->range('项目集1,项目1,项目2,项目3,迭代1,阶段1,看板1');
$execution->type->range('program,project{3},sprint,stage,kanban');
$execution->model->range('[],scrum,waterfall,kanban,[]{3}');
$execution->parent->range('0,1{3},2,3,4');
$execution->project->range('0{4},2,3,4');
$execution->status->range('doing');
$execution->vision->range('rnd');
$execution->grade->range('1,2{3},1{3}');
$execution->path->range('`,1,`,`,1,2,`,`,1,3,`,`,1,4,`, `1,2,5,`,`,1,3,6`,`,1,4,7,`');
$execution->openedBy->range('admin,user1');
$execution->begin->range('20220112 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20220212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->attribute->range('[]{5},design,[]');
$execution->gen(7);

/**

title=测试executionModel->getByProjectTest();
timeout=0
cid=16305

- 敏捷项目执行列表查询
 - 第5条的project属性 @2
 - 第5条的name属性 @迭代1
 - 第5条的type属性 @sprint
- 瀑布项目执行列表查询
 - 第6条的project属性 @3
 - 第6条的name属性 @阶段1
 - 第6条的type属性 @stage
- 看板项目执行列表查询
 - 第7条的project属性 @4
 - 第7条的name属性 @看板1
 - 第7条的type属性 @kanban
- wait执行列表查询 @0
- doing执行列表查询
 - 第7条的status属性 @doing
 - 第7条的name属性 @看板1
- 执行列表2条查询 @2
- 执行列表10条查询 @3
- 敏捷项目执行键值对查询属性5 @项目1/迭代1
- 瀑布项目执行键值对查询属性6 @项目2/阶段1
- 看板项目执行键值对查询属性7 @项目3/看板1
- wait执行键值对查询 @0
- doing执行键值对查询属性7 @项目3/看板1
- 执行键值对2条查询 @2
- 执行键值对10条查询 @3
- 测试获取开发阶段 @0
- 测试获取开发阶段或id=7的执行 @1

*/

$projectIdList = array(0, 2, 3, 4);
$status        = array('all', 'wait', 'doing');
$limit         = array(0, 2, 10);
$pairsList     = array(false, true);
$develList     = array(false, true);
$appendIdList  = array(0, 7);
$count         = array(0, 1);

$executionTester = new executionTest();
r($executionTester->getByProjectTest($projectIdList[1], $status[0], $limit[0], $pairsList[0], $develList[0], $appendIdList[0], $count[0])) && p('5:project,name,type') && e('2,迭代1,sprint');      // 敏捷项目执行列表查询
r($executionTester->getByProjectTest($projectIdList[2], $status[0], $limit[0], $pairsList[0], $develList[0], $appendIdList[0], $count[0])) && p('6:project,name,type') && e('3,阶段1,stage'); // 瀑布项目执行列表查询
r($executionTester->getByProjectTest($projectIdList[3], $status[0], $limit[0], $pairsList[0], $develList[0], $appendIdList[0], $count[0])) && p('7:project,name,type') && e('4,看板1,kanban');      // 看板项目执行列表查询
r($executionTester->getByProjectTest($projectIdList[0], $status[1], $limit[0], $pairsList[0], $develList[0], $appendIdList[0], $count[0])) && p()                      && e('0');                   // wait执行列表查询
r($executionTester->getByProjectTest($projectIdList[0], $status[2], $limit[0], $pairsList[0], $develList[0], $appendIdList[0], $count[0])) && p('7:status,name')       && e('doing,看板1');         // doing执行列表查询
r($executionTester->getByProjectTest($projectIdList[0], $status[2], $limit[1], $pairsList[0], $develList[0], $appendIdList[0], $count[1])) && p()                      && e('2');                   // 执行列表2条查询
r($executionTester->getByProjectTest($projectIdList[0], $status[2], $limit[2], $pairsList[0], $develList[0], $appendIdList[0], $count[1])) && p()                      && e('3');                   // 执行列表10条查询
r($executionTester->getByProjectTest($projectIdList[1], $status[0], $limit[0], $pairsList[1], $develList[0], $appendIdList[0], $count[0])) && p('5')                   && e('项目1/迭代1');         // 敏捷项目执行键值对查询
r($executionTester->getByProjectTest($projectIdList[2], $status[0], $limit[0], $pairsList[1], $develList[0], $appendIdList[0], $count[0])) && p('6')                   && e('项目2/阶段1');   // 瀑布项目执行键值对查询
r($executionTester->getByProjectTest($projectIdList[3], $status[0], $limit[0], $pairsList[1], $develList[0], $appendIdList[0], $count[0])) && p('7')                   && e('项目3/看板1');         // 看板项目执行键值对查询
r($executionTester->getByProjectTest($projectIdList[0], $status[1], $limit[0], $pairsList[1], $develList[0], $appendIdList[0], $count[0])) && p()                      && e('0');                   // wait执行键值对查询
r($executionTester->getByProjectTest($projectIdList[0], $status[2], $limit[0], $pairsList[1], $develList[0], $appendIdList[0], $count[0])) && p('7')                   && e('项目3/看板1');         // doing执行键值对查询
r($executionTester->getByProjectTest($projectIdList[0], $status[2], $limit[1], $pairsList[1], $develList[0], $appendIdList[0], $count[1])) && p()                      && e('2');                   // 执行键值对2条查询
r($executionTester->getByProjectTest($projectIdList[0], $status[2], $limit[2], $pairsList[1], $develList[0], $appendIdList[0], $count[1])) && p()                      && e('3');                   // 执行键值对10条查询
r($executionTester->getByProjectTest($projectIdList[0], $status[2], $limit[2], $pairsList[1], $develList[1], $appendIdList[0], $count[1])) && p()                      && e('0');                   // 测试获取开发阶段
r($executionTester->getByProjectTest($projectIdList[0], $status[2], $limit[2], $pairsList[1], $develList[1], $appendIdList[1], $count[1])) && p()                      && e('1');                   // 测试获取开发阶段或id=7的执行
