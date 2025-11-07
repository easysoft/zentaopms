#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printRiskBlock();
timeout=0
cid=0

- 步骤1:测试默认参数情况 @1
- 步骤2:测试type为all,count为15 @1
- 步骤3:测试type为active,count为5 @1
- 步骤4:测试type为closed,count为10 @1
- 步骤5:测试type为resolved,count为20 @1
- 步骤6:测试参数结构不正确 @0

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. 用户登录(选择合适角色)
su('admin');

// 3. 创建测试实例(变量名与模块名一致)
$blockTest = new blockTest();

// 4. 创建测试用的block对象
$block1 = new stdClass();
$block1->params = new stdClass();
$block1->params->type = 'all';
$block1->params->count = 15;
$block1->params->orderBy = 'id_desc';

$block2 = new stdClass();
$block2->params = new stdClass();
$block2->params->type = 'active';
$block2->params->count = 5;
$block2->params->orderBy = 'id_desc';

$block3 = new stdClass();
$block3->params = new stdClass();
$block3->params->type = 'closed';
$block3->params->count = 10;
$block3->params->orderBy = 'id_asc';

$block4 = new stdClass();
$block4->params = new stdClass();
$block4->params->type = 'resolved';
$block4->params->count = 20;
$block4->params->orderBy = 'name_desc';

$block5 = new stdClass();
// 故意不设置params,测试参数不正确的情况

// 5. 强制要求:必须包含至少5个测试步骤
r($blockTest->printRiskBlockTest(null)) && p() && e('1'); // 步骤1:测试默认参数情况
r($blockTest->printRiskBlockTest($block1)) && p() && e('1'); // 步骤2:测试type为all,count为15
r($blockTest->printRiskBlockTest($block2)) && p() && e('1'); // 步骤3:测试type为active,count为5
r($blockTest->printRiskBlockTest($block3)) && p() && e('1'); // 步骤4:测试type为closed,count为10
r($blockTest->printRiskBlockTest($block4)) && p() && e('1'); // 步骤5:测试type为resolved,count为20
r($blockTest->printRiskBlockTest($block5)) && p() && e('0'); // 步骤6:测试参数结构不正确