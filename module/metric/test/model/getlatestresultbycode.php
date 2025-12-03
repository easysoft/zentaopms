#!/usr/bin/env php
<?php

/**

title=测试 metricModel::getLatestResultByCode();
timeout=0
cid=17102

- 步骤1:使用不存在的代号查询返回空数组 @0
- 步骤2:检查返回结果是数组类型 @1
- 步骤3:使用options参数调用方法并检查返回array @1
- 步骤4:使用pager参数调用方法并检查返回array @1
- 步骤5:使用vision参数调用方法并检查返回array @1
- 步骤6:使用多个参数调用方法并检查返回array @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 登录用户
su('admin');

// 准备测试数据
zendata('product')->gen(10);
zendata('project')->gen(10);
zendata('metric')->loadYaml('getlatestresultbycode', false, 2)->gen(10);
zendata('metriclib')->loadYaml('getlatestresultbycode', false, 2)->gen(100);

// 创建测试实例
$metricTest = new metricModelTest();

r(count($metricTest->getLatestResultByCodeTest('nonexistent_code'))) && p() && e('0'); // 步骤1:使用不存在的代号查询返回空数组
r(is_array($metricTest->getLatestResultByCodeTest('test_metric_1'))) && p() && e('1'); // 步骤2:检查返回结果是数组类型
r(is_array($metricTest->getLatestResultByCodeTest('test_metric_2', array('product' => '1')))) && p() && e('1'); // 步骤3:使用options参数调用方法并检查返回array
r(is_array($metricTest->getLatestResultByCodeTest('test_metric_3', array(), null))) && p() && e('1'); // 步骤4:使用pager参数调用方法并检查返回array
r(is_array($metricTest->getLatestResultByCodeTest('test_metric_1', array(), null, 'rnd'))) && p() && e('1'); // 步骤5:使用vision参数调用方法并检查返回array
r(is_array($metricTest->getLatestResultByCodeTest('test_metric_2', array('year' => '2024'), null, 'rnd'))) && p() && e('1'); // 步骤6:使用多个参数调用方法并检查返回array