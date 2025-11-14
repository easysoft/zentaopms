#!/usr/bin/env php
<?php

/**

title=测试 todoZen::addCycleYearConfig();
timeout=0
cid=19284

- 执行$result1 @1
- 执行data模块的config['type']方法  @month
- 执行data模块的config['type']方法  @day
- 执行data模块的config['specifiedDate']方法  @1
- 执行data模块的config['cycleYear']方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/todozen.unittest.class.php';

su('admin');

$todoTest = new todoTest();

// 测试1：传入空配置的表单对象 - 应返回原对象
$form1 = new stdClass();
$form1->data = new stdClass();
$result1 = $todoTest->addCycleYearConfigTest($form1);
r(is_object($result1)) && p() && e('1');

// 测试2：传入非年循环类型的配置（月循环）- 应保持原配置不变
$form2 = new stdClass();
$form2->data = new stdClass();
$form2->data->config = array('type' => 'month');
$result2 = $todoTest->addCycleYearConfigTest($form2);
r($result2->data->config['type']) && p() && e('month');

// 测试3：传入年循环类型的配置 - 验证类型转换为day
$form3 = new stdClass();
$form3->data = new stdClass();
$form3->data->config = array('type' => 'year');
$result3 = $todoTest->addCycleYearConfigTest($form3);
r($result3->data->config['type']) && p() && e('day');

// 测试4：验证年循环配置的specifiedDate设置
$form4 = new stdClass();
$form4->data = new stdClass();
$form4->data->config = array('type' => 'year');
$result4 = $todoTest->addCycleYearConfigTest($form4);
r($result4->data->config['specifiedDate']) && p() && e('1');

// 测试5：验证年循环配置的cycleYear设置
$form5 = new stdClass();
$form5->data = new stdClass();
$form5->data->config = array('type' => 'year');
$result5 = $todoTest->addCycleYearConfigTest($form5);
r($result5->data->config['cycleYear']) && p() && e('1');