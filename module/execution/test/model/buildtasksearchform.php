#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/execution.unittest.class.php';

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

$query = zenData('userquery');
$query->id->range('1');
$query->account->range('admin');
$query->module->range('task');
$query->title->range('搜索条件1');
$query->form->range('`a:59:{s:9:"fieldname";s:0:"";s:11:"fieldstatus";s:0:"";s:9:"fielddesc";s:0:"";s:15:"fieldassignedTo";s:0:"";s:8:"fieldpri";s:1:"0";s:14:"fieldexecution";s:0:"";s:11:"fieldmodule";s:4:"ZERO";s:13:"fieldestimate";s:0:"";s:9:"fieldleft";s:0:"";s:13:"fieldconsumed";s:0:"";s:9:"fieldtype";s:0:"";s:12:"fieldfromBug";s:0:"";s:17:"fieldclosedReason";s:0:"";s:13:"fieldopenedBy";s:0:"";s:15:"fieldfinishedBy";s:0:"";s:13:"fieldclosedBy";s:0:"";s:15:"fieldcanceledBy";s:0:"";s:17:"fieldlastEditedBy";s:0:"";s:11:"fieldmailto";s:0:"";s:15:"fieldopenedDate";s:0:"";s:13:"fielddeadline";s:0:"";s:15:"fieldestStarted";s:0:"";s:16:"fieldrealStarted";s:0:"";s:17:"fieldassignedDate";s:0:"";s:17:"fieldfinishedDate";s:0:"";s:15:"fieldclosedDate";s:0:"";s:17:"fieldcanceledDate";s:0:"";s:19:"fieldlastEditedDate";s:0:"";s:18:"fieldactivatedDate";s:0:"";s:7:"fieldid";s:0:"";s:6:"andOr1";s:3:"AND";s:6:"field1";s:4:"name";s:9:"operator1";s:7:"include";s:6:"value1";s:6:"任务";s:6:"andOr2";s:3:"and";s:6:"field2";s:2:"id";s:9:"operator2";s:1:"=";s:6:"value2";s:0:"";s:6:"andOr3";s:3:"and";s:6:"field3";s:6:"status";s:9:"operator3";s:1:"=";s:6:"value3";s:0:"";s:10:"groupAndOr";s:3:"and";s:6:"andOr4";s:3:"AND";s:6:"field4";s:4:"desc";s:9:"operator4";s:7:"include";s:6:"value4";s:0:"";s:6:"andOr5";s:3:"and";s:6:"field5";s:10:"assignedTo";s:9:"operator5";s:1:"=";s:6:"value5";s:0:"";s:6:"andOr6";s:3:"and";s:6:"field6";s:3:"pri";s:9:"operator6";s:1:"=";s:6:"value6";s:1:"0";s:6:"module";s:4:"task";s:9:"actionURL";s:41:"/execution-task-3-bySearch-myQueryID.html";s:10:"groupItems";s:1:"3";s:8:"formType";s:4:"lite";}`');
$query->sql->range("`(( 1   AND `name`  LIKE '%任务%' ) AND ( 1  )) AND deleted = '0'`");
$query->gen(1);

su('admin');

/**

title=测试executionModel->buildTaskSearchForm();
timeout=0
cid=1

- 传递项目 ID，缓存查询参数，打印 queryID 的值。 @0
- 传递项目 ID，缓存查询参数，打印 actionURL 的值。 @0
- 传递项目 ID，缓存查询参数，打印所属执行列表。 @0
- 传递项目 ID，不缓存查询参数，打印 queryID 的值。属性queryID @1
- 传递项目 ID，不缓存查询参数，打印 actionURL 的值。属性actionURL @/execution-task-3-bySearch-myQueryID.html
- 传递项目 ID，不缓存查询参数，打印所属执行列表。
 -  @~~
 - 属性3 @迭代1
 - 属性4 @迭代2
 - 属性5 @迭代3
- 传递执行 ID，不缓存查询参数，打印 queryID 的值。属性queryID @1
- 传递执行 ID，不缓存查询参数，打印 actionURL 的值。属性actionURL @/execution-task-3-bySearch-myQueryID.html
- 传递执行 ID，不缓存查询参数，打印所属执行列表。
 -  @~~
 - 属性3 @迭代1
 - 属性4 @~~
 - 属性5 @~~
 - 属性all @所有执行

*/

$projectID   = 1;
$productID   = 0;
$executionID = 3;
$executions  = [3 => '迭代1', 4 => '迭代2', 5 => '迭代3'];
$queryID     = 1;
$module      = 'task';
$actionURL   = '/execution-task-3-bySearch-myQueryID.html';

$execution = new executionTest();

$searchConfig = $execution->buildTaskSearchFormTest($projectID, $productID, $executions, $queryID, $actionURL, $module, true);
r(isset($searchConfig['queryID']))                       && p() && e(0); // 传递项目 ID，缓存查询参数，打印 queryID 的值。
r(isset($searchConfig['actionURL']))                     && p() && e(0); // 传递项目 ID，缓存查询参数，打印 actionURL 的值。
r(isset($searchConfig['params']['execution']['values'])) && p() && e(0); // 传递项目 ID，缓存查询参数，打印所属执行列表。

$searchConfig = $execution->buildTaskSearchFormTest($projectID, $productID, $executions, $queryID, $actionURL, $module, false);
r($searchConfig)                                  && p('queryID')   && e('1');                                         // 传递项目 ID，不缓存查询参数，打印 queryID 的值。
r($searchConfig)                                  && p('actionURL') && e('/execution-task-3-bySearch-myQueryID.html'); // 传递项目 ID，不缓存查询参数，打印 actionURL 的值。
r($searchConfig['params']['execution']['values']) && p(',3,4,5')    && e('~~,迭代1,迭代2,迭代3');                      // 传递项目 ID，不缓存查询参数，打印所属执行列表。

$searchConfig = $execution->buildTaskSearchFormTest($executionID, $productID, $executions, $queryID, $actionURL, $module, false);
r($searchConfig)                                  && p('queryID')    && e('1');                                         // 传递执行 ID，不缓存查询参数，打印 queryID 的值。
r($searchConfig)                                  && p('actionURL')  && e('/execution-task-3-bySearch-myQueryID.html'); // 传递执行 ID，不缓存查询参数，打印 actionURL 的值。
r($searchConfig['params']['execution']['values']) && p(',3,4,5,all') && e('~~,迭代1,~~,~~,所有执行');                   // 传递执行 ID，不缓存查询参数，打印所属执行列表。
