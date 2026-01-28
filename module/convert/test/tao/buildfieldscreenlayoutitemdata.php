#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildFieldScreenLayoutItemData();
timeout=0
cid=15811

- 步骤1：正常完整数据ID检查属性id @1001
- 步骤1：正常完整数据字段标识符检查属性fieldidentifier @field_summary
- 步骤1：正常完整数据屏幕选项卡检查属性fieldscreentab @main_tab
- 步骤2：缺少可选字段时返回空字符串属性fieldscreentab @~~
- 步骤3：数字ID输入处理属性id @2001

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$convertTest = new convertTaoTest();

// 4. 测试步骤
r($convertTest->buildFieldScreenLayoutItemDataTest(array('id' => '1001', 'fieldidentifier' => 'field_summary', 'fieldscreentab' => 'main_tab'))) && p('id') && e('1001'); // 步骤1：正常完整数据ID检查
r($convertTest->buildFieldScreenLayoutItemDataTest(array('id' => '1001', 'fieldidentifier' => 'field_summary', 'fieldscreentab' => 'main_tab'))) && p('fieldidentifier') && e('field_summary'); // 步骤1：正常完整数据字段标识符检查
r($convertTest->buildFieldScreenLayoutItemDataTest(array('id' => '1001', 'fieldidentifier' => 'field_summary', 'fieldscreentab' => 'main_tab'))) && p('fieldscreentab') && e('main_tab'); // 步骤1：正常完整数据屏幕选项卡检查
r($convertTest->buildFieldScreenLayoutItemDataTest(array('id' => '1002', 'fieldidentifier' => 'field_description'))) && p('fieldscreentab') && e('~~'); // 步骤2：缺少可选字段时返回空字符串
r($convertTest->buildFieldScreenLayoutItemDataTest(array('id' => 2001, 'fieldidentifier' => 'field_assignee', 'fieldscreentab' => 'detail_tab'))) && p('id') && e('2001'); // 步骤3：数字ID输入处理