#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/execution.class.php';
su('admin');

/**

title=测试 executionModel->checkWorkload();
cid=1
pid=1

测试传入空数据 >> 0
测试创建迭代的工作量 >> 0
测试创建迭代的工作量 >> 工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n
测试创建迭代的工作量 >> "工作量比例"必须为数字\n
测试创建迭代的工作量 >> 0
测试更新迭代的工作量 >> 0
测试更新迭代的工作量 >> 工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n
测试更新迭代的工作量 >> "工作量比例"必须为数字\n
测试更新迭代的工作量 >> 0
测试创建阶段的工作量 >> 0
测试创建阶段的工作量 >> 工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n
测试创建阶段的工作量 >> "工作量比例"必须为数字\n
测试创建阶段的工作量 >> 0
测试更新阶段的工作量 >> 0
测试更新阶段的工作量 >> 工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n
测试更新阶段的工作量 >> "工作量比例"必须为数字\n
测试更新阶段的工作量 >> 0
测试创建看板的工作量 >> 0
测试创建看板的工作量 >> 工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n
测试创建看板的工作量 >> "工作量比例"必须为数字\n
测试创建看板的工作量 >> 0
测试更新看板的工作量 >> 0
测试更新看板的工作量 >> 工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n
测试更新看板的工作量 >> "工作量比例"必须为数字\n
测试更新看板的工作量 >> 0

*/

$executionIdList = array(0, 101, 131, 161);
$typeList        = array('', 'create', 'update');
$percentList     = array('0', '123', 'all', '10');

$execution = new executionTest();

r($execution->checkWorkloadTest($executionIdList[0], $typeList[0], $percentList[0])) && p() && e('0');                                                        // 测试传入空数据
r($execution->checkWorkloadTest($executionIdList[1], $typeList[1], $percentList[0])) && p() && e('0');                                                        // 测试创建迭代的工作量
r($execution->checkWorkloadTest($executionIdList[1], $typeList[1], $percentList[1])) && p() && e('工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n'); // 测试创建迭代的工作量
r($execution->checkWorkloadTest($executionIdList[1], $typeList[1], $percentList[2])) && p() && e('"工作量比例"必须为数字\n');                                 // 测试创建迭代的工作量
r($execution->checkWorkloadTest($executionIdList[1], $typeList[1], $percentList[3])) && p() && e('0');                                                        // 测试创建迭代的工作量
r($execution->checkWorkloadTest($executionIdList[1], $typeList[2], $percentList[0])) && p() && e('0');                                                        // 测试更新迭代的工作量
r($execution->checkWorkloadTest($executionIdList[1], $typeList[2], $percentList[1])) && p() && e('工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n'); // 测试更新迭代的工作量
r($execution->checkWorkloadTest($executionIdList[1], $typeList[2], $percentList[2])) && p() && e('"工作量比例"必须为数字\n');                                 // 测试更新迭代的工作量
r($execution->checkWorkloadTest($executionIdList[1], $typeList[2], $percentList[3])) && p() && e('0');                                                        // 测试更新迭代的工作量
r($execution->checkWorkloadTest($executionIdList[2], $typeList[1], $percentList[0])) && p() && e('0');                                                        // 测试创建阶段的工作量
r($execution->checkWorkloadTest($executionIdList[2], $typeList[1], $percentList[1])) && p() && e('工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n'); // 测试创建阶段的工作量
r($execution->checkWorkloadTest($executionIdList[2], $typeList[1], $percentList[2])) && p() && e('"工作量比例"必须为数字\n');                                 // 测试创建阶段的工作量
r($execution->checkWorkloadTest($executionIdList[2], $typeList[1], $percentList[3])) && p() && e('0');                                                        // 测试创建阶段的工作量
r($execution->checkWorkloadTest($executionIdList[2], $typeList[2], $percentList[0])) && p() && e('0');                                                        // 测试更新阶段的工作量
r($execution->checkWorkloadTest($executionIdList[2], $typeList[2], $percentList[1])) && p() && e('工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n'); // 测试更新阶段的工作量
r($execution->checkWorkloadTest($executionIdList[2], $typeList[2], $percentList[2])) && p() && e('"工作量比例"必须为数字\n');                                 // 测试更新阶段的工作量
r($execution->checkWorkloadTest($executionIdList[2], $typeList[2], $percentList[3])) && p() && e('0');                                                        // 测试更新阶段的工作量
r($execution->checkWorkloadTest($executionIdList[3], $typeList[1], $percentList[0])) && p() && e('0');                                                        // 测试创建看板的工作量
r($execution->checkWorkloadTest($executionIdList[3], $typeList[1], $percentList[1])) && p() && e('工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n'); // 测试创建看板的工作量
r($execution->checkWorkloadTest($executionIdList[3], $typeList[1], $percentList[2])) && p() && e('"工作量比例"必须为数字\n');                                 // 测试创建看板的工作量
r($execution->checkWorkloadTest($executionIdList[3], $typeList[1], $percentList[3])) && p() && e('0');                                                        // 测试创建看板的工作量
r($execution->checkWorkloadTest($executionIdList[3], $typeList[2], $percentList[0])) && p() && e('0');                                                        // 测试更新看板的工作量
r($execution->checkWorkloadTest($executionIdList[3], $typeList[2], $percentList[1])) && p() && e('工作量占比累计不应当超过100, 当前产品下的工作量之和为%\n'); // 测试更新看板的工作量
r($execution->checkWorkloadTest($executionIdList[3], $typeList[2], $percentList[2])) && p() && e('"工作量比例"必须为数字\n');                                 // 测试更新看板的工作量
r($execution->checkWorkloadTest($executionIdList[3], $typeList[2], $percentList[3])) && p() && e('0');                                                        // 测试更新看板的工作量
