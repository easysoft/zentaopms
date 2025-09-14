#!/usr/bin/env php
<?php

/**

title=测试 pivotZen::show();
timeout=0
cid=0

- 步骤1：正常透视表显示
 - 属性hasVersionMark @0
 - 属性pivotName @测试透视表1
 - 属性currentMenu @1_1
- 步骤2：使用指定版本显示透视表
 - 属性pivotName @版本透视表V1
 - 属性version @1
- 步骤3：无效透视表ID显示 @access_denied
- 步骤4：带有标记的透视表显示
 - 属性pivotName @测试透视表2
 - 属性currentMenu @1_2
- 步骤5：设置新标记的内置透视表显示
 - 属性pivotName @内置透视表
 - 属性markSet @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$pivotTable = zenData('pivot');
$pivotTable->id->range('1-5');
$pivotTable->dimension->range('1');
$pivotTable->group->range('1-2');
$pivotTable->name->range('测试透视表1,测试透视表2,测试透视表3,内置透视表,版本透视表');
$pivotTable->sql->range('SELECT * FROM zt_user{5}');
$pivotTable->fields->range('{"id":{"name":"id","type":"input"},"account":{"name":"account","type":"input"}}{5}');
$pivotTable->settings->range('{"summary":"use","columns":[{"field":"account","title":"用户"}]}{5}');
$pivotTable->filters->range('[]{5}');
$pivotTable->stage->range('published{5}');
$pivotTable->builtin->range('0,0,0,1,0');
$pivotTable->deleted->range('0{5}');
$pivotTable->gen(5);

$pivotspecTable = zenData('pivotspec');
$pivotspecTable->pivot->range('5');
$pivotspecTable->version->range('1');
$pivotspecTable->name->range('版本透视表V1');
$pivotspecTable->sql->range('SELECT * FROM zt_user WHERE deleted="0"');
$pivotspecTable->fields->range('{"id":{"name":"id","type":"input"},"account":{"name":"account","type":"input"}}');
$pivotspecTable->settings->range('{"summary":"use","columns":[{"field":"account","title":"用户账号"}]}');
$pivotspecTable->filters->range('[]');
$pivotspecTable->gen(1);

$userTable = zenData('user');
$userTable->id->range('1-3');
$userTable->account->range('admin,user1,user2');
$userTable->realname->range('管理员,用户1,用户2');
$userTable->deleted->range('0{3}');
$userTable->gen(3);

$markTable = zenData('mark');
$markTable->id->range('1-2');
$markTable->objectType->range('pivot{2}');
$markTable->objectID->range('4,5');
$markTable->version->range('1,2');
$markTable->mark->range('view{2}');
$markTable->account->range('admin{2}');
$markTable->gen(2);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->showTest(1, 1)) && p('hasVersionMark,pivotName,currentMenu') && e('0,测试透视表1,1_1'); // 步骤1：正常透视表显示
r($pivotTest->showTest(1, 5, '', '1')) && p('pivotName,version') && e('版本透视表V1,1'); // 步骤2：使用指定版本显示透视表
r($pivotTest->showTest(1, 999)) && p() && e('access_denied'); // 步骤3：无效透视表ID显示
r($pivotTest->showTest(1, 2, 'view')) && p('pivotName,currentMenu') && e('测试透视表2,1_2'); // 步骤4：带有标记的透视表显示
r($pivotTest->showTest(1, 4, 'view')) && p('pivotName,markSet') && e('内置透视表,1'); // 步骤5：设置新标记的内置透视表显示