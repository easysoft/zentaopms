#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试executionModel->getTasksTest();
timeout=0
cid=16343

- 敏捷执行任务查询
 - 第1条的execution属性 @3
 - 第1条的name属性 @任务1
- 瀑布执行任务查询
 - 第2条的execution属性 @4
 - 第2条的project属性 @12
- 看板执行任务查询
 - 第3条的execution属性 @5
 - 第3条的status属性 @wait
- 正常产品查询任务
 - 第1条的execution属性 @3
 - 第1条的name属性 @任务1
- unclosed任务查询
 - 第1条的execution属性 @3
 - 第1条的status属性 @wait
- wait任务查询
 - 第1条的execution属性 @3
 - 第1条的status属性 @wait
- doing任务查询
 - 第4条的execution属性 @3
 - 第4条的status属性 @doing
- undone任务查询
 - 第4条的execution属性 @3
 - 第4条的status属性 @doing
- done任务查询
 - 第3条的execution属性 @0
 - 第3条的status属性 @0
- 根据查询条件查询任务
 - 第1条的execution属性 @3
 - 第1条的name属性 @任务1
- 根据模块查询任务
 - 第1条的execution属性 @0
 - 第1条的module属性 @0
- name_asc,id_asc排序查询
 - 第1条的execution属性 @3
 - 第1条的name属性 @任务1
- id_asc排序查询
 - 第1条的execution属性 @3
 - 第1条的type属性 @test
- pri_desc,id_desc排序查询
 - 第1条的execution属性 @3
 - 第1条的status属性 @wait
- 敏捷执行任务查询统计 @4
- 瀑布执行任务查询统计 @3
- 看板执行任务查询统计 @3
- 正常产品查询任务统计 @1
- unclosed任务查询统计 @4
- wat任务查询统计 @2
- doing任务查询统计 @2
- undone任务查询统计 @4
- done任务查询统计 @0
- 根据查询条件查询任务统计 @4
- 根据模块查询任务统计 @0
- name_asc,id_asc排序查询统计 @4
- id_asc排序查询统计 @4
- pri_desc,id_desc排序查询统计 @4

