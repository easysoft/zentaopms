#!/usr/bin/env php
<?php

/**

title=测试 commonModel::checkPrivByObject();
timeout=0
cid=15661

- 步骤1：教程模式下返回true @1
- 步骤2：有效的product对象类型检查权限 @1
- 步骤3：有效的project对象类型检查权限 @1
- 步骤4：有效的execution对象类型检查权限 @1
- 步骤5：无效的对象类型返回false @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('product')->gen(3);
zenData('project')->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$commonTest = new commonModelTest();

// 备份原始教程模式状态
$originalTutorialMode = isset($_SESSION['tutorialMode']) ? $_SESSION['tutorialMode'] : null;

// 5. 强制要求：必须包含至少5个测试步骤
$_SESSION['tutorialMode'] = true;
r($commonTest->checkPrivByObjectTest('product', 1)) && p() && e('1'); // 步骤1：教程模式下返回true

// 恢复正常模式
unset($_SESSION['tutorialMode']);

r($commonTest->checkPrivByObjectTest('product', 1)) && p() && e('1'); // 步骤2：有效的product对象类型检查权限
r($commonTest->checkPrivByObjectTest('project', 1)) && p() && e('1'); // 步骤3：有效的project对象类型检查权限
r($commonTest->checkPrivByObjectTest('execution', 1)) && p() && e('1'); // 步骤4：有效的execution对象类型检查权限
r($commonTest->checkPrivByObjectTest('invalid', 1)) && p() && e('0'); // 步骤5：无效的对象类型返回false

// 恢复原始session状态
if($originalTutorialMode !== null)
{
    $_SESSION['tutorialMode'] = $originalTutorialMode;
}
else
{
    unset($_SESSION['tutorialMode']);
}