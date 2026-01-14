#!/usr/bin/env php
<?php

/**

title=测试 buildModel::addReleaseLabelForBuilds();
timeout=0
cid=15484

- 测试空版本数组 @0
- 测试单个版本无发布关联
 - 第0条的value属性 @1
 - 第0条的text属性 @Build001
- 测试单个版本有发布关联
 - 第0条的value属性 @2
 - 第0条的text属性 @Build002
- 测试多个版本部分有发布关联 @3
- 测试不存在的产品ID
 - 第0条的value属性 @1
 - 第0条的text属性 @Build001
- 测试版本名称包含特殊字符
 - 第0条的value属性 @4
 - 第0条的text属性 @Build<script>alert(1)</script>
- 测试版本ID和名称的拼音转换
 - 第0条的value属性 @5
 - 第0条的text属性 @构建版本v1.0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('build')->loadYaml('build', false, 2)->gen(10);
zenData('release')->loadYaml('release', false, 2)->gen(5);
zenData('product')->loadYaml('product', false, 2)->gen(5);

su('admin');

$buildTest = new buildModelTest();

r(count($buildTest->addReleaseLabelForBuildsTest(1, array()))) && p() && e('0'); // 测试空版本数组
r($buildTest->addReleaseLabelForBuildsTest(1, array(1 => 'Build001'))) && p('0:value;0:text') && e('1;Build001'); // 测试单个版本无发布关联
r($buildTest->addReleaseLabelForBuildsTest(1, array(2 => 'Build002'))) && p('0:value;0:text') && e('2;Build002'); // 测试单个版本有发布关联
r(count($buildTest->addReleaseLabelForBuildsTest(1, array(1 => 'Build001', 2 => 'Build002', 3 => 'Build003')))) && p() && e('3'); // 测试多个版本部分有发布关联
r($buildTest->addReleaseLabelForBuildsTest(999, array(1 => 'Build001'))) && p('0:value;0:text') && e('1;Build001'); // 测试不存在的产品ID
r($buildTest->addReleaseLabelForBuildsTest(1, array(4 => 'Build<script>alert(1)</script>'))) && p('0:value;0:text') && e('4;Build<script>alert(1)</script>'); // 测试版本名称包含特殊字符
r($buildTest->addReleaseLabelForBuildsTest(1, array(5 => '构建版本v1.0'))) && p('0:value;0:text') && e('5;构建版本v1.0'); // 测试版本ID和名称的拼音转换