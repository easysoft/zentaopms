#!/usr/bin/env php
<?php

/**

title=测试 bugModel::getDatatableModules();
timeout=0
cid=15380

- 测试普通产品(无分支)获取模块列表，查看模块数量 @7
- 测试普通产品(无分支)获取模块列表，检查根节点 @/
- 测试普通产品(无分支)获取模块列表，检查模块2属性2 @/这是一个模块2
- 测试分支产品获取模块列表，查看模块总数量 @1
- 测试不存在的产品ID，返回包含根节点的数组 @1
- 测试产品ID为0，返回包含根节点的数组 @1
- 测试不存在的产品ID，验证只有根节点 @/

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

su('admin');

zendata('product')->loadYaml('product_getdatatablemodules', false, 2)->gen(3);
zendata('branch')->loadYaml('branch_getdatatablemodules', false, 2)->gen(4);
zendata('module')->loadYaml('module_getdatatablemodules', false, 2)->gen(10);

$bugTest = new bugTest();

r(count($bugTest->getDatatableModulesTest(1))) && p('') && e('7'); // 测试普通产品(无分支)获取模块列表，查看模块数量
r($bugTest->getDatatableModulesTest(1)) && p('0') && e('/'); // 测试普通产品(无分支)获取模块列表，检查根节点
r($bugTest->getDatatableModulesTest(1)) && p('2') && e('/这是一个模块2'); // 测试普通产品(无分支)获取模块列表，检查模块2
r(count($bugTest->getDatatableModulesTest(2))) && p('') && e('1'); // 测试分支产品获取模块列表，查看模块总数量
r(count($bugTest->getDatatableModulesTest(999))) && p('') && e('1'); // 测试不存在的产品ID，返回包含根节点的数组
r(count($bugTest->getDatatableModulesTest(0))) && p('') && e('1'); // 测试产品ID为0，返回包含根节点的数组
r($bugTest->getDatatableModulesTest(999)) && p('0') && e('/'); // 测试不存在的产品ID，验证只有根节点