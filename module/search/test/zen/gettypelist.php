#!/usr/bin/env php
<?php

/**

title=测试 searchZen::getTypeList();
cid=0

- 测试步骤1：正常获取类型列表 >> 期望返回包含all和其他类型的数组
- 测试步骤2：检查all类型的标题 >> 期望返回'全部'
- 测试步骤3：验证返回数组长度大于1 >> 期望包含多个类型
- 测试步骤4：验证包含task类型 >> 期望包含任务类型
- 测试步骤5：验证包含bug类型 >> 期望包含Bug类型

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

zenData('searchindex')->loadYaml('searchindex_gettypelist', false, 2)->gen(50);

su('admin');

$searchTest = new searchTest();

r($searchTest->getTypeListTest()) && p('all') && e('全部'); // 步骤1：检查all类型的标题
r($searchTest->getTypeListTest()) && p('task') && e('任务'); // 步骤2：检查task类型的标题
r($searchTest->getTypeListTest()) && p('bug') && e('Bug'); // 步骤3：检查bug类型的标题
r($searchTest->getTypeListTest()) && p('story') && e('需求'); // 步骤4：检查story类型的标题
r($searchTest->getTypeListTest()) && p('product') && e('产品'); // 步骤5：检查product类型的标题