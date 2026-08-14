#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getByID();
timeout=0
cid=18238

- 步骤1：查询不存在的screen ID @0
- 步骤2：查询存在的screen ID且不加载chartData
 - 属性id @1
 - 属性name @Screen1
- 步骤3：查询存在的screen ID并加载chartData
 - 属性id @2
 - 属性name @Screen2
- 步骤4：查询ID为0的边界值 @0
- 步骤5：查询负数ID @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$screen = zenData('screen');
$screen->id->range('1-2');
$screen->name->range('Screen1,Screen2');
$screen->scheme->range('{"componentList":[]}');
$screen->builtin->range('0');
$screen->deleted->range('0');
$screen->gen(2);

su('admin');
$screenTest = new screenModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($screenTest->getByIDTest(999)) && p() && e('0'); // 步骤1：查询不存在的screen ID
r($screenTest->getByIDTest(1, 0, 0, 0, '', false)) && p('id,name') && e('1,Screen1'); // 步骤2：查询存在的screen ID且不加载chartData
r($screenTest->getByIDTest(2, 0, 0, 0, '', true)) && p('id,name') && e('2,Screen2'); // 步骤3：查询存在的screen ID并加载chartData
r($screenTest->getByIDTest(0)) && p() && e('0'); // 步骤4：查询ID为0的边界值
r($screenTest->getByIDTest(-1)) && p() && e('0'); // 步骤5：查询负数ID
