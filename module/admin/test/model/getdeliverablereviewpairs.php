#!/usr/bin/env php
<?php
/**

title=测试 adminModel::getDeliverableReviewPairs();
timeout=0
cid=0

- 测试获取所有交付物数量 @10
- 测试获取所有交付物
 - 属性2 @0
 - 属性4 @2
 - 属性6 @4
- 测试获取流程ID为1的交付物数量 @3
- 测试获取流程ID为1的交付物
 - 属性1 @0
 - 属性2 @0
 - 属性3 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('projectdeliverable')->loadYaml('projectdeliverable')->gen(10);
zenData('project')->loadYaml('project')->gen(5);
zenData('user')->gen(5);
su('admin');

$adminTest = new adminModelTest();

$allDeliverable = $adminTest->getDeliverableReviewPairsTest();
r(count($allDeliverable)) && p()        && e('10');    // 测试获取所有交付物数量
r($allDeliverable)        && p('2,4,6') && e('0,2,4'); // 测试获取所有交付物

$groupDeliverable = $adminTest->getDeliverableReviewPairsTest(1);
r(count($groupDeliverable)) && p()        && e('3');     // 测试获取流程ID为1的交付物数量
r($groupDeliverable)        && p('1,2,3') && e('0,0,1'); // 测试获取流程ID为1的交付物
