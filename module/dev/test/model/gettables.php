#!/usr/bin/env php
<?php

/**

title=测试 devModel::getTables();
timeout=0
cid=16011

- 测试步骤1：正常获取所有数据表并验证my分组下todo表第my条的todo属性 @zt_todo
- 测试步骤2：验证product分组下product表的完整名称第product条的product属性 @zt_product
- 测试步骤3：验证other分组下acl表的完整名称第other条的acl属性 @zt_acl
- 测试步骤4：验证返回结果的数据结构完整性属性isArray @1
- 测试步骤5：验证flow表过滤机制正常工作属性hasFlowTable @0
- 测试步骤6：验证表分组功能正常工作 @array
- 测试步骤7：验证空前缀情况下的表获取 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$devTest = new devModelTest();

r($devTest->getTablesTest()) && p('my:todo') && e('zt_todo'); // 测试步骤1：正常获取所有数据表并验证my分组下todo表
r($devTest->getTablesTest()) && p('product:product') && e('zt_product'); // 测试步骤2：验证product分组下product表的完整名称
r($devTest->getTablesTest()) && p('other:acl') && e('zt_acl'); // 测试步骤3：验证other分组下acl表的完整名称
r($devTest->getTablesStructureTest()) && p('isArray') && e('1'); // 测试步骤4：验证返回结果的数据结构完整性
r($devTest->getTablesFlowFilterTest()) && p('hasFlowTable') && e('0'); // 测试步骤5：验证flow表过滤机制正常工作
r($devTest->getTablesGroupTest()) && p() && e('array'); // 测试步骤6：验证表分组功能正常工作
r($devTest->getTablesEmptyPrefixTest()) && p() && e('array'); // 测试步骤7：验证空前缀情况下的表获取