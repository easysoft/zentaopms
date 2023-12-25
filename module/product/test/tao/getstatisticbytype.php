#!/usr/bin/env php
<?php

/**

title=productTao->getStatisticByType();
cid=0

- 获取计划数量属性1 @3
- 获取发布数量属性2 @3
- 获取bug数量属性3 @3
- 获取未解决bug数量属性1 @3
- 获取活跃bug数量属性2 @1
- 获取已解决bug数量属性2 @1
- 获取已关闭bug数量属性1 @1
- 获取本周bug数量属性2 @2
- 获取未指派bug数量属性3 @1
- 获取最新发布第0条的name属性 @产品发布9

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

$bug = zdTable('bug');
$bug->assignedTo->range('user1,admin,[],test1');
$bug->status->range('active,resolved,closed');
$bug->resolution->range('[],fixed{2}');
$bug->openedDate->range('[2023-01-01 10:00:00,' . date('Y-m-d H:i:s') . ']');
$bug->gen(10);

$plan = zdTable('productplan');
$plan->end->range('`' . date('Y-m-d', strtotime('+1 day')) . '`');
$plan->gen(10);

$release = zdTable('release');
$release->product->range('1-4{3}');
$release->gen(10);

zdTable('user')->gen(5);
su('admin');

global $tester;
$product = $tester->loadModel('product');

$typeList = array('plans', 'releases', 'latestReleases', 'bugs', 'unResolved', 'activeBugs', 'fixedBugs', 'closedBugs', 'thisWeekBugs', 'assignToNull');

r($product->getStatisticByType(array(1, 2, 3), $typeList[0])) && p('1') && e('3'); // 获取计划数量
r($product->getStatisticByType(array(1, 2, 3), $typeList[1])) && p('2') && e('3'); // 获取发布数量
r($product->getStatisticByType(array(1, 2, 3), $typeList[3])) && p('3') && e('3'); // 获取bug数量
r($product->getStatisticByType(array(1, 2, 3), $typeList[4])) && p('1') && e('3'); // 获取未解决bug数量
r($product->getStatisticByType(array(1, 2, 3), $typeList[5])) && p('2') && e('1'); // 获取活跃bug数量
r($product->getStatisticByType(array(1, 2, 3), $typeList[6])) && p('2') && e('1'); // 获取已解决bug数量
r($product->getStatisticByType(array(1, 2, 3), $typeList[7])) && p('1') && e('1'); // 获取已关闭bug数量
r($product->getStatisticByType(array(1, 2, 3), $typeList[8])) && p('2') && e('2'); // 获取本周bug数量
r($product->getStatisticByType(array(1, 2, 3), $typeList[9])) && p('3') && e('1'); // 获取未指派bug数量

$lastRelease = $product->getStatisticByType(array(1, 2, 3), $typeList[2]);
r($lastRelease[3]) && p('0:name') && e('产品发布9'); // 获取最新发布
