#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printTaskBlock();
timeout=0
cid=15305

- 步骤1:测试正常情况下传入合法type参数和count参数
 - 属性type @assignedTo
 - 属性count @10
- 步骤2:测试type参数包含特殊字符时验证失败属性type @invalid-type
- 步骤3:测试count为0时的处理属性count @0
- 步骤4:测试不同的type类型参数finishedBy
 - 属性type @finishedBy
 - 属性count @8
- 步骤5:测试orderBy参数的传递
 - 属性orderBy @id_asc
 - 属性type @assignedTo
- 步骤6:测试较大的count值 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/block.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$blockTest = new blockTest();

// 4. 准备测试数据
$block1 = new stdclass();
$block1->params = new stdclass();
$block1->params->type = 'assignedTo';
$block1->params->count = 10;
$block1->params->orderBy = 'id_desc';
$block1->dashboard = 'my';

$block2 = new stdclass();
$block2->params = new stdclass();
$block2->params->type = 'invalid-type';
$block2->params->count = 10;
$block2->dashboard = 'my';

$block3 = new stdclass();
$block3->params = new stdclass();
$block3->params->type = 'assignedTo';
$block3->params->count = 0;
$block3->params->orderBy = 'id_desc';
$block3->dashboard = 'my';

$block4 = new stdclass();
$block4->params = new stdclass();
$block4->params->type = 'finishedBy';
$block4->params->count = 8;
$block4->params->orderBy = 'id_desc';
$block4->dashboard = 'my';

$block5 = new stdclass();
$block5->params = new stdclass();
$block5->params->type = 'assignedTo';
$block5->params->count = 5;
$block5->params->orderBy = 'id_asc';
$block5->dashboard = 'my';

$block6 = new stdclass();
$block6->params = new stdclass();
$block6->params->type = 'assignedTo';
$block6->params->count = 100;
$block6->params->orderBy = 'id_desc';
$block6->dashboard = 'my';

// 5. 强制要求：必须包含至少5个测试步骤
r($blockTest->printTaskBlockTest($block1)) && p('type,count') && e('assignedTo,10'); // 步骤1:测试正常情况下传入合法type参数和count参数
r($blockTest->printTaskBlockTest($block2)) && p('type') && e('invalid-type'); // 步骤2:测试type参数包含特殊字符时验证失败
r($blockTest->printTaskBlockTest($block3)) && p('count') && e('0'); // 步骤3:测试count为0时的处理
r($blockTest->printTaskBlockTest($block4)) && p('type,count') && e('finishedBy,8'); // 步骤4:测试不同的type类型参数finishedBy
r($blockTest->printTaskBlockTest($block5)) && p('orderBy,type') && e('id_asc,assignedTo'); // 步骤5:测试orderBy参数的传递
r(count($blockTest->printTaskBlockTest($block6)->tasks)) && p() && e('5'); // 步骤6:测试较大的count值