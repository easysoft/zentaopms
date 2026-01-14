#!/usr/bin/env php
<?php

/**

title=测试 screenModel::buildGroup();
timeout=0
cid=18211

- 步骤1：空component，验证添加了默认styles.hueRotate第styles条的hueRotate属性 @0
- 步骤2：已有styles，验证保持不变属性styles @customStyles
- 步骤3：已有status，验证保持不变属性status @customStatus
- 步骤4：已有request，验证保持不变属性request @customRequest
- 步骤5：已有events，验证保持不变属性events @customEvents

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenModelTest();

// 4. 准备测试数据
$emptyComponent = new stdclass();

$componentWithStyles = new stdclass();
$componentWithStyles->styles = 'customStyles';

$componentWithStatus = new stdclass();
$componentWithStatus->status = 'customStatus';

$componentWithRequest = new stdclass();
$componentWithRequest->request = 'customRequest';

$componentWithEvents = new stdclass();
$componentWithEvents->events = 'customEvents';

// 5. 强制要求：必须包含至少5个测试步骤
r($screenTest->buildGroupTest($emptyComponent)) && p('styles:hueRotate') && e('0'); // 步骤1：空component，验证添加了默认styles.hueRotate
r($screenTest->buildGroupTest($componentWithStyles)) && p('styles') && e('customStyles'); // 步骤2：已有styles，验证保持不变
r($screenTest->buildGroupTest($componentWithStatus)) && p('status') && e('customStatus'); // 步骤3：已有status，验证保持不变
r($screenTest->buildGroupTest($componentWithRequest)) && p('request') && e('customRequest'); // 步骤4：已有request，验证保持不变
r($screenTest->buildGroupTest($componentWithEvents)) && p('events') && e('customEvents'); // 步骤5：已有events，验证保持不变