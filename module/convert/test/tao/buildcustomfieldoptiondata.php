#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildCustomFieldOptionData();
timeout=0
cid=15807

- 步骤1：正常自定义字段选项数据转换 - ID属性id @1001
- 步骤1：正常自定义字段选项数据转换 - customfield属性customfield @1
- 步骤1：正常自定义字段选项数据转换 - customfieldconfig属性customfieldconfig @2
- 步骤1：正常自定义字段选项数据转换 - customvalue属性customvalue @Option A
- 步骤1：正常自定义字段选项数据转换 - disabled属性disabled @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTaoTest();

// 4. 必须包含至少5个测试步骤
r($convertTest->buildCustomFieldOptionDataTest(array('id' => '1001', 'customfield' => '1', 'customfieldconfig' => '2', 'value' => 'Option A', 'disabled' => '0'))) && p('id') && e('1001'); // 步骤1：正常自定义字段选项数据转换 - ID
r($convertTest->buildCustomFieldOptionDataTest(array('id' => '1001', 'customfield' => '1', 'customfieldconfig' => '2', 'value' => 'Option A', 'disabled' => '0'))) && p('customfield') && e('1'); // 步骤1：正常自定义字段选项数据转换 - customfield
r($convertTest->buildCustomFieldOptionDataTest(array('id' => '1001', 'customfield' => '1', 'customfieldconfig' => '2', 'value' => 'Option A', 'disabled' => '0'))) && p('customfieldconfig') && e('2'); // 步骤1：正常自定义字段选项数据转换 - customfieldconfig
r($convertTest->buildCustomFieldOptionDataTest(array('id' => '1001', 'customfield' => '1', 'customfieldconfig' => '2', 'value' => 'Option A', 'disabled' => '0'))) && p('customvalue') && e('Option A'); // 步骤1：正常自定义字段选项数据转换 - customvalue
r($convertTest->buildCustomFieldOptionDataTest(array('id' => '1001', 'customfield' => '1', 'customfieldconfig' => '2', 'value' => 'Option A', 'disabled' => '0'))) && p('disabled') && e('0'); // 步骤1：正常自定义字段选项数据转换 - disabled