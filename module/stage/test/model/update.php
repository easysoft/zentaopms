#!/usr/bin/env php
<?php
/**

title=测试 stageModel->update();
cid=1

- 测试修改ID=0阶段的名称 @0
- 测试修改名称
 - 第0条的field属性 @name
 - 第0条的old属性 @需求1
 - 第0条的new属性 @修改后的需求
- 测试修改工作量占比
 - 第0条的field属性 @percent
 - 第0条的old属性 @10
 - 第0条的new属性 @15
- 测试修改阶段分类
 - 第0条的field属性 @type
 - 第0条的old属性 @request
 - 第0条的new属性 @other
- 测试修改不存在阶段的名称 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stage.class.php';

zdTable('stage')->config('stage')->gen(1);

$stageIds      = array(0, 1, 2);
$changeName    = array('name' => '修改后的需求');
$changePercent = array('percent' => '15');
$changeType    = array('type' => 'other');

$stageTester = new stageTest();
r($stageTester->updateTest($stageIds[0], $changeName))    && p()                  && e('0');                       // 测试修改ID=0阶段的名称
r($stageTester->updateTest($stageIds[1], $changeName))    && p('0:field,old,new') && e('name,需求1,修改后的需求'); // 测试修改名称
r($stageTester->updateTest($stageIds[1], $changePercent)) && p('0:field,old,new') && e('percent,10,15');           // 测试修改工作量占比
r($stageTester->updateTest($stageIds[1], $changeType))    && p('0:field,old,new') && e('type,request,other');      // 测试修改阶段分类
r($stageTester->updateTest($stageIds[2], $changeName))    && p()                  && e('0');                       // 测试修改不存在阶段的名称
