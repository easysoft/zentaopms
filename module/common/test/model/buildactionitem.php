#!/usr/bin/env php
<?php

/**

title=测试 commonModel::buildActionItem();
timeout=0
cid=15645

- 步骤1：测试有权限模块正常构建链接属性url @product-index-
- 步骤2：测试带属性参数的链接构建属性class @btn
- 步骤3：测试带title属性的链接构建属性title @查看
- 步骤4：测试带参数的URL构建属性url @product-view-id=1
- 步骤5：测试空模块名的异常处理 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$commonTest = new commonModelTest();

// 4. 必须包含至少5个测试步骤
r($commonTest->buildActionItemTest('product', 'index', '')) && p('url') && e('product-index-'); // 步骤1：测试有权限模块正常构建链接
r($commonTest->buildActionItemTest('product', 'index', '', null, array('class' => 'btn'))) && p('class') && e('btn'); // 步骤2：测试带属性参数的链接构建
r($commonTest->buildActionItemTest('product', 'index', '', null, array('title' => '查看'))) && p('title') && e('查看'); // 步骤3：测试带title属性的链接构建
r($commonTest->buildActionItemTest('product', 'view', 'id=1')) && p('url') && e('product-view-id=1'); // 步骤4：测试带参数的URL构建
r($commonTest->buildActionItemTest('', 'index', '')) && p() && e('0'); // 步骤5：测试空模块名的异常处理