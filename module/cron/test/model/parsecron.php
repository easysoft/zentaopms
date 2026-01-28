#!/usr/bin/env php
<?php

/**

title=测试 cronModel::parseCron();
cid=15885

- 测试正常cron表达式解析功能获取id为2的command属性 >> 期望返回执行命令
- 测试正常cron表达式解析功能获取id为3的command属性 >> 期望返回执行命令
- 测试正常cron表达式解析功能获取id为1的command属性 >> 期望返回空值
- 测试空数组输入的边界值处理 >> 期望返回空数组
- 测试无效cron表达式的异常处理 >> 期望正确跳过无效表达式
- 测试解析结果的结构完整性验证 >> 期望包含必要字段
- 测试包含多个有效任务的解析结果 >> 期望正确解析所有有效任务

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

$cron = new cronModelTest();
$cron->init();

// 获取现有cron数据进行测试
$crons = $tester->cron->getCrons('nostop');
$parse = $cron->parseCronTest($crons);

// 测试步骤1：验证id为2的定时任务解析结果
r($parse) && p('2:command') && e('moduleName=execution&methodName=computeburn');

// 测试步骤2：验证id为3的定时任务解析结果
r($parse) && p('3:command') && e('moduleName=execution&methodName=computecfd');

// 测试步骤3：验证id为1的定时任务解析结果（空命令）
r($parse) && p('1:command') && e('~~');

// 测试步骤4：测试空数组输入
r($cron->parseCronTest(array())) && p() && e('0');

// 测试步骤5：测试无效cron表达式处理
$invalidCron = array((object)array(
    'id' => 999,
    'm' => 'invalid',
    'h' => 'invalid',
    'dom' => '*',
    'mon' => '*',
    'dow' => '*',
    'command' => 'test'
));
r($cron->parseCronTest($invalidCron)) && p() && e('0');

// 测试步骤6：测试有效cron表达式的结构
$validCron = array((object)array(
    'id' => 100,
    'm' => '30',
    'h' => '23',
    'dom' => '*',
    'mon' => '*',
    'dow' => '*',
    'command' => 'moduleName=test&methodName=valid'
));
$validResult = $cron->parseCronTest($validCron);
r($validResult) && p('100:schema') && e('30 23 * * *');

// 测试步骤7：测试解析多个有效任务
r(count($parse) > 0) && p() && e('1');