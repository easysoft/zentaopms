#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printProductStatisticBlock();
timeout=0
cid=0

- 步骤1:type为all类型测试属性productsCount @10
- 步骤2:count限制为5测试属性productsCount @5
- 步骤3:type为normal测试属性productsCount @8
- 步骤4:type为closed测试属性productsCount @2
- 步骤5:count限制为3测试属性productsCount @3

*/

// 1. 导入依赖(路径固定,不可修改)
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备(根据需要配置)
$product = zenData('product');
$product->status->range('normal{8},closed{2}');
$product->acl->range('open');
$product->gen(10);
zenData('user')->gen(5);
zenData('productplan')->gen(0);
zenData('project')->gen(0);
zenData('execution')->gen(0);
zenData('release')->gen(0);

// 3. 用户登录(选择合适角色)
su('admin');

// 4. 创建测试实例(变量名与模块名一致)
$blockTest = new blockZenTest();

// 5. 强制要求:必须包含至少5个测试步骤
// 步骤1:测试type为all类型
$allBlock = new stdclass();
$allBlock->dashboard = 'my';
$allBlock->params = new stdclass();
$allBlock->params->type = 'all';
$allBlock->params->count = 0;
r($blockTest->printProductStatisticBlockTest($allBlock)) && p('productsCount') && e('10'); // 步骤1:type为all类型测试

// 步骤2:测试count限制为5
$limit5Block = new stdclass();
$limit5Block->dashboard = 'my';
$limit5Block->params = new stdclass();
$limit5Block->params->type = 'all';
$limit5Block->params->count = 5;
r($blockTest->printProductStatisticBlockTest($limit5Block)) && p('productsCount') && e('5'); // 步骤2:count限制为5测试

// 步骤3:测试type为normal的情况
$normalBlock = new stdclass();
$normalBlock->dashboard = 'my';
$normalBlock->params = new stdclass();
$normalBlock->params->type = 'normal';
$normalBlock->params->count = 0;
r($blockTest->printProductStatisticBlockTest($normalBlock)) && p('productsCount') && e('8'); // 步骤3:type为normal测试

// 步骤4:测试type为closed的情况
$closedBlock = new stdclass();
$closedBlock->dashboard = 'my';
$closedBlock->params = new stdclass();
$closedBlock->params->type = 'closed';
$closedBlock->params->count = 0;
r($blockTest->printProductStatisticBlockTest($closedBlock)) && p('productsCount') && e('2'); // 步骤4:type为closed测试

// 步骤5:测试count限制为3
$limit3Block = new stdclass();
$limit3Block->dashboard = 'my';
$limit3Block->params = new stdclass();
$limit3Block->params->type = 'normal';
$limit3Block->params->count = 3;
r($blockTest->printProductStatisticBlockTest($limit3Block)) && p('productsCount') && e('3'); // 步骤5:count限制为3测试