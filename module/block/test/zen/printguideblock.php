#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printGuideBlock();
timeout=0
cid=15264

- 步骤1:测试基本区块对象,验证blockID正确设置属性blockID @1
- 步骤2:测试programLink链接正确设置属性programLink @program-browse
- 步骤3:测试productLink链接正确设置属性productLink @product-all
- 步骤4:测试projectLink链接正确设置属性projectLink @project-browse
- 步骤5:测试executionLink链接正确设置属性executionLink @execution-task

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备(根据需要配置)
zendata('user')->loadYaml('user', false, 2)->gen(10);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$blockTest = new blockZenTest();

// 5. 准备测试数据
$block1 = new stdClass();
$block1->id = 1;

$block2 = new stdClass();
$block2->id = 2;

$block3 = new stdClass();
$block3->id = 3;

$block4 = new stdClass();
$block4->id = 4;

$block5 = new stdClass();
$block5->id = 5;

// 6. 强制要求:必须包含至少5个测试步骤
r($blockTest->printGuideBlockTest($block1)) && p('blockID') && e('1'); // 步骤1:测试基本区块对象,验证blockID正确设置
r($blockTest->printGuideBlockTest($block2)) && p('programLink') && e('program-browse'); // 步骤2:测试programLink链接正确设置
r($blockTest->printGuideBlockTest($block3)) && p('productLink') && e('product-all'); // 步骤3:测试productLink链接正确设置
r($blockTest->printGuideBlockTest($block4)) && p('projectLink') && e('project-browse'); // 步骤4:测试projectLink链接正确设置
r($blockTest->printGuideBlockTest($block5)) && p('executionLink') && e('execution-task'); // 步骤5:测试executionLink链接正确设置