#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->getTaskRelated().
timeout=0
cid=1

- 测试当objectType为task,objectID为1时，返回的数据是否正确
 - 第0条的1属性 @1
 - 属性1 @11
 - 属性2 @11
- 测试当objectType为task,objectID为3时，返回的数据是否正确
 - 第0条的1属性 @1
 - 属性1 @13
 - 属性2 @11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('project')->config('execution')->gen(1);
zdTable('task')->config('task')->gen(5);
zdTable('story')->gen(10);
zdTable('projectproduct')->gen(20);

$actionTest = new actionTest();

$objectTypeList = array('task');
$objectIDList   = array(1, 3);

r($actionTest->getTaskRelated($objectTypeList[0], $objectIDList[0])) && p('0:1;1;2')  && e('1,11,11');   //测试当objectType为task,objectID为1时，返回的数据是否正确
r($actionTest->getTaskRelated($objectTypeList[0], $objectIDList[1])) && p('0:1;1;2')  && e('1,13,11');   //测试当objectType为task,objectID为3时，返回的数据是否正确