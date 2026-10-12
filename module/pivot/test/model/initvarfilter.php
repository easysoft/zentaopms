#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::initVarFilter();
timeout=0
cid=17410

- 步骤1：空过滤器和空SQL @0
- 步骤2：基本变量替换 @SELECT * FROM zt_bug WHERE status = 'active'
- 步骤3：数组默认值处理 @SELECT * FROM zt_bug WHERE type IN ('bug', 'feature', 'improvement')

- 步骤4：空过滤器时保持原SQL不变 @SELECT * FROM zt_bug WHERE status = $status AND type = $type
- 步骤5：多过滤器组合及变量清理 @SELECT * FROM zt_bug WHERE status = 'active' AND product = '1' AND priority = ''
- 步骤6：from字段为空时跳过该过滤器但清理变量 @SELECT * FROM zt_bug WHERE status = ''
- 步骤7：注入载荷被转义为字面量 @SELECT * FROM zt_bug WHERE status = 'active\' or sleep(3)-- '
- 步骤8：嵌套变量不会逃逸出字面量 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotModelTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($pivotTest->initVarFilterTest(array(), '')) && p() && e('0'); // 步骤1：空过滤器和空SQL
r($pivotTest->initVarFilterTest(array(array('field' => 'status', 'from' => 'bug', 'default' => 'active')), 'SELECT * FROM zt_bug WHERE status = $status')) && p() && e("SELECT * FROM zt_bug WHERE status = 'active'"); // 步骤2：基本变量替换
r($pivotTest->initVarFilterTest(array(array('field' => 'types', 'from' => 'bug', 'default' => array('bug', 'feature', 'improvement'))), 'SELECT * FROM zt_bug WHERE type IN ($types)')) && p() && e("SELECT * FROM zt_bug WHERE type IN ('bug', 'feature', 'improvement')"); // 步骤3：数组默认值处理
r($pivotTest->initVarFilterTest(array(), 'SELECT * FROM zt_bug WHERE status = $status AND type = $type')) && p() && e('SELECT * FROM zt_bug WHERE status = $status AND type = $type'); // 步骤4：空过滤器时保持原SQL不变
r($pivotTest->initVarFilterTest(array(array('field' => 'status', 'from' => 'bug', 'default' => 'active'), array('field' => 'product', 'from' => 'product', 'default' => '1')), 'SELECT * FROM zt_bug WHERE status = $status AND product = $product AND priority = $priority')) && p() && e("SELECT * FROM zt_bug WHERE status = 'active' AND product = '1' AND priority = ''"); // 步骤5：多过滤器组合及变量清理
r($pivotTest->initVarFilterTest(array(array('field' => 'status', 'from' => '', 'default' => 'active')), 'SELECT * FROM zt_bug WHERE status = $status')) && p() && e("SELECT * FROM zt_bug WHERE status = ''"); // 步骤6：from字段为空时跳过该过滤器但清理变量

/* 步骤7：注入载荷被 quote 转义，不能闭合引号。The injection payload is quoted. */
r($pivotTest->initVarFilterTest(array(array('field' => 'status', 'from' => 'bug', 'default' => "active' or sleep(3)-- ")), 'SELECT * FROM zt_bug WHERE status = $status')) && p() && e("SELECT * FROM zt_bug WHERE status = 'active\\' or sleep(3)-- '"); // 步骤7：注入载荷被转义为字面量

/* 步骤8：值中携带的嵌套变量不会在替换结果中再次拼进SQL。A nested variable inside the replaced text is not replaced again. */
$nestedSQL     = "select t2.status from zt_product t2 where (case when \$productStatus='' then 1=1 else t2.status=\$productStatus end) and (case when \$product='0' then 1=1 else t2.id=\$product end)";
$nestedFilters = array(
    array('field' => 'productStatus', 'from' => 'query', 'default' => 'x$productx'),
    array('field' => 'product',       'from' => 'query', 'default' => ' or sleep(3) or ')
);
$nestedResult = $pivotTest->initVarFilterTest($nestedFilters, $nestedSQL);
r(strpos($nestedResult, "'x' or sleep(3) or 'x'") === false) && p('') && e('1'); // 步骤8：嵌套变量不会逃逸出字面量
