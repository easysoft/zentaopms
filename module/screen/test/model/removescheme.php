#!/usr/bin/env php
<?php

/**

title=测试 screenModel::removeScheme();
timeout=0
cid=18280

- 步骤1：正常情况 - 包含scheme的screens数组
 - 第0条的id属性 @1
 - 第0条的name属性 @Test Screen 1
 - 第1条的id属性 @2
 - 第1条的name属性 @Test Screen 2
- 步骤2：边界值 - 空数组输入 @0
- 步骤3：单个对象情况 - 单个screen对象包含scheme属性
 - 第0条的id属性 @1
 - 第0条的name属性 @Test Screen 1
- 步骤4：复合情况 - 多个screen对象的复合数组
 - 第0条的id属性 @1
 - 第0条的name属性 @Test Screen 1
 - 第1条的id属性 @2
 - 第1条的name属性 @Test Screen 2
 - 第2条的id属性 @3
 - 第2条的name属性 @Test Screen 3
- 步骤5：边界情况 - 不包含scheme属性的screen对象
 - 第0条的id属性 @3
 - 第0条的name属性 @Test Screen 3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$screenTest = new screenModelTest();

// 4. 测试数据准备
$screenWithScheme1 = new stdClass();
$screenWithScheme1->id = 1;
$screenWithScheme1->name = 'Test Screen 1';
$screenWithScheme1->scheme = '{"componentList": [{"id": 1}]}';

$screenWithScheme2 = new stdClass();
$screenWithScheme2->id = 2;
$screenWithScheme2->name = 'Test Screen 2';
$screenWithScheme2->scheme = '{"componentList": [{"id": 2}]}';

$screenWithoutScheme = new stdClass();
$screenWithoutScheme->id = 3;
$screenWithoutScheme->name = 'Test Screen 3';

// 5. 强制要求：必须包含至少5个测试步骤
r($screenTest->removeSchemeTest(array($screenWithScheme1, $screenWithScheme2))) && p('0:id,name;1:id,name') && e('1,Test Screen 1;2,Test Screen 2'); // 步骤1：正常情况 - 包含scheme的screens数组
r($screenTest->removeSchemeTest(array())) && p() && e(0); // 步骤2：边界值 - 空数组输入
r($screenTest->removeSchemeTest(array($screenWithScheme1))) && p('0:id,name') && e('1,Test Screen 1'); // 步骤3：单个对象情况 - 单个screen对象包含scheme属性
r($screenTest->removeSchemeTest(array($screenWithScheme1, $screenWithScheme2, $screenWithoutScheme))) && p('0:id,name;1:id,name;2:id,name') && e('1,Test Screen 1;2,Test Screen 2;3,Test Screen 3'); // 步骤4：复合情况 - 多个screen对象的复合数组
r($screenTest->removeSchemeTest(array($screenWithoutScheme))) && p('0:id,name') && e('3,Test Screen 3'); // 步骤5：边界情况 - 不包含scheme属性的screen对象