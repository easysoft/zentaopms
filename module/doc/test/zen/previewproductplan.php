#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewProductplan();
timeout=0
cid=0

- 步骤1：正常预览设置页面第data条的0:title属性 @产品计划1
- 步骤2：无效产品ID属性data @~~
- 步骤3：有效ID列表第data条的0:title属性 @产品计划1
- 步骤4：空ID列表属性data @~~
- 步骤5：无效视图类型属性data @~~

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（简化，避免数据库依赖）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->previewProductplanTest('setting', array('action' => 'preview', 'product' => 1), '')) && p('data:0:title') && e('产品计划1'); // 步骤1：正常预览设置页面
r($docTest->previewProductplanTest('setting', array('action' => 'preview', 'product' => 0), '')) && p('data') && e('~~'); // 步骤2：无效产品ID
r($docTest->previewProductplanTest('list', array(), '1,2,3')) && p('data:0:title') && e('产品计划1'); // 步骤3：有效ID列表
r($docTest->previewProductplanTest('list', array(), '')) && p('data') && e('~~'); // 步骤4：空ID列表
r($docTest->previewProductplanTest('invalid', array(), '')) && p('data') && e('~~'); // 步骤5：无效视图类型