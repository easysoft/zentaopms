#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/stage.class.php';
su('admin');

zdTable('stage')->gen(0);

/**

title=测试 stageModel->batchCreate();
cid=1
pid=1

测试批量创建瀑布项目的阶段 1 >> 2
测试批量创建瀑布项目的阶段 2 >> 4
测试批量创建瀑布项目的阶段 3 >> 6
测试批量创建瀑布项目的阶段 4 >> 7
测试批量创建瀑布项目的百分比为空的阶段 >> 『工作量占比』不能为空。
测试批量创建瀑布项目的类型为空的阶段 >> 『阶段类型』不能为空。
测试批量创建瀑布项目的百分比非法的阶段 >> 『工作量占比』应当是数字，可以是小数。
测试批量创建瀑布项目的百分比超出100的阶段 >> 工作量占比累计不应当超过100%
测试批量创建融合瀑布项目的阶段 1 >> 2
测试批量创建融合瀑布项目的阶段 2 >> 4
测试批量创建融合瀑布项目的阶段 3 >> 6
测试批量创建融合瀑布项目的阶段 4 >> 7
测试批量创建融合瀑布项目的百分比为空的阶段 >> 『工作量占比』不能为空。
测试批量创建融合瀑布项目的类型为空的阶段 >> 『阶段类型』不能为空。
测试批量创建融合瀑布项目的百分比非法的阶段 >> 『工作量占比』应当是数字，可以是小数。
测试批量创建融合瀑布项目的百分比超出100的阶段 >> 工作量占比累计不应当超过100%

*/
$name1    = array('1' => '批量创建的需求', '2' => '批量创建的设计', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$percent1 = array('1' => '1', '2' => '2', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$type1    = array('1' => 'request', '2' => 'design', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');

$name2    = array('1' => '批量创建的开发', '2' => '批量创建的测试', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$percent2 = array('1' => '3', '2' => '4', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$type2    = array('1' => 'dev', '2' => 'qa', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');

$name3    = array('1' => '批量创建的发布', '2' => '批量创建的总结评审', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$percent3 = array('1' => '5', '2' => '6', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$type3    = array('1' => 'release', '2' => 'review', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');

$name4    = array('1' => '', '2' => '批量创建的其他', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$percent4 = array('1' => '1', '2' => '7', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$type4    = array('1' => 'request', '2' => 'other', '3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');

$emptyPercent   = array('1' => '', '2' => '','3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$emptyType      = array('1' => '', '2' => '','3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$IllegalPercent = array('1' => 'a', '2' => 'b','3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');
$percentOver    = array('1' => '111', '2' => '2','3' => '', '4' => '', '5' => '', '6' => '', '7' => '', '8' => '', '9' => '', '10' => '');


$param1    = array('name' => $name1, 'percent' => $percent1,       'type' => $type1);
$param2    = array('name' => $name2, 'percent' => $percent2,       'type' => $type2);
$param3    = array('name' => $name3, 'percent' => $percent3,       'type' => $type3);
$param4    = array('name' => $name4, 'percent' => $percent4,       'type' => $type4);
$param5    = array('name' => $name1, 'percent' => $emptyPercent,   'type' => $type1);
$param6    = array('name' => $name1, 'percent' => $percent1,       'type' => $emptyType);
$param7    = array('name' => $name1, 'percent' => $IllegalPercent, 'type' => $type1);
$param8    = array('name' => $name1, 'percent' => $percentOver,    'type' => $type1);


$stage = new stageTest();

r($stage->batchCreateTest($param1))                  && p()            && e('2');                                      // 测试批量创建瀑布项目的阶段 1
r($stage->batchCreateTest($param2))                  && p()            && e('4');                                      // 测试批量创建瀑布项目的阶段 2
r($stage->batchCreateTest($param3))                  && p()            && e('6');                                      // 测试批量创建瀑布项目的阶段 3
r($stage->batchCreateTest($param4))                  && p()            && e('7');                                      // 测试批量创建瀑布项目的阶段 4
r($stage->batchCreateTest($param5))                  && p('percent:0') && e('『工作量占比』不能为空。');               // 测试批量创建瀑布项目的百分比为空的阶段
r($stage->batchCreateTest($param6))                  && p('type:0')    && e('『阶段类型』不能为空。');                 // 测试批量创建瀑布项目的类型为空的阶段
r($stage->batchCreateTest($param7))                  && p('percent:0') && e('『工作量占比』应当是数字，可以是小数。'); // 测试批量创建瀑布项目的百分比非法的阶段
r($stage->batchCreateTest($param8))                  && p('message:0') && e('工作量占比累计不应当超过100%');           // 测试批量创建瀑布项目的百分比超出100的阶段
r($stage->batchCreateTest($param1, 'waterfallplus')) && p()            && e('2');                                      // 测试批量创建融合瀑布项目的阶段 1
r($stage->batchCreateTest($param2, 'waterfallplus')) && p()            && e('4');                                      // 测试批量创建融合瀑布项目的阶段 2
r($stage->batchCreateTest($param3, 'waterfallplus')) && p()            && e('6');                                      // 测试批量创建融合瀑布项目的阶段 3
r($stage->batchCreateTest($param4, 'waterfallplus')) && p()            && e('7');                                      // 测试批量创建融合瀑布项目的阶段 4
r($stage->batchCreateTest($param5, 'waterfallplus')) && p('percent:0') && e('『工作量占比』不能为空。');               // 测试批量创建融合瀑布项目的百分比为空的阶段
r($stage->batchCreateTest($param6, 'waterfallplus')) && p('type:0')    && e('『阶段类型』不能为空。');                 // 测试批量创建融合瀑布项目的类型为空的阶段
r($stage->batchCreateTest($param7, 'waterfallplus')) && p('percent:0') && e('『工作量占比』应当是数字，可以是小数。'); // 测试批量创建融合瀑布项目的百分比非法的阶段
r($stage->batchCreateTest($param8, 'waterfallplus')) && p('message:0') && e('工作量占比累计不应当超过100%');           // 测试批量创建融合瀑布项目的百分比超出100的阶段
