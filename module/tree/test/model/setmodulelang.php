#!/usr/bin/env php
<?php

/**

title=测试 treeModel::setModuleLang();
timeout=0
cid=19391

- 步骤1：正常情况 - 验证name属性属性name @名称
- 步骤2：验证short属性属性short @简称
- 步骤3：验证返回对象类型 @object
- 步骤4：验证同时检查多个属性
 - 属性name @名称
 - 属性short @简称
- 步骤5：再次调用验证覆盖行为属性name @名称

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tree.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$treeTest = new treeTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($treeTest->setModuleLangTest()) && p('name') && e('名称'); // 步骤1：正常情况 - 验证name属性
r($treeTest->setModuleLangTest()) && p('short') && e('简称'); // 步骤2：验证short属性
r(gettype($treeTest->setModuleLangTest())) && p() && e('object'); // 步骤3：验证返回对象类型
r($treeTest->setModuleLangTest()) && p('name,short') && e('名称,简称'); // 步骤4：验证同时检查多个属性
r($treeTest->setModuleLangTest()) && p('name') && e('名称'); // 步骤5：再次调用验证覆盖行为