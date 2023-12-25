#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getActionListByTypeAndID().
timeout=0
cid=1

- 测试获取当objectType为project，并且限定objectID在1,2,3之间的操作记录，返回的数据是否正确
 - 第1条的id属性 @1
 - 第1条的objectType属性 @project
 - 第1条的objectID属性 @1
 - 第3条的id属性 @3
 - 第3条的objectType属性 @build
 - 第3条的objectID属性 @3
- 测试获取当objectType为story，并且限定objectID在4,5之间的操作记录，返回的数据是否正确
 - 第4条的id属性 @4
 - 第4条的objectType属性 @story
 - 第4条的objectID属性 @4
 - 第5条的id属性 @5
 - 第5条的objectType属性 @requirement
 - 第5条的objectID属性 @5
- 测试获取当objectType为case，并且限定objectID在6,7之间的操作记录，返回的数据是否正确
 - 第6条的id属性 @6
 - 第6条的objectType属性 @case
 - 第6条的objectID属性 @6
 - 第7条的id属性 @7
 - 第7条的objectType属性 @testcase
 - 第7条的objectID属性 @7
- 测试获取当objectType为module，并且限定objectID在8,12之间以及限定的模块id之间的操作记录，返回的数据是否正确
 - 第8条的id属性 @8
 - 第8条的objectType属性 @module
 - 第8条的objectID属性 @8
 - 第12条的id属性 @12
 - 第12条的objectType属性 @module
 - 第12条的objectID属性 @12
- 测试获取当objectType为bug，并且限定objectID在13,14之间的操作记录，返回的数据是否正确
 - 第13条的id属性 @13
 - 第13条的objectType属性 @bug
 - 第13条的objectID属性 @13

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('action')->config('action')->gen(18);
$actionTest = new actionTest();

$objectTypeList = array('project', 'testtask', 'buld', 'story', 'requirement', 'case', 'testcase', 'module', 'bug', 'testsuite');
$objectIDList   = array(array(1, 2, 3), array(4, 5), array(6, 7), array(8), array(13, 14));
$modulesList    = array(array(), array(9, 10, 11, 12));

r($actionTest->getActionListByTypeAndID($objectTypeList[0], $objectIDList[0], $modulesList[0])) && p('1:id,objectType,objectID;3:id,objectType,objectID') && e('1,project,1;3,build,3');        //测试获取当objectType为project，并且限定objectID在1,2,3之间的操作记录，返回的数据是否正确
r($actionTest->getActionListByTypeAndID($objectTypeList[3], $objectIDList[1], $modulesList[0])) && p('4:id,objectType,objectID;5:id,objectType,objectID') && e('4,story,4;5,requirement,5');    //测试获取当objectType为story，并且限定objectID在4,5之间的操作记录，返回的数据是否正确
r($actionTest->getActionListByTypeAndID($objectTypeList[5], $objectIDList[2], $modulesList[0])) && p('6:id,objectType,objectID;7:id,objectType,objectID') && e('6,case,6;7,testcase,7');        //测试获取当objectType为case，并且限定objectID在6,7之间的操作记录，返回的数据是否正确
r($actionTest->getActionListByTypeAndID($objectTypeList[7], $objectIDList[3], $modulesList[1])) && p('8:id,objectType,objectID;12:id,objectType,objectID') && e('8,module,8;12,module,12');     //测试获取当objectType为module，并且限定objectID在8,12之间以及限定的模块id之间的操作记录，返回的数据是否正确
r($actionTest->getActionListByTypeAndID($objectTypeList[8], $objectIDList[4], $modulesList[0])) && p('13:id,objectType,objectID') && e('13,bug,13');                                            //测试获取当objectType为bug，并且限定objectID在13,14之间的操作记录，返回的数据是否正确
