#!/usr/bin/env php
<?php

/**

title=测试 productZen::prepareManageLineExtras();
timeout=0
cid=0

- 步骤1:正常情况,不同项目集下的产品线
 - 第1条的1属性 @手机产品线
 - 第2条的2属性 @电脑产品线
- 步骤2:同一项目集下有重复产品线名称属性modules[2] @『手机产品线』产品线已经存在，请重新设置！
- 步骤3:空产品线名称被忽略
 - 第1条的1属性 @手机产品线
 - 第1条的3属性 @电脑产品线
- 步骤4:多个不同项目集有多条产品线
 - 第1条的1属性 @手机产品线
 - 第1条的2属性 @平板产品线
 - 第2条的3属性 @电脑产品线
 - 第2条的4属性 @服务器产品线
- 步骤5:所有产品线名称为空 @0

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$productTest = new productZenTest();

// 4. 测试步骤
r($productTest->prepareManageLineExtrasTest(array(1 => '手机产品线', 2 => '电脑产品线'), array(1 => 1, 2 => 2))) && p('1:1;2:2') && e('手机产品线;电脑产品线'); // 步骤1:正常情况,不同项目集下的产品线
r($productTest->prepareManageLineExtrasTest(array(1 => '手机产品线', 2 => '手机产品线'), array(1 => 1, 2 => 1))) && p('modules[2]') && e('『手机产品线』产品线已经存在，请重新设置！'); // 步骤2:同一项目集下有重复产品线名称
r($productTest->prepareManageLineExtrasTest(array(1 => '手机产品线', 2 => '', 3 => '电脑产品线'), array(1 => 1, 2 => 1, 3 => 1))) && p('1:1;1:3') && e('手机产品线;电脑产品线'); // 步骤3:空产品线名称被忽略
r($productTest->prepareManageLineExtrasTest(array(1 => '手机产品线', 2 => '平板产品线', 3 => '电脑产品线', 4 => '服务器产品线'), array(1 => 1, 2 => 1, 3 => 2, 4 => 2))) && p('1:1;1:2;2:3;2:4') && e('手机产品线;平板产品线;电脑产品线;服务器产品线'); // 步骤4:多个不同项目集有多条产品线
r($productTest->prepareManageLineExtrasTest(array(1 => '', 2 => '', 3 => ''), array(1 => 1, 2 => 2, 3 => 3))) && p() && e('0'); // 步骤5:所有产品线名称为空