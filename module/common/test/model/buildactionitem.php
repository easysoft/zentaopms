#!/usr/bin/env php
<?php

/**

title=测试 commonModel::buildActionItem();
timeout=0
cid=0

- 步骤1：正常情况有权限模块属性url @1
- 步骤2：带属性参数属性class @btn
- 步骤3：带title属性属性title @查看
- 步骤4：带参数的URL属性url @1
- 步骤5：空模块参数 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/common.unittest.class.php';

// 2. zendata数据准备 - 使用已存在的数据
// （直接使用数据库中已有的基础数据，无需重新生成）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$commonTest = new commonTest();

// 5. 必须包含至少5个测试步骤
r($commonTest->buildActionItemTest('product', 'index', '')) && p('url') && e('1'); // 步骤1：正常情况有权限模块
r($commonTest->buildActionItemTest('product', 'index', '', null, array('class' => 'btn'))) && p('class') && e('btn'); // 步骤2：带属性参数
r($commonTest->buildActionItemTest('product', 'index', '', null, array('title' => '查看'))) && p('title') && e('查看'); // 步骤3：带title属性
r($commonTest->buildActionItemTest('product', 'view', 'id=1')) && p('url') && e('1'); // 步骤4：带参数的URL
r($commonTest->buildActionItemTest('', 'view', 'id=1')) && p() && e('0'); // 步骤5：空模块参数