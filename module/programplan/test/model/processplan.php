#!/usr/bin/env php
<?php

/**

title=测试 programplanModel->processPlan();
cid=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(5);
zdTable('projectproduct')->config('projectproduct')->gen(5);
zdTable('product')->config('product')->gen(2);
$planIDList = array(1, 2, 3);

$programplan = new programplanTest();

r($programplan->processPlanTest($planIDList[0])) && p('id,name,productName')           && e('1,瀑布项目1,瀑布产品1');        // 测试id为1的瀑布项目
r($programplan->processPlanTest($planIDList[1])) && p('id,name,productName,attribute') && e('2,阶段a,瀑布产品2,review');     // 测试id为2的瀑布项目阶段
r($programplan->processPlanTest($planIDList[2])) && p('id,name,productName,attribute') && e('3,阶段a子1,瀑布产品2,release'); // 测试id为3的瀑布项目阶段
