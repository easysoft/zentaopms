#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('user')->gen(1);

$execution = zenData('project');
$execution->id->range('1-5');
$execution->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$execution->type->range('project{2},sprint,stage,kanban');
$execution->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$execution->gen(5);

$module = zenData('module');
$module->name->range('1,2,3,4,5')->prefix('模块');
$module->gen(5);

su('admin');

/**

title=测试 myModel->buildTaskSearchForm();
timeout=0
cid=0

- module 为 workTask，缓存查询参数，查询参数中 queryID 为空。 @0
- module 为 workTask，缓存查询参数，查询参数中 actionURL 为空。 @0
- module 为 workTask，缓存查询参数，查询字段中 closedReason 不为空。 @1
- module 为 workTask，缓存查询参数，查询字段中 closedBy 不为空。 @1
- module 为 workTask，缓存查询参数，查询字段中 closedDate 不为空。 @1
- module 为 workTask，缓存查询参数，查询字段中 canceledBy 不为空。 @1
- module 为 workTask，缓存查询参数，查询字段中 canceledDate 不为空。 @1
- module 为 workTask，缓存查询参数，打印 module 的值。属性module @task
- module 为 workTask，缓存查询参数，打印所属项目列表。
 - 属性1 @0
 - 属性2 @0
 - 属性all @0
- module 为 workTask，缓存查询参数，打印所属执行列表。
 - 属性3 @0
 - 属性4 @0
 - 属性5 @0
 - 属性all @0
- module 为 workTask，缓存查询参数，打印所属模块列表。
 -  @0
 - 属性2 @0
 - 属性5 @0
- module 为 workTask，不缓存查询参数，查询参数中 queryID 不为空。 @1
- module 为 workTask，不缓存查询参数，查询参数中 actionURL 不为空。 @1
- module 为 workTask，不缓存查询参数，查询字段中 closedReason 为空。 @0
- module 为 workTask，不缓存查询参数，查询字段中 closedBy 为空。 @0
- module 为 workTask，不缓存查询参数，查询字段中 closedDate 为空。 @0
- module 为 workTask，不缓存查询参数，查询字段中 canceledBy 为空。 @0
- module 为 workTask，不缓存查询参数，查询字段中 canceledDate 为空。 @0
- module 为 workTask，不缓存查询参数，打印 module 的值。属性module @workTask
- module 为 workTask，不缓存查询参数，打印 queryID 的值。属性queryID @1
- module 为 workTask，不缓存查询参数，打印 actionURL 的值。属性actionURL @/my-work-task.html
- module 为 workTask，不缓存查询参数，打印所属项目列表。
 - 属性1 @项目1
 - 属性2 @项目2
 - 属性all @所有项目
- module 为 workTask，不缓存查询参数，打印所属执行列表。
 - 属性3 @/迭代1
 - 属性4 @/迭代2
 - 属性5 @/迭代3
 - 属性all @所有执行
- module 为 workTask，不缓存查询参数，打印所属模块列表。
 -  @/
 - 属性2 @/模块2
 - 属性5 @/模块5
- module 为 contributeTask，缓存查询参数，查询参数中 queryID 为空。 @0
- module 为 contributeTask，缓存查询参数，查询参数中 actionURL 为空。 @0
- module 为 contributeTask，缓存查询参数，查询字段中 closedReason 不为空。 @1
- module 为 contributeTask，缓存查询参数，查询字段中 closedBy 不为空。 @1
- module 为 contributeTask，缓存查询参数，查询字段中 closedDate 不为空。 @1
- module 为 contributeTask，缓存查询参数，查询字段中 canceledBy 不为空。 @1
- module 为 contributeTask，缓存查询参数，查询字段中 canceledDate 不为空。 @1
- module 为 contributeTask，缓存查询参数，打印 module 的值。属性module @task
- module 为 contributeTask，缓存查询参数，打印所属项目列表。
 - 属性1 @0
 - 属性2 @0
 - 属性all @0
- module 为 contributeTask，缓存查询参数，打印所属执行列表。
 - 属性3 @0
 - 属性4 @0
 - 属性5 @0
 - 属性all @0
- module 为 contributeTask，缓存查询参数，打印所属模块列表。
 -  @0
 - 属性2 @0
 - 属性5 @0
- 不缓存查询参数，module 为 contributeTask，查询参数中 module 不为空。 @1
- 不缓存查询参数，module 为 contributeTask，查询参数中 queryID 不为空。 @1
- 不缓存查询参数，module 为 contributeTask，查询参数中 actionURL 不为空。 @1
- 不缓存查询参数，module 为 contributeTask，查询字段中 closedReason 不为空。 @1
- 不缓存查询参数，module 为 contributeTask，查询字段中 closedBy 不为空。 @1
- 不缓存查询参数，module 为 contributeTask，查询字段中 closedDate 不为空。 @1
- 不缓存查询参数，module 为 contributeTask，查询字段中 canceledBy 不为空。 @1
- 不缓存查询参数，module 为 contributeTask，查询字段中 canceledDate 不为空。 @1
- 不缓存查询参数，module 为 contributeTask，打印 module 的值。属性module @contributeTask
- 不缓存查询参数，module 为 contributeTask，打印 queryID 的值。属性queryID @1
- 不缓存查询参数，module 为 contributeTask，打印 actionURL 的值。属性actionURL @/my-contribute-task.html
- 不缓存查询参数，module 为 contributeTask，打印所属项目列表。
 - 属性1 @项目1
 - 属性2 @项目2
 - 属性all @所有项目
- 不缓存查询参数，module 为 contributeTask，打印所属执行列表。
 - 属性3 @/迭代1
 - 属性4 @/迭代2
 - 属性5 @/迭代3
 - 属性all @所有执行
- 不缓存查询参数，module 为 contributeTask，打印所属模块列表。
 -  @/
 - 属性2 @/模块2
 - 属性5 @/模块5

*/

