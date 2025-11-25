#!/usr/bin/env php
<?php

/**

title=测试 docModel::getSubSpacesByType();
timeout=0
cid=16129

- 步骤1：获取所有类型子空间
 - 属性1 @我的空间/我的文档库1
 - 属性4 @团队空间/自定义库1
- 步骤2：获取mine类型子空间
 - 属性1 @我的文档库1
 - 属性2 @我的文档库2
- 步骤3：获取custom类型子空间
 - 属性4 @自定义库1
 - 属性5 @自定义库2
- 步骤4：获取不存在的类型 @0
- 步骤5：测试withType参数为true时的返回格式
 - 属性mine.1 @我的文档库1
 - 属性mine.2 @我的文档库2

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('doclib');
$table->id->range('1-10');
$table->type->range('mine{3},custom{3},product{2},project{2}');
$table->vision->range('rnd');
$table->parent->range('0');
$table->name->range('我的文档库1,我的文档库2,我的文档库3,自定义库1,自定义库2,自定义库3,产品库1,产品库2,项目库1,项目库2');
$table->addedBy->range('admin{5},user1{3},user2{2}');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($docTest->getSubSpacesByTypeTest('all', false)) && p('1,4') && e('我的空间/我的文档库1,团队空间/自定义库1'); // 步骤1：获取所有类型子空间
r($docTest->getSubSpacesByTypeTest('mine', false)) && p('1,2') && e('我的文档库1,我的文档库2'); // 步骤2：获取mine类型子空间
r($docTest->getSubSpacesByTypeTest('custom', false)) && p('4,5') && e('自定义库1,自定义库2'); // 步骤3：获取custom类型子空间
r($docTest->getSubSpacesByTypeTest('nonexistent', false)) && p() && e('0'); // 步骤4：获取不存在的类型
r($docTest->getSubSpacesByTypeTest('mine', true)) && p('mine.1,mine.2') && e('我的文档库1,我的文档库2'); // 步骤5：测试withType参数为true时的返回格式