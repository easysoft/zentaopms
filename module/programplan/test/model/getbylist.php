#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(8);
/**

title=测试 programplanModel->getByList();
cid=1
pid=1

*/

$planIDList = array(array(2), array(3, 4), array(5, 6, 7), array(10000));

$programplan = new programplanTest();

r($programplan->getByListTest($planIDList[0])) && p('2:name')   && e('瀑布项目2'); // 测试获取项目2的名称
r($programplan->getByListTest($planIDList[0])) && p('2:status') && e('doing');     // 测试获取项目2的状态

$result = $programplan->getByListTest($planIDList[1]);
r(count($result)) && p() && e('2'); // 测试获取项目3 4的取出的数量

$result = $programplan->getByListTest($planIDList[2]);
r($result[5]) && p('name,desc') && e('瀑布项目5,项目描述5'); // 测试获取项目5的名称和描述信息
r(count($result)) && p() && e('3');                          // 测试获取5 6 7 项目数量

r($programplan->getByListTest($planIDList[3])) && p() && e('0'); // 测试获取不存在的项目信息
