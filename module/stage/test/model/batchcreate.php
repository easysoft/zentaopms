#!/usr/bin/env php
<?php
/**

title=测试 stageModel->batchCreate();
cid=1

- 测试批量创建瀑布项目的阶段 1 @2
- 测试批量创建瀑布项目的阶段 2 @4
- 测试批量创建瀑布项目的阶段 3 @6
- 测试批量创建瀑布项目的阶段 4 @8
- 测试批量创建瀑布项目的百分比为空的阶段 @『工作量占比』不能为空。
- 测试批量创建瀑布项目的类型为空的阶段 @『阶段类型』不能为空。
- 测试批量创建瀑布项目的百分比非法的阶段 @『工作量占比』应当是数字，可以是小数。
- 测试批量创建瀑布项目的百分比超出100的阶段 @工作量占比累计不应当超过100%
- 测试批量创建融合瀑布项目的阶段 1 @2
- 测试批量创建融合瀑布项目的阶段 2 @4
- 测试批量创建融合瀑布项目的阶段 3 @6
- 测试批量创建融合瀑布项目的阶段 4 @8
- 测试批量创建融合瀑布项目的百分比为空的阶段 @『工作量占比』不能为空。
- 测试批量创建融合瀑布项目的类型为空的阶段 @『阶段类型』不能为空。
- 测试批量创建融合瀑布项目的百分比非法的阶段 @『工作量占比』应当是数字，可以是小数。
- 测试批量创建融合瀑布项目的百分比超出100的阶段 @工作量占比累计不应当超过100%

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stage.class.php';

zdTable('user')->gen(5);
zdTable('stage')->gen(0);

$name1    = array(1 => '批量创建的需求', 2 => '批量创建的设计');
$percent1 = array(1 => 1, 2 => 2);
$type1    = array(1 => 'request', 2 => 'design');

$name2    = array(1 => '批量创建的开发', 2 => '批量创建的测试');
$percent2 = array(1 => 3, 2 => 4);
$type2    = array(1 => 'dev', 2 => 'qa');

$name3    = array(1 => '批量创建的发布', 2 => '批量创建的总结评审');
$percent3 = array(1 => 5, 2 => 6);
$type3    = array(1 => 'release', 2 => 'review');

$name4    = array(1 => '批量创建的需求', 2 => '批量创建的其他');
$percent4 = array(1 => 1, 2 => 7);
$type4    = array(1 => 'request', 2 => 'other');

$emptyPercent   = array(1 => 0, 2 => 0);
$emptyType      = array(1 => '', 2 => '');
$IllegalPercent = array(1 => 'a', 2 => 'b');
$percentOver    = array(1 => 111, 2 => 2);

$param1 = array('name' => $name1, 'percent' => $percent1,       'type' => $type1);
$param2 = array('name' => $name2, 'percent' => $percent2,       'type' => $type2);
$param3 = array('name' => $name3, 'percent' => $percent3,       'type' => $type3);
$param4 = array('name' => $name4, 'percent' => $percent4,       'type' => $type4);
$param5 = array('name' => $name1, 'percent' => $emptyPercent,   'type' => $type1);
$param6 = array('name' => $name1, 'percent' => $percent1,       'type' => $emptyType);
$param7 = array('name' => $name1, 'percent' => $IllegalPercent, 'type' => $type1);
$param8 = array('name' => $name1, 'percent' => $percentOver,    'type' => $type1);

$stageTester = new stageTest();

/* Normal condition. */
r($stageTester->batchCreateTest($param1)) && p() && e('2'); // 测试批量创建瀑布项目的阶段 1
r($stageTester->batchCreateTest($param2)) && p() && e('4'); // 测试批量创建瀑布项目的阶段 2
r($stageTester->batchCreateTest($param3)) && p() && e('6'); // 测试批量创建瀑布项目的阶段 3
r($stageTester->batchCreateTest($param4)) && p() && e('8'); // 测试批量创建瀑布项目的阶段 4

/* Error condition. */
r($stageTester->batchCreateTest($param5)) && p('0') && e('『工作量占比』不能为空。');               // 测试批量创建瀑布项目的百分比为空的阶段
r($stageTester->batchCreateTest($param6)) && p('0') && e('『阶段类型』不能为空。');                 // 测试批量创建瀑布项目的类型为空的阶段
r($stageTester->batchCreateTest($param7)) && p('0') && e('『工作量占比』应当是数字，可以是小数。'); // 测试批量创建瀑布项目的百分比非法的阶段
r($stageTester->batchCreateTest($param8)) && p('0') && e('工作量占比累计不应当超过100%');           // 测试批量创建瀑布项目的百分比超出100的阶段

/* Normal condition. */
r($stageTester->batchCreateTest($param1, 'waterfallplus')) && p() && e('2'); // 测试批量创建融合瀑布项目的阶段 1
r($stageTester->batchCreateTest($param2, 'waterfallplus')) && p() && e('4'); // 测试批量创建融合瀑布项目的阶段 2
r($stageTester->batchCreateTest($param3, 'waterfallplus')) && p() && e('6'); // 测试批量创建融合瀑布项目的阶段 3
r($stageTester->batchCreateTest($param4, 'waterfallplus')) && p() && e('8'); // 测试批量创建融合瀑布项目的阶段 4

/* Error condition. */
r($stageTester->batchCreateTest($param5, 'waterfallplus')) && p('0') && e('『工作量占比』不能为空。');               // 测试批量创建融合瀑布项目的百分比为空的阶段
r($stageTester->batchCreateTest($param6, 'waterfallplus')) && p('0') && e('『阶段类型』不能为空。');                 // 测试批量创建融合瀑布项目的类型为空的阶段
r($stageTester->batchCreateTest($param7, 'waterfallplus')) && p('0') && e('『工作量占比』应当是数字，可以是小数。'); // 测试批量创建融合瀑布项目的百分比非法的阶段
r($stageTester->batchCreateTest($param8, 'waterfallplus')) && p('0') && e('工作量占比累计不应当超过100%');           // 测试批量创建融合瀑布项目的百分比超出100的阶段
