#!/usr/bin/env php
<?php
/**

title=测试 programplanModel->processPlans();
cid=0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/programplan.class.php';
su('admin');

zdTable('project')->config('project')->gen(6);
zdTable('projectproduct')->config('projectproduct')->gen(6);
zdTable('product')->config('product')->gen(2);
$planIDList = array(array(1, 2), array(3, 4), array(5, 6, 7));

$programplan = new programplanTest();

$result  = $programplan->processPlansTest($planIDList[0]);
$result2 = $programplan->processPlansTest($planIDList[1]);
$result3 = $programplan->processPlansTest($planIDList[2]);

r($result[1])         && p('name,productName') && e('瀑布项目1,瀑布产品1'); // 测试id为1的瀑布项目名称和所属产品
r($result[2])         && p('name,productName') && e('瀑布项目2,瀑布产品2'); // 测试id为2的瀑布项目名称和所属产品
r($result2[3])        && p('name,productName') && e('瀑布项目3,瀑布产品2'); // 测试id为3的瀑布项目名称和所属产品
r($result2[4])        && p('name,productName') && e('瀑布项目4,瀑布产品2'); // 测试id为4的瀑布项目名称和所属产品
r($result3[5])        && p('name,productName') && e('瀑布项目5,瀑布产品2'); // 测试id为5的瀑布项目名称和所属产品
r($result3[6])        && p('name,productName') && e('瀑布项目6,瀑布产品2'); // 测试id为6的瀑布项目名称和所属产品
r(isset($result3[7])) && p()                   && e('0');                   // 测试id不存在的瀑布项目
