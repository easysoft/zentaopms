#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
$execution->id->range('1-6');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3,迭代4');
$execution->type->range('project{2},sprint,stage,kanban,sprint');
$execution->status->range('doing{5},wait');
$execution->parent->range('0,0,1,1,2');
$execution->grade->range('2{2},1{4}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`,`1,6`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(6);
su('admin');

/**

title=测试executionModel->putoffTest();
cid=16359

- wait执行延期
 - 第0条的field属性 @days
 - 第0条的old属性 @0
 - 第0条的new属性 @5
- 敏捷执行延期
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @wait
- 瀑布阶段延期
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @wait
- 看板执行延期
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @wait

*/

$executionIDList = array(6, 3, 4, 5);

$execution = new executionModelTest();
r($execution->putoffTest($executionIDList[0])) && p('0:field,old,new') && e('days,0,5');          // wait执行延期
r($execution->putoffTest($executionIDList[1])) && p('0:field,old,new') && e('status,doing,wait'); // 敏捷执行延期
r($execution->putoffTest($executionIDList[2])) && p('0:field,old,new') && e('status,doing,wait'); // 瀑布阶段延期
r($execution->putoffTest($executionIDList[3])) && p('0:field,old,new') && e('status,doing,wait'); // 看板执行延期
