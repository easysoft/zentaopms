#!/usr/bin/env php
<?php

/**

title=测试 groupModel::getPrivsByParents();
timeout=0
cid=16712

- 步骤1：获取 my 子集的权限属性my-index @地盘仪表盘
- 步骤2：获取 todo 子集的权限属性todo-create @添加待办
- 步骤3：获取 product 子集的权限属性product-view @产品概况
- 步骤4：获取 my 子集指定包的权限属性my-index @地盘仪表盘
- 步骤5：测试不存在的子集 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$groupTest = new groupModelTest();

r($groupTest->getPrivsByParentsTest('my', '')) && p('my-index') && e('地盘仪表盘'); // 步骤1：获取 my 子集的权限
r($groupTest->getPrivsByParentsTest('todo', '')) && p('todo-create') && e('添加待办'); // 步骤2：获取 todo 子集的权限
r($groupTest->getPrivsByParentsTest('product', '')) && p('product-view') && e('产品概况'); // 步骤3：获取 product 子集的权限
r($groupTest->getPrivsByParentsTest('my', '|my|')) && p('my-index') && e('地盘仪表盘'); // 步骤4：获取 my 子集指定包的权限
r(count($groupTest->getPrivsByParentsTest('nonexistent', ''))) && p() && e('0'); // 步骤5：测试不存在的子集