*/

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->status->range('doing');
$execution->parent->range('0,0,1,1,2');
$execution->project->range('0,0,1,1,2');
$execution->grade->range('2{2},1{3}');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->begin->range('20230102 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->end->range('20230212 000000:0')->type('timestamp')->format('YY/MM/DD');
$execution->gen(5);

$task = zenData('task');
$task->id->range('1-10');
$task->name->range('1-10')->prefix('任务');
$task->execution->range('3-5');
$task->type->range('test,devel');
$task->status->range('wait,doing');
$task->estimate->range('1-10');
$task->left->range('1-10');
$task->consumed->range('1-10');
$task->gen(10);

$product = zenData('product');
$product->id->range('1-3');
$product->name->range('1-3')->prefix('产品');
$product->code->range('1-3')->prefix('product');
$product->type->range('normal');
$product->status->range('normal');
$product->gen(3);

$query = zenData('userquery');
$query->id->range('1');
$query->account->range('admin');
$query->module->range('task');
$query->title->range('搜索条件1');
$query->form->range('`a:59:{s:9:"fieldname";s:0:"";s:11:"fieldstatus";s:0:"";s:9:"fielddesc";s:0:"";s:15:"fieldassignedTo";s:0:"";s:8:"fieldpri";s:1:"0";s:14:"fieldexecution";s:0:"";s:11:"fieldmodule";s:4:"ZERO";s:13:"fieldestimate";s:0:"";s:9:"fieldleft";s:0:"";s:13:"fieldconsumed";s:0:"";s:9:"fieldtype";s:0:"";s:12:"fieldfromBug";s:0:"";s:17:"fieldclosedReason";s:0:"";s:13:"fieldopenedBy";s:0:"";s:15:"fieldfinishedBy";s:0:"";s:13:"fieldclosedBy";s:0:"";s:15:"fieldcanceledBy";s:0:"";s:17:"fieldlastEditedBy";s:0:"";s:11:"fieldmailto";s:0:"";s:15:"fieldopenedDate";s:0:"";s:13:"fielddeadline";s:0:"";s:15:"fieldestStarted";s:0:"";s:16:"fieldrealStarted";s:0:"";s:17:"fieldassignedDate";s:0:"";s:17:"fieldfinishedDate";s:0:"";s:15:"fieldclosedDate";s:0:"";s:17:"fieldcanceledDate";s:0:"";s:19:"fieldlastEditedDate";s:0:"";s:18:"fieldactivatedDate";s:0:"";s:7:"fieldid";s:0:"";s:6:"andOr1";s:3:"AND";s:6:"field1";s:4:"name";s:9:"operator1";s:7:"include";s:6:"value1";s:6:"任务";s:6:"andOr2";s:3:"and";s:6:"field2";s:2:"id";s:9:"operator2";s:1:"=";s:6:"value2";s:0:"";s:6:"andOr3";s:3:"and";s:6:"field3";s:6:"status";s:9:"operator3";s:1:"=";s:6:"value3";s:0:"";s:10:"groupAndOr";s:3:"and";s:6:"andOr4";s:3:"AND";s:6:"field4";s:4:"desc";s:9:"operator4";s:7:"include";s:6:"value4";s:0:"";s:6:"andOr5";s:3:"and";s:6:"field5";s:10:"assignedTo";s:9:"operator5";s:1:"=";s:6:"value5";s:0:"";s:6:"andOr6";s:3:"and";s:6:"field6";s:3:"pri";s:9:"operator6";s:1:"=";s:6:"value6";s:1:"0";s:6:"module";s:4:"task";s:9:"actionURL";s:41:"/execution-task-3-bySearch-myQueryID.html";s:10:"groupItems";s:1:"3";s:8:"formType";s:4:"lite";}`');
$query->sql->range("`(( 1   AND `name`  LIKE '%任务%' ) AND ( 1  )) AND deleted = '0'`");
$query->gen(1);

$product = zenData('module');
$product->id->range('1-10');
$product->name->range('1-10')->prefix('模块');
$product->root->range('1-3');
$product->parent->range('0,1{9}');
$product->type->range('task');
$product->gen(10);

zenData('story')->gen(1);

su('admin');

$executionIDList = array('0', '3', '4', '5');
$productIDList   = array('0', '1');
$browseType      = array('all', 'unclosed', 'wait', 'doing', 'undone', 'done', 'closed', 'bysearch');
$queryID         = array('0', '1');
$moduleID        = array('0', '10');
$sort            = array('status,id_desc', 'name_asc,id_asc', 'id_asc', 'pri_desc,id_desc');
$count           = array('0', '1');

$execution = new executionModelTest();
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[0], $sort[0], $count[0])) && p('1:execution,name')    && e('3,任务1'); // 敏捷执行任务查询
r($execution->getTasksTest($productIDList[0], $executionIDList[2], $browseType[0], $queryID[0], $moduleID[0], $sort[0], $count[0])) && p('2:execution,project') && e('4,12');    // 瀑布执行任务查询
r($execution->getTasksTest($productIDList[0], $executionIDList[3], $browseType[0], $queryID[0], $moduleID[0], $sort[0], $count[0])) && p('3:execution,status')  && e('5,wait');  // 看板执行任务查询
r($execution->getTasksTest($productIDList[1], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[0], $sort[0], $count[0])) && p('1:execution,name')    && e('3,任务1'); // 正常产品查询任务
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[1], $queryID[0], $moduleID[0], $sort[0], $count[0])) && p('1:execution,status')  && e('3,wait');  // unclosed任务查询
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[2], $queryID[0], $moduleID[0], $sort[0], $count[0])) && p('1:execution,status')  && e('3,wait');  // wait任务查询
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[3], $queryID[0], $moduleID[0], $sort[0], $count[0])) && p('4:execution,status')  && e('3,doing'); // doing任务查询
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[4], $queryID[0], $moduleID[0], $sort[0], $count[0])) && p('4:execution,status')  && e('3,doing'); // undone任务查询
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[5], $queryID[0], $moduleID[0], $sort[0], $count[0])) && p('3:execution,status')  && e('0,0');     // done任务查询
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[7], $queryID[1], $moduleID[0], $sort[0], $count[0])) && p('1:execution,name')    && e('3,任务1'); // 根据查询条件查询任务
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[1], $sort[0], $count[0])) && p('1:execution,module')  && e('0,0');     // 根据模块查询任务
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[0], $sort[1], $count[0])) && p('1:execution,name')    && e('3,任务1'); // name_asc,id_asc排序查询
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[0], $sort[2], $count[0])) && p('1:execution,type')    && e('3,test');  // id_asc排序查询
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[0], $sort[3], $count[0])) && p('1:execution,status')  && e('3,wait');  // pri_desc,id_desc排序查询
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[0], $sort[0], $count[1])) && p()                      && e('4');       // 敏捷执行任务查询统计
r($execution->getTasksTest($productIDList[0], $executionIDList[2], $browseType[0], $queryID[0], $moduleID[0], $sort[0], $count[1])) && p()                      && e('3');       // 瀑布执行任务查询统计
r($execution->getTasksTest($productIDList[0], $executionIDList[3], $browseType[0], $queryID[0], $moduleID[0], $sort[0], $count[1])) && p()                      && e('3');       // 看板执行任务查询统计
r($execution->getTasksTest($productIDList[1], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[0], $sort[0], $count[1])) && p()                      && e('1');       // 正常产品查询任务统计
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[1], $queryID[0], $moduleID[0], $sort[0], $count[1])) && p()                      && e('4');       // unclosed任务查询统计
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[2], $queryID[0], $moduleID[0], $sort[0], $count[1])) && p()                      && e('2');       // wat任务查询统计
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[3], $queryID[0], $moduleID[0], $sort[0], $count[1])) && p()                      && e('2');       // doing任务查询统计
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[4], $queryID[0], $moduleID[0], $sort[0], $count[1])) && p()                      && e('4');       // undone任务查询统计
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[5], $queryID[0], $moduleID[0], $sort[0], $count[1])) && p()                      && e('0');       // done任务查询统计
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[7], $queryID[1], $moduleID[0], $sort[0], $count[1])) && p()                      && e('4');       // 根据查询条件查询任务统计
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[1], $sort[0], $count[1])) && p()                      && e('0');       // 根据模块查询任务统计
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[0], $sort[1], $count[1])) && p()                      && e('4');       // name_asc,id_asc排序查询统计
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[0], $sort[2], $count[1])) && p()                      && e('4');       // id_asc排序查询统计
r($execution->getTasksTest($productIDList[0], $executionIDList[1], $browseType[0], $queryID[0], $moduleID[0], $sort[3], $count[1])) && p()                      && e('4');       // pri_desc,id_desc排序查询统计
