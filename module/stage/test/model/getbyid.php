#!/usr/bin/env php
<?php
/**

title=测试 stageModel->getByID();
cid=1

- 测试获取ID=0的阶段 @0
- 测试获取ID=1的阶段
 - 属性name @需求1
 - 属性percent @10
 - 属性type @request
- 测试获取ID不存在的阶段 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stage.class.php';

zdTable('stage')->config('stage')->gen(1);
zdTable('user')->gen(5);

$stageIds = array(0, 1, 2);

$stageTester = new stageTest();
r($stageTester->getByIDTest($stageIds[0])) && p()                    && e('0');                // 测试获取ID=0的阶段
r($stageTester->getByIDTest($stageIds[1])) && p('name,percent,type') && e('需求1,10,request'); // 测试获取ID=1的阶段
r($stageTester->getByIDTest($stageIds[2])) && p()                    && e('0');                // 测试获取ID不存在的阶段
