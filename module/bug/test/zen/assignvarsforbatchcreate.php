#!/usr/bin/env php
<?php

/**

title=测试 bugZen::assignVarsForBatchCreate();
timeout=0
cid=0

- 步骤1：正常产品和项目情况
 - 属性hasCustomFields @1
 - 属性productType @normal
- 步骤2：分支产品类型情况
 - 属性hasBranch @1
 - 属性productType @branch
- 步骤3：看板项目模式情况
 - 属性hasExecution @1
 - 属性projectModel @kanban
- 步骤4：包含图片文件情况属性hasTitles @1
- 步骤5：多个图片文件情况属性hasTitles @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->type->range('normal{2},branch{2},platform{1}');
$productTable->status->range('normal');
$productTable->gen(5);

$projectTable = zenData('project');
$projectTable->id->range('1-5');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5');
$projectTable->model->range('scrum{2},waterfall{2},kanban{1}');
$projectTable->type->range('project');
$projectTable->status->range('wait');
$projectTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->assignVarsForBatchCreateTest((object)array('id' => 1, 'type' => 'normal'), (object)array('id' => 1, 'model' => 'scrum'), array())) && p('hasCustomFields,productType') && e('1,normal'); // 步骤1：正常产品和项目情况
r($bugTest->assignVarsForBatchCreateTest((object)array('id' => 3, 'type' => 'branch'), (object)array('id' => 2, 'model' => 'waterfall'), array())) && p('hasBranch,productType') && e('1,branch'); // 步骤2：分支产品类型情况
r($bugTest->assignVarsForBatchCreateTest((object)array('id' => 1, 'type' => 'normal'), (object)array('id' => 5, 'model' => 'kanban'), array())) && p('hasExecution,projectModel') && e('1,kanban'); // 步骤3：看板项目模式情况
r($bugTest->assignVarsForBatchCreateTest((object)array('id' => 1, 'type' => 'normal'), (object)array('id' => 1, 'model' => 'scrum'), array('test.png' => array('title' => '测试图片')))) && p('hasTitles') && e('1'); // 步骤4：包含图片文件情况
r($bugTest->assignVarsForBatchCreateTest((object)array('id' => 1, 'type' => 'normal'), (object)array('id' => 1, 'model' => 'scrum'), array('test1.png' => array('title' => '图片1'), 'test2.jpg' => array('title' => '图片2')))) && p('hasTitles') && e('1'); // 步骤5：多个图片文件情况