$my = new myTest();

$queryID      = 1;
$module       = 'workTask';
$actionURL    = "/my-work-task.html";
$searchConfig = $my->buildTaskSearchFormTest($queryID, $actionURL, $module, true);
r(isset($searchConfig['queryID']))                && p() && e(0); // module 为 workTask，缓存查询参数，查询参数中 queryID 为空。
r(isset($searchConfig['actionURL']))              && p() && e(0); // module 为 workTask，缓存查询参数，查询参数中 actionURL 为空。
r(isset($searchConfig['fields']['closedReason'])) && p() && e(1); // module 为 workTask，缓存查询参数，查询字段中 closedReason 不为空。
r(isset($searchConfig['fields']['closedBy']))     && p() && e(1); // module 为 workTask，缓存查询参数，查询字段中 closedBy 不为空。
r(isset($searchConfig['fields']['closedDate']))   && p() && e(1); // module 为 workTask，缓存查询参数，查询字段中 closedDate 不为空。
r(isset($searchConfig['fields']['canceledBy']))   && p() && e(1); // module 为 workTask，缓存查询参数，查询字段中 canceledBy 不为空。
r(isset($searchConfig['fields']['canceledDate'])) && p() && e(1); // module 为 workTask，缓存查询参数，查询字段中 canceledDate 不为空。

r($searchConfig)                                  && p('module')    && e('task');    // module 为 workTask，缓存查询参数，打印 module 的值。
r($searchConfig['params']['project']['values'])   && p('1,2,all')   && e('0,0,0');   // module 为 workTask，缓存查询参数，打印所属项目列表。
r($searchConfig['params']['execution']['values']) && p('3,4,5,all') && e('0,0,0,0'); // module 为 workTask，缓存查询参数，打印所属执行列表。
r($searchConfig['params']['module']['values'])    && p('0,2,5')     && e('0,0,0');   // module 为 workTask，缓存查询参数，打印所属模块列表。

$searchConfig = $my->buildTaskSearchFormTest($queryID, $actionURL, $module, false);
r(isset($searchConfig['queryID']))                && p() && e(1); // module 为 workTask，不缓存查询参数，查询参数中 queryID 不为空。
r(isset($searchConfig['actionURL']))              && p() && e(1); // module 为 workTask，不缓存查询参数，查询参数中 actionURL 不为空。
r(isset($searchConfig['fields']['closedReason'])) && p() && e(0); // module 为 workTask，不缓存查询参数，查询字段中 closedReason 为空。
r(isset($searchConfig['fields']['closedBy']))     && p() && e(0); // module 为 workTask，不缓存查询参数，查询字段中 closedBy 为空。
r(isset($searchConfig['fields']['closedDate']))   && p() && e(0); // module 为 workTask，不缓存查询参数，查询字段中 closedDate 为空。
r(isset($searchConfig['fields']['canceledBy']))   && p() && e(0); // module 为 workTask，不缓存查询参数，查询字段中 canceledBy 为空。
r(isset($searchConfig['fields']['canceledDate'])) && p() && e(0); // module 为 workTask，不缓存查询参数，查询字段中 canceledDate 为空。

r($searchConfig)                                  && p('module')    && e('workTask');                      // module 为 workTask，不缓存查询参数，打印 module 的值。
r($searchConfig)                                  && p('queryID')   && e('1');                             // module 为 workTask，不缓存查询参数，打印 queryID 的值。
r($searchConfig)                                  && p('actionURL') && e('/my-work-task.html');            // module 为 workTask，不缓存查询参数，打印 actionURL 的值。
r($searchConfig['params']['project']['values'])   && p('1,2,all')   && e('项目1,项目2,所有项目');          // module 为 workTask，不缓存查询参数，打印所属项目列表。
r($searchConfig['params']['execution']['values']) && p('3,4,5,all') && e('/迭代1,/迭代2,/迭代3,所有执行'); // module 为 workTask，不缓存查询参数，打印所属执行列表。
r($searchConfig['params']['module']['values'])    && p('0,2,5')     && e('/,/模块2,/模块5');               // module 为 workTask，不缓存查询参数，打印所属模块列表。

