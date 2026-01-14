#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

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

$query = zenData('userquery');
$query->id->range('1');
$query->account->range('admin');
$query->module->range('execution');
$query->title->range('搜索条件1');
$query->form->range('`a:44:{s:9:"fieldname";s:0:"";s:7:"fieldid";s:0:"";s:11:"fieldstatus";s:1:"0";s:12:"fieldproject";s:1:"0";s:7:"fieldPM";s:0:"";s:13:"fieldopenedBy";s:0:"";s:15:"fieldopenedDate";s:0:"";s:10:"fieldbegin";s:0:"";s:8:"fieldend";s:0:"";s:14:"fieldrealBegan";s:0:"";s:12:"fieldrealEnd";s:0:"";s:13:"fieldclosedBy";s:0:"";s:19:"fieldlastEditedDate";s:0:"";s:15:"fieldclosedDate";s:0:"";s:14:"fieldteamCount";s:0:"";s:6:"andOr1";s:3:"AND";s:6:"field1";s:4:"name";s:9:"operator1";s:7:"include";s:6:"value1";s:6:"迭代";s:6:"andOr2";s:3:"and";s:6:"field2";s:2:"id";s:9:"operator2";s:1:"=";s:6:"value2";s:0:"";s:6:"andOr3";s:3:"and";s:6:"field3";s:6:"status";s:9:"operator3";s:1:"=";s:6:"value3";s:1:"0";s:10:"groupAndOr";s:3:"and";s:6:"andOr4";s:3:"AND";s:6:"field4";s:7:"project";s:9:"operator4";s:1:"=";s:6:"value4";s:1:"0";s:6:"andOr5";s:3:"and";s:6:"field5";s:2:"PM";s:9:"operator5";s:1:"=";s:6:"value5";s:0:"";s:6:"andOr6";s:3:"and";s:6:"field6";s:8:"openedBy";s:9:"operator6";s:1:"=";s:6:"value6";s:0:"";s:6:"module";s:9:"execution";s:9:"actionURL";s:50:"/execution-all-bySearch-order_asc-0-myQueryID.html";s:10:"groupItems";s:1:"3";s:8:"formType";s:4:"lite";}`');
$query->sql->range("`(( 1   AND `name`  LIKE '%迭代%' ) AND ( 1  ))`");
$query->gen(1);

su('admin');

/**

title=测试executionModel->buildSearchForm();
cid=16273

- 错误的queryID @0
- 正确的queryID @1
- 正确的queryID @2
- 正确的queryID @3
- 正确的queryID @4

*/

$queryIDList = array('0', '1', '2', '3', '4');

$execution = new executionModelTest();
r($execution->buildSearchFormTest($queryIDList[1])) && p() && e('1'); // 正确的queryID
r($execution->buildSearchFormTest($queryIDList[0])) && p() && e('0'); // 错误的queryID
r($execution->buildSearchFormTest($queryIDList[2])) && p() && e('2'); // 错误的queryID
r($execution->buildSearchFormTest($queryIDList[3])) && p() && e('3'); // 错误的queryID
r($execution->buildSearchFormTest($queryIDList[4])) && p() && e('4'); // 错误的queryID
