#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processFieldSettings();
timeout=0
cid=17419

- 执行pivotTest模块的processFieldSettingsTest方法，参数是$pivot1  @0
- 执行pivotTest模块的processFieldSettingsTest方法，参数是$pivot2  @0
- 执行pivotTest模块的processFieldSettingsTest方法，参数是$pivot3  @1
- 执行pivotTest模块的processFieldSettingsTest方法，参数是$pivot4  @1
- 执行pivotTest模块的processFieldSettingsTest方法，参数是$pivot5  @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤

// 测试步骤1：测试空fieldSettings的处理（空字符串）
$pivot1 = (object)array('fieldSettings' => '');
r($pivotTest->processFieldSettingsTest($pivot1)) && p() && e('0');

// 测试步骤2：测试fieldSettings为空数组的处理
$pivot2 = (object)array('fieldSettings' => array());
r($pivotTest->processFieldSettingsTest($pivot2)) && p() && e('0');

// 测试步骤3：测试有字段内容的fieldSettings
$pivot3 = (object)array('fieldSettings' => array('field1' => 'value1'));
r($pivotTest->processFieldSettingsTest($pivot3)) && p() && e('1');

// 测试步骤4：测试含SQL字段的对象处理
$pivot4 = (object)array(
    'fieldSettings' => array('field1' => 'value1'),
    'sql' => 'SELECT * FROM zt_user'
);
r($pivotTest->processFieldSettingsTest($pivot4)) && p() && e('1');

// 测试步骤5：测试含filters字段的对象处理
$pivot5 = (object)array(
    'fieldSettings' => array('field1' => 'value1'),
    'sql' => 'SELECT id, account FROM zt_user',
    'filters' => array('status' => 'active')
);
r($pivotTest->processFieldSettingsTest($pivot5)) && p() && e('1');