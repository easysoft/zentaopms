#!/usr/bin/env php
<?php

/**

title=测试 repoZen::getLinkBugs();
timeout=0
cid=0

- 步骤1：搜索模式获取active bug @2
- 步骤2：单产品模式 @1
- 步骤3：多产品模式 @2
- 步骤4：空产品列表 @0
- 步骤5：状态文本验证第0条的statusText属性 @激活

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repozen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('repo');
$table->id->range('1-10');
$table->name->range('测试仓库{1-10}');
$table->SCM->range('Git,Subversion');
$table->gen(5);

$bugTable = zenData('bug');
$bugTable->id->range('1-10');
$bugTable->title->range('测试Bug{1-10}');
$bugTable->product->range('1-3');
$bugTable->status->range('active{7},closed{3}');
$bugTable->gen(10);

$productTable = zenData('product');
$productTable->id->range('1-3');
$productTable->name->range('产品{1-3}');
$productTable->type->range('normal');
$productTable->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$repoTest = new repoZenTest();

// 创建分页器对象
$pager = new stdClass();
$pager->recPerPage = 10;
$pager->pageID = 1;

// 创建产品数组
$product1 = new stdClass();
$product1->id = 1;
$product1->name = '产品1';

$product2 = new stdClass();
$product2->id = 2;
$product2->name = '产品2';

$products = array(1 => $product1, 2 => $product2);

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r(count($repoTest->getLinkBugsTest(1, 'abc123', 'bySearch', $products, 'id_desc', $pager, 1))) && p() && e(2); // 步骤1：搜索模式获取active bug
r(count($repoTest->getLinkBugsTest(1, 'abc123', 'normal', array(1 => $product1), 'id_desc', $pager, 0))) && p() && e(1); // 步骤2：单产品模式
r(count($repoTest->getLinkBugsTest(1, 'abc123', 'normal', $products, 'id_desc', $pager, 0))) && p() && e(2); // 步骤3：多产品模式
r(count($repoTest->getLinkBugsTest(1, 'abc123', 'normal', array(), 'id_desc', $pager, 0))) && p() && e(0); // 步骤4：空产品列表
r($repoTest->getLinkBugsTest(1, 'abc123', 'bySearch', $products, 'id_desc', $pager, 1)) && p('0:statusText') && e('激活'); // 步骤5：状态文本验证