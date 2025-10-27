#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getAppVolumes();
timeout=0
cid=0

- 步骤1：正常实例获取数据卷第0条的name属性 @data-volume
- 步骤2：MySQL组件获取数据卷第0条的name属性 @mysql-data
- 步骤3：Redis组件获取数据卷第0条的name属性 @redis-data
- 步骤4：非块设备卷第0条的is_block_device属性 @~~
- 步骤5：不存在实例返回值 @0
- 步骤6：空数据卷数组返回值 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 3. 🔴 强制要求：必须包含至少6个测试步骤
r($cneTest->getAppVolumesTest(1, false)) && p('0:name') && e('data-volume'); // 步骤1：正常实例获取数据卷
r($cneTest->getAppVolumesTest(2, true)) && p('0:name') && e('mysql-data'); // 步骤2：MySQL组件获取数据卷
r($cneTest->getAppVolumesTest(3, 'redis')) && p('0:name') && e('redis-data'); // 步骤3：Redis组件获取数据卷
r($cneTest->getAppVolumesTest(4, false)) && p('0:is_block_device') && e('~~'); // 步骤4：非块设备卷
r($cneTest->getAppVolumesTest(999, false)) && p() && e('0'); // 步骤5：不存在实例返回值
r($cneTest->getAppVolumesTest(5, false)) && p() && e('0'); // 步骤6：空数据卷数组返回值