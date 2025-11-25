#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getDiskSettings();
timeout=0
cid=15618

- 步骤1：测试正常实例但没有块设备卷的情况属性resizable @0
- 步骤2：测试不存在的实例ID
 - 属性size @0
 - 属性used @0
 - 属性limit @0
- 步骤3：测试带MySQL组件参数的实例属性resizable @0
- 步骤4：测试component参数为布尔值true的情况
 - 属性resizable @0
 - 属性requestSize @0
- 步骤5：测试component参数为空字符串的情况
 - 属性resizable @0
 - 属性size @0
 - 属性used @0
 - 属性limit @0

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

// 5. 强制要求：必须包含至少5个测试步骤
r($cneTest->getDiskSettingsTest(1, false)) && p('resizable') && e('0'); // 步骤1：测试正常实例但没有块设备卷的情况
r($cneTest->getDiskSettingsTest(999, false)) && p('size;used;limit') && e('0;0;0'); // 步骤2：测试不存在的实例ID
r($cneTest->getDiskSettingsTest(1, 'mysql')) && p('resizable') && e('0'); // 步骤3：测试带MySQL组件参数的实例
r($cneTest->getDiskSettingsTest(1, true)) && p('resizable;requestSize') && e('0;0'); // 步骤4：测试component参数为布尔值true的情况
r($cneTest->getDiskSettingsTest(2, '')) && p('resizable;size;used;limit') && e('0;0;0;0'); // 步骤5：测试component参数为空字符串的情况