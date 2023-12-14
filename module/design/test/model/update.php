#!/usr/bin/env php
<?php
/**

title=测试 designModel->update();
cid=1

- 修改设计的所属产品
 - 第0条的field属性 @product
 - 第0条的old属性 @0
 - 第0条的new属性 @1
- 修改设计的名称
 - 第0条的field属性 @name
 - 第0条的old属性 @设计2
 - 第0条的new属性 @修改后的名字
- 修改设计的类型
 - 第0条的field属性 @type
 - 第0条的old属性 @DBDS
 - 第0条的new属性 @HLDS
- 修改设计的所属故事
 - 第0条的field属性 @story
 - 第0条的old属性 @~~
 - 第0条的new属性 @1
- 修改设计的描述
 - 第0条的field属性 @desc
 - 第0条的old属性 @这是设计描述5
 - 第0条的new属性 @修改后的描述
- 测试ID为空 @0
- 测试ID不存在 @0
- 测试名称为空第name条的0属性 @『设计名称』不能为空。
- 测试类型为空第type条的0属性 @『设计类型』不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/design.class.php';

zdTable('design')->config('design')->gen(5);
zdTable('designspec')->config('designspec')->gen(5);
zdTable('user')->gen(5);

$idList = array(1, 2, 3, 4, 5, 0, 6);

$changeProduct = array('product' => 1);
$changeName    = array('name' => '修改后的名字');
$changeType    = array('type' => 'HLDS');
$changeStory   = array('story' => 1);
$changeDesc    = array('desc' => '修改后的描述');
$emptyName     = array('name' => '');
$emptyType     = array('type' => '');

$designTester = new designTest();

/* Normal condition. */
r($designTester->updateTest($idList[0], $changeProduct)) && p('0:field,old,new') && e('product,0,1');                     // 修改设计的所属产品
r($designTester->updateTest($idList[1], $changeName))    && p('0:field,old,new') && e('name,设计2,修改后的名字');         // 修改设计的名称
r($designTester->updateTest($idList[2], $changeType))    && p('0:field,old,new') && e('type,DBDS,HLDS');                  // 修改设计的类型
r($designTester->updateTest($idList[3], $changeStory))   && p('0:field,old,new') && e('story,~~,1');                      // 修改设计的所属故事
r($designTester->updateTest($idList[4], $changeDesc))    && p('0:field,old,new') && e('desc,这是设计描述5,修改后的描述'); // 修改设计的描述

/* Error condition. */
r($designTester->updateTest($idList[5], $changeProduct)) && p()         && e('0');                      // 测试ID为空
r($designTester->updateTest($idList[6], $changeName))    && p()         && e('0');                      // 测试ID不存在
r($designTester->updateTest($idList[0], $emptyName))     && p('name:0') && e('『设计名称』不能为空。'); // 测试名称为空
r($designTester->updateTest($idList[0], $emptyType))     && p('type:0') && e('『设计类型』不能为空。'); // 测试类型为空
