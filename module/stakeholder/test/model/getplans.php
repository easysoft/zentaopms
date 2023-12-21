#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getPlans();
cid=1

- 获取项目ID为0的干预列表 @0
- 获取项目ID为60的干预列表
 - 第10条的project属性 @60
 - 第10条的activity属性 @10
- 获取项目ID不存在的干预列表 @0
- 获取项目ID为60的干预列表数量 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('intervention')->config('intervention')->gen(10);
zdTable('user')->gen(5);

$objectIds = array(0 , 60, 70);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getPlansTest($objectIds[0])) && p()                      && e('0');     // 获取项目ID为0的干预列表
r($stakeholderTester->getPlansTest($objectIds[1])) && p('10:project,activity') && e('60,10'); // 获取项目ID为60的干预列表
r($stakeholderTester->getPlansTest($objectIds[2])) && p()                      && e('0');     // 获取项目ID不存在的干预列表

r(count($stakeholderTester->getPlansTest($objectIds[1]))) && p() && e('10'); // 获取项目ID为60的干预列表数量
