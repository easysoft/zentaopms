#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->getProcessGroup();
cid=1

- 获取项目ID为0的按照进度分组的活动列表 @0
- 获取项目ID为60的按照进度分组的活动列表
 - 第0条的process属性 @5
 - 第0条的activity属性 @5
- 获取项目ID不存在的按照进度分组的活动列表 @0
- 获取项目ID为60的按照进度分组的活动列表分组数量 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('programactivity')->config('programactivity')->gen(25);
zdTable('user')->gen(5);

$objectIds = array(0 , 60, 70);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->getProcessGroupTest($objectIds[0]))    && p()                     && e('0');   // 获取项目ID为0的按照进度分组的活动列表
r($stakeholderTester->getProcessGroupTest($objectIds[1])[5]) && p('0:process,activity') && e('5,5'); // 获取项目ID为60的按照进度分组的活动列表
r($stakeholderTester->getProcessGroupTest($objectIds[2]))    && p()                     && e('0');   // 获取项目ID不存在的按照进度分组的活动列表

r(count($stakeholderTester->getProcessGroupTest($objectIds[1]))) && p() && e('5'); // 获取项目ID为60的按照进度分组的活动列表分组数量
