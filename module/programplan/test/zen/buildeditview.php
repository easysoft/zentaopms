#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::buildEditView();
timeout=0
cid=17788

- 步骤1：正常阶段编辑场景属性success @1
- 步骤2：IPD项目模型场景属性success @1
- 步骤3：父阶段mix属性场景属性success @1
- 步骤4：非mix属性父阶段场景属性success @1
- 步骤5：research项目模型场景属性success @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 不使用zendata，直接在测试方法中模拟数据

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$programplanTest = new programplanZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($programplanTest->buildEditViewTest(1)) && p('success') && e('1'); // 步骤1：正常阶段编辑场景
r($programplanTest->buildEditViewTest(4)) && p('success') && e('1'); // 步骤2：IPD项目模型场景
r($programplanTest->buildEditViewTest(7)) && p('success') && e('1'); // 步骤3：父阶段mix属性场景
r($programplanTest->buildEditViewTest(6)) && p('success') && e('1'); // 步骤4：非mix属性父阶段场景
r($programplanTest->buildEditViewTest(8)) && p('success') && e('1'); // 步骤5：research项目模型场景