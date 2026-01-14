#!/usr/bin/env php
<?php
/**

title=测试 blockZen::printAssignToMeBlock();
timeout=0
cid=15248

- 步骤1:测试方法执行成功返回success为true属性success @1
- 步骤2:测试返回对象包含权限验证信息属性hasViewPriv @1
- 步骤3:测试返回对象包含数据信息属性hasData @1
- 步骤4:测试总数量统计正确属性totalCount @14
- 步骤5:测试不传入block参数时使用默认参数属性success @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 4. 准备测试数据
$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->count = 15;

$block2 = new stdclass();
$block2->params = new stdclass();
$block2->params->count = 10;

$block3 = new stdclass();
$block3->params = new stdclass();
$block3->params->count = 5;

// 5. 强制要求：必须包含至少5个测试步骤
r($blockTest->printAssignToMeBlockTest($block1)) && p('success') && e('1'); // 步骤1:测试方法执行成功返回success为true
r($blockTest->printAssignToMeBlockTest($block1)) && p('hasViewPriv') && e('1'); // 步骤2:测试返回对象包含权限验证信息
r($blockTest->printAssignToMeBlockTest($block1)) && p('hasData') && e('1'); // 步骤3:测试返回对象包含数据信息
r($blockTest->printAssignToMeBlockTest($block1)) && p('totalCount') && e('14'); // 步骤4:测试总数量统计正确
r($blockTest->printAssignToMeBlockTest()) && p('success') && e('1'); // 步骤5:测试不传入block参数时使用默认参数
