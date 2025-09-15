#!/usr/bin/env php
<?php

/**

title=测试 productZen::setMenu4Browse();
timeout=0
cid=0

- 步骤1：正常情况下非项目tab设置产品菜单 @success
- 步骤2：项目tab下设置项目菜单 @success
- 步骤3：空项目ID和产品ID的边界值测试 @success
- 步骤4：不同需求类型参数测试 @success
- 步骤5：不同分支参数测试 @success

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->status->range('normal');
$table->type->range('normal');
$table->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-3');
$projectTable->name->range('项目1,项目2,项目3');
$projectTable->status->range('doing');
$projectTable->type->range('project');
$projectTable->gen(3);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$productTest = new productTest();

// 5. 执行测试步骤（至少5个）
r($productTest->setMenu4BrowseTest(0, 1, 'all', 'story')) && p() && e('success'); // 步骤1：正常情况下非项目tab设置产品菜单
$productTest->objectZen->getProperty('app')->setValue(new productZen(), (object)array('tab' => 'project'));
r($productTest->setMenu4BrowseTest(1, 1, 'all', 'story')) && p() && e('success'); // 步骤2：项目tab下设置项目菜单
r($productTest->setMenu4BrowseTest(0, 0, '', '')) && p() && e('success'); // 步骤3：空项目ID和产品ID的边界值测试
r($productTest->setMenu4BrowseTest(0, 1, 'all', 'requirement')) && p() && e('success'); // 步骤4：不同需求类型参数测试
r($productTest->setMenu4BrowseTest(0, 1, 'main', 'epic')) && p() && e('success'); // 步骤5：不同分支参数测试