$queryID      = 1;
$module       = 'contributeTask';
$actionURL    = "/my-contribute-task.html";
$searchConfig = $my->buildTaskSearchFormTest($queryID, $actionURL, $module, true);
r(isset($searchConfig['queryID']))                && p() && e(0); // module 为 contributeTask，缓存查询参数，查询参数中 queryID 为空。
r(isset($searchConfig['actionURL']))              && p() && e(0); // module 为 contributeTask，缓存查询参数，查询参数中 actionURL 为空。
r(isset($searchConfig['fields']['closedReason'])) && p() && e(1); // module 为 contributeTask，缓存查询参数，查询字段中 closedReason 不为空。
r(isset($searchConfig['fields']['closedBy']))     && p() && e(1); // module 为 contributeTask，缓存查询参数，查询字段中 closedBy 不为空。
r(isset($searchConfig['fields']['closedDate']))   && p() && e(1); // module 为 contributeTask，缓存查询参数，查询字段中 closedDate 不为空。
r(isset($searchConfig['fields']['canceledBy']))   && p() && e(1); // module 为 contributeTask，缓存查询参数，查询字段中 canceledBy 不为空。
r(isset($searchConfig['fields']['canceledDate'])) && p() && e(1); // module 为 contributeTask，缓存查询参数，查询字段中 canceledDate 不为空。

r($searchConfig)                                  && p('module')    && e('task');    // module 为 contributeTask，缓存查询参数，打印 module 的值。
r($searchConfig['params']['project']['values'])   && p('1,2,all')   && e('0,0,0');   // module 为 contributeTask，缓存查询参数，打印所属项目列表。
r($searchConfig['params']['execution']['values']) && p('3,4,5,all') && e('0,0,0,0'); // module 为 contributeTask，缓存查询参数，打印所属执行列表。
r($searchConfig['params']['module']['values'])    && p('0,2,5')     && e('0,0,0');   // module 为 contributeTask，缓存查询参数，打印所属模块列表。

$searchConfig = $my->buildTaskSearchFormTest($queryID, $actionURL, $module, false);
r(isset($searchConfig['module']))                 && p() && e(1); // 不缓存查询参数，module 为 contributeTask，查询参数中 module 不为空。
r(isset($searchConfig['queryID']))                && p() && e(1); // 不缓存查询参数，module 为 contributeTask，查询参数中 queryID 不为空。
r(isset($searchConfig['actionURL']))              && p() && e(1); // 不缓存查询参数，module 为 contributeTask，查询参数中 actionURL 不为空。
r(isset($searchConfig['fields']['closedReason'])) && p() && e(1); // 不缓存查询参数，module 为 contributeTask，查询字段中 closedReason 不为空。
r(isset($searchConfig['fields']['closedBy']))     && p() && e(1); // 不缓存查询参数，module 为 contributeTask，查询字段中 closedBy 不为空。
r(isset($searchConfig['fields']['closedDate']))   && p() && e(1); // 不缓存查询参数，module 为 contributeTask，查询字段中 closedDate 不为空。
r(isset($searchConfig['fields']['canceledBy']))   && p() && e(1); // 不缓存查询参数，module 为 contributeTask，查询字段中 canceledBy 不为空。
r(isset($searchConfig['fields']['canceledDate'])) && p() && e(1); // 不缓存查询参数，module 为 contributeTask，查询字段中 canceledDate 不为空。

r($searchConfig)                                  && p('module')    && e('contributeTask');                // 不缓存查询参数，module 为 contributeTask，打印 module 的值。
r($searchConfig)                                  && p('queryID')   && e('1');                             // 不缓存查询参数，module 为 contributeTask，打印 queryID 的值。
r($searchConfig)                                  && p('actionURL') && e('/my-contribute-task.html');      // 不缓存查询参数，module 为 contributeTask，打印 actionURL 的值。
r($searchConfig['params']['project']['values'])   && p('1,2,all')   && e('项目1,项目2,所有项目');          // 不缓存查询参数，module 为 contributeTask，打印所属项目列表。
r($searchConfig['params']['execution']['values']) && p('3,4,5,all') && e('/迭代1,/迭代2,/迭代3,所有执行'); // 不缓存查询参数，module 为 contributeTask，打印所属执行列表。
r($searchConfig['params']['module']['values'])    && p('0,2,5')     && e('/,/模块2,/模块5');               // 不缓存查询参数，module 为 contributeTask，打印所属模块列表。