#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printPlanBlock();
timeout=0
cid=15268

- 步骤1：默认参数测试属性plansCount @1
- 步骤2：type为all类型测试属性plansCount @1
- 步骤3：count限制为5测试属性plansCount @1
- 步骤4：count限制为10测试属性plansCount @1
- 步骤5：type为noproduct测试属性plansCount @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（根据需要配置）
// 暂时注释掉zendata，直接测试逻辑

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

// 5. 强制要求：必须包含至少5个测试步骤
// 创建默认的block参数
$defaultBlock = new stdclass();
$defaultBlock->dashboard = 'my';
$defaultBlock->params = new stdclass();
$defaultBlock->params->type = '';
$defaultBlock->params->count = 15;

r($blockTest->printPlanBlockTest($defaultBlock)) && p('plansCount') && e('1'); // 步骤1：默认参数测试

// 测试type为all的情况
$allBlock = new stdclass();
$allBlock->dashboard = 'my';
$allBlock->params = new stdclass();
$allBlock->params->type = 'all';
$allBlock->params->count = 15;

r($blockTest->printPlanBlockTest($allBlock)) && p('plansCount') && e('1'); // 步骤2：type为all类型测试

// 测试count限制为5
$limit5Block = new stdclass();
$limit5Block->dashboard = 'my';
$limit5Block->params = new stdclass();
$limit5Block->params->type = '';
$limit5Block->params->count = 5;

r($blockTest->printPlanBlockTest($limit5Block)) && p('plansCount') && e('1'); // 步骤3：count限制为5测试

// 测试count限制为10
$limit10Block = new stdclass();
$limit10Block->dashboard = 'my';
$limit10Block->params = new stdclass();
$limit10Block->params->type = '';
$limit10Block->params->count = 10;

r($blockTest->printPlanBlockTest($limit10Block)) && p('plansCount') && e('1'); // 步骤4：count限制为10测试

// 测试type为noproduct的情况
$noproductBlock = new stdclass();
$noproductBlock->dashboard = 'my';
$noproductBlock->params = new stdclass();
$noproductBlock->params->type = 'noproduct';
$noproductBlock->params->count = 15;

r($blockTest->printPlanBlockTest($noproductBlock)) && p('plansCount') && e('1'); // 步骤5：type为noproduct测试