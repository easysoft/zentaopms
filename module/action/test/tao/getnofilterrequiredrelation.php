#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->fetchBaseInfo().
timeout=0
cid=1

- 测试获取id为1的product的关联关系
 - 第0条的0属性 @11
 - 属性1 @0
 - 属性2 @0
- 测试获取id为11的project的关联关系
 - 第0条的1属性 @1
 - 属性1 @11
 - 属性2 @0
- 测试获取id为1的execution的关联关系
 - 第0条的1属性 @1
 - 属性1 @5
 - 属性2 @11
- 测试获取id为1的marketresearch的关联关系
 - 第0条的1属性 @~~
 - 属性1 @11
 - 属性2 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('projectproduct')->gen(10);
zdTable('project')->config('execution_for_relation')->gen(1);

$actionTest = new actionTest();

$objectTypeList = array('product', 'project', 'execution', 'marketresearch');
$objectIDList   = array(1, 11,);

r($actionTest->getNoFilterRequiredRelation($objectTypeList[0], $objectIDList[1])) && p('0:0;1;2') && e('11,0,0');   //测试获取id为1的product的关联关系
r($actionTest->getNoFilterRequiredRelation($objectTypeList[1], $objectIDList[1])) && p('0:1;1;2') && e('1,11,0');   //测试获取id为11的project的关联关系
r($actionTest->getNoFilterRequiredRelation($objectTypeList[2], $objectIDList[1])) && p('0:1;1;2') && e('1,5,11');   //测试获取id为1的execution的关联关系
r($actionTest->getNoFilterRequiredRelation($objectTypeList[3], $objectIDList[1])) && p('0:1;1;2') && e('~~,11,0');   //测试获取id为1的marketresearch的关联关系