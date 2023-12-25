#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao->fetchBaseInfo().
timeout=0
cid=1

- 测试获取id为1的action的基本信息
 - 属性id @3
 - 属性objectType @product
 - 属性objectID @1
 - 属性action @common
- 测试获取id为2的action的基本信息
 - 属性id @2
 - 属性objectType @story
 - 属性objectID @2
 - 属性action @extra
- 测试获取id为3的action的基本信息
 - 属性id @3
 - 属性objectType @productplan
 - 属性objectID @3
 - 属性action @opened
- 测试获取id为0的action的基本信息,由于id为0的action不存在,所以返回false @

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/action.class.php';

zdTable('action')->gen(5);

$actionIDList = array(1, 2, 3, 0);

$actionTest = new actionTest();

r($actionTest->fetchBaseInfo($actionIDList[0])) && p('id,objectType,objectID,action') && e('1,product,1,common');       //测试获取id为1的action的基本信息
r($actionTest->fetchBaseInfo($actionIDList[1])) && p('id,objectType,objectID,action') && e('2,story,2,extra');          //测试获取id为2的action的基本信息
r($actionTest->fetchBaseInfo($actionIDList[2])) && p('id,objectType,objectID,action') && e('3,productplan,3,opened');   //测试获取id为3的action的基本信息
r($actionTest->fetchBaseInfo($actionIDList[3])) && p('') && e(0);                                                      //测试获取id为0的action的基本信息,由于id为0的action不存在,所以返回false
