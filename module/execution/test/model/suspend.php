#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$execution = zenData('project');
$execution->id->range('1-7');
$execution->name->range('项目1,迭代1,迭代2,迭代3,迭代4,迭代5,迭代6');
$execution->type->range('project,sprint{2},waterfall{2},kanban{2}');
$execution->status->range('doing,wait');
$execution->gen(7);

su('admin');

/**

title=测试executionModel->suspendTest();
cid=16370
pid=1

- wait敏捷执行挂起
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @suspended
- doing敏捷执行挂起
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @suspended
- wait瀑布执行挂起
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @suspended
- doing瀑布执行挂起
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @suspended
- wait看板执行挂起
 - 第0条的field属性 @status
 - 第0条的old属性 @wait
 - 第0条的new属性 @suspended
- doing看板执行挂起
 - 第0条的field属性 @status
 - 第0条的old属性 @doing
 - 第0条的new属性 @suspended
- 挂起后再次挂起 @0

*/

$executionIDList = array('2', '3', '4', '5', '6', '7');

$execution = new executionModelTest();
r($execution->suspendTest($executionIDList[0])) && p('0:field,old,new') && e('status,wait,suspended');  // wait敏捷执行挂起
r($execution->suspendTest($executionIDList[1])) && p('0:field,old,new') && e('status,doing,suspended'); // doing敏捷执行挂起
r($execution->suspendTest($executionIDList[2])) && p('0:field,old,new') && e('status,wait,suspended');  // wait瀑布执行挂起
r($execution->suspendTest($executionIDList[3])) && p('0:field,old,new') && e('status,doing,suspended'); // doing瀑布执行挂起
r($execution->suspendTest($executionIDList[4])) && p('0:field,old,new') && e('status,wait,suspended');  // wait看板执行挂起
r($execution->suspendTest($executionIDList[5])) && p('0:field,old,new') && e('status,doing,suspended'); // doing看板执行挂起
r($execution->suspendTest($executionIDList[0])) && p()                  && e('0');                      // 挂起后再次挂起
