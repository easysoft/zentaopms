#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getObjectBaseInfo().
timeout=0
cid=1

- 判断actionType为linked2testtask,extra为1的用例相关信息。
 - 第0条的0属性 @1
 - 属性1 @11
 - 属性2 @101
- 判断actionType为unlinkedfromtesttask,extra为1的用例相关信息。
 - 第0条的0属性 @1
 - 属性1 @11
 - 属性2 @101
- 判断actionType为assigned,extra为1的用例相关信息。
 - 第0条的0属性 @1
 - 属性1 @11
 - 属性2 @101
- 判断actionType为run,extra为1的用例相关信息。
 - 第0条的0属性 @1
 - 属性1 @11
 - 属性2 @101
- 判断actionType为closed,extra为1的用例相关信息。
 - 第0条的0属性 @1
 - 属性1 @0
 - 属性2 @101

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('action')->config('action')->gen(8);
zdTable('case')->gen(10);

$actionTest = new actionTest();

$objectTypeList = array('case');
$actionTypeList = array('linked2testtask', 'unlinkedfromtesttask', 'assigned', 'run', 'closed');
$objectIDList   = array(1, 2);
$extraList      = array(1);

r($actionTest->getCaseRelated($objectTypeList[0], $actionTypeList[0], $objectIDList[0], $extraList[0])) && p('0:0;1;2') && e('1,11,101');   //判断actionType为linked2testtask,extra为1的用例相关信息。
r($actionTest->getCaseRelated($objectTypeList[0], $actionTypeList[1], $objectIDList[0], $extraList[0])) && p('0:0;1;2') && e('1,11,101');   //判断actionType为unlinkedfromtesttask,extra为1的用例相关信息。
r($actionTest->getCaseRelated($objectTypeList[0], $actionTypeList[2], $objectIDList[0], $extraList[0])) && p('0:0;1;2') && e('1,11,101');   //判断actionType为assigned,extra为1的用例相关信息。
r($actionTest->getCaseRelated($objectTypeList[0], $actionTypeList[3], $objectIDList[0], $extraList[0])) && p('0:0;1;2') && e('1,11,101');   //判断actionType为run,extra为1的用例相关信息。
r($actionTest->getCaseRelated($objectTypeList[0], $actionTypeList[4], $objectIDList[1], $extraList[0])) && p('0:0;1;2') && e('1,0,101');    //判断actionType为closed,extra为1的用例相关信息。