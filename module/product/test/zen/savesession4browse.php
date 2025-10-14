#!/usr/bin/env php
<?php

/**

title=测试 productZen::saveSession4Browse();
timeout=0
cid=0

- 步骤1：正常情况
 - 属性productList @true
 - 属性storyList @true
- 步骤2：空产品对象属性currentProductType @empty
- 步骤3：bymodule类型属性storyBrowseType @empty
- 步骤4：bybranch类型属性storyBrowseType @empty
- 步骤5：项目标签属性storyList @true

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-10');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->type->range('normal{3},branch{2}');
$table->status->range('normal');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->saveSession4BrowseTest((object)array('id' => 1, 'type' => 'normal'), 'unclosed')) && p('productList,storyList') && e('true,true'); // 步骤1：正常情况
r($productTest->saveSession4BrowseTest(null, 'closed')) && p('currentProductType') && e('empty'); // 步骤2：空产品对象
r($productTest->saveSession4BrowseTest((object)array('id' => 2, 'type' => 'branch'), 'bymodule')) && p('storyBrowseType') && e('empty'); // 步骤3：bymodule类型
r($productTest->saveSession4BrowseTest((object)array('id' => 3, 'type' => 'normal'), 'bybranch')) && p('storyBrowseType') && e('empty'); // 步骤4：bybranch类型
r($productTest->saveSession4BrowseTest((object)array('id' => 4, 'type' => 'normal'), 'active', 'project')) && p('storyList') && e('true'); // 步骤5：项目标签