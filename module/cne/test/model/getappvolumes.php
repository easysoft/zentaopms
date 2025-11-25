#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getAppVolumes();
timeout=0
cid=15613

- 步骤1：正常实例获取数据卷信息第0条的name属性 @data-volume
- 步骤2：使用component参数为true获取MySQL数据卷第0条的name属性 @mysql-data
- 步骤3：使用component参数为字符串获取Redis数据卷第0条的name属性 @redis-data
- 步骤4：测试返回非块设备的数据卷第0条的name属性 @config-volume
- 步骤5：测试不存在的实例ID @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('instance')->gen(0);
zenData('space')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->getAppVolumesTest(1, false)) && p('0:name') && e('data-volume'); // 步骤1：正常实例获取数据卷信息
r($cneTest->getAppVolumesTest(2, true)) && p('0:name') && e('mysql-data'); // 步骤2：使用component参数为true获取MySQL数据卷
r($cneTest->getAppVolumesTest(3, 'redis')) && p('0:name') && e('redis-data'); // 步骤3：使用component参数为字符串获取Redis数据卷
r($cneTest->getAppVolumesTest(4, false)) && p('0:name') && e('config-volume'); // 步骤4：测试返回非块设备的数据卷
r($cneTest->getAppVolumesTest(999, false)) && p() && e('0'); // 步骤5：测试不存在的实例ID