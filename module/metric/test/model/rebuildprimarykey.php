#!/usr/bin/env php
<?php

/**

title=测试 metricModel::rebuildPrimaryKey();
timeout=0
cid=17154

- 步骤1：空表重建主键 @0
- 步骤2：有数据的表重建主键属性result @success
- 步骤3：不连续ID的表重建主键属性result @success
- 步骤4：大量数据重建主键属性result @success
- 步骤5：验证自增值设置属性autoIncrement @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$metricTest = new metricModelTest();

// 4. 测试步骤（必须包含至少5个测试步骤）
r($metricTest->rebuildPrimaryKeyTest()) && p() && e('0'); // 步骤1：空表重建主键
r($metricTest->rebuildPrimaryKeyTest('normal')) && p('result') && e('success'); // 步骤2：有数据的表重建主键
r($metricTest->rebuildPrimaryKeyTest('discontinuous')) && p('result') && e('success'); // 步骤3：不连续ID的表重建主键
r($metricTest->rebuildPrimaryKeyTest('large')) && p('result') && e('success'); // 步骤4：大量数据重建主键
r($metricTest->rebuildPrimaryKeyTest('verify')) && p('autoIncrement') && e('1'); // 步骤5：验证自增值设置