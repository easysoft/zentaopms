#!/usr/bin/env php
<?php

/**

title=测试 productZen::prepareManageLineExtras();
timeout=0
cid=0

步骤1：正常情况-不同项目集 >> 产品线1
步骤2：边界值-空数据 >> 0
步骤4：空名称过滤 >> 产品线2
步骤5：单个项目集多产品线 >> 3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->prepareManageLineExtrasTest(array(
    'modules' => array('1' => '产品线1', '2' => '产品线2'),
    'programs' => array('1' => '1', '2' => '2')
))) && p('1:1') && e('产品线1'); // 步骤1：正常情况-不同项目集

r($productTest->prepareManageLineExtrasTest(array(
    'modules' => array(),
    'programs' => array()
))) && p() && e('0'); // 步骤2：边界值-空数据

r($productTest->prepareManageLineExtrasTest(array(
    'modules' => array('1' => '产品线1', '2' => '产品线1'),
    'programs' => array('1' => '1', '2' => '1')
))) && p() && e(false); // 步骤3：异常输入-重复名称

r($productTest->prepareManageLineExtrasTest(array(
    'modules' => array('1' => '', '2' => '产品线2'),
    'programs' => array('1' => '1', '2' => '2')
))) && p('2:2') && e('产品线2'); // 步骤4：空名称过滤

r($productTest->prepareManageLineExtrasTest(array(
    'modules' => array('1' => '产品线1', '2' => '产品线2', '3' => '产品线3'),
    'programs' => array('1' => '1', '2' => '1', '3' => '1')
))) && p('1,count') && e('3'); // 步骤5：单个项目集多产品线