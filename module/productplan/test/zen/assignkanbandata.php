#!/usr/bin/env php
<?php

/**

title=测试 productplanZen::assignKanbanData();
timeout=0
cid=0

- 执行productplanZenTest模块的assignKanbanDataTest方法，参数是$normalProduct, '0', 'begin_asc' 属性type @normal
- 执行productplanZenTest模块的assignKanbanDataTest方法，参数是$branchProduct, '0', 'begin_asc' 属性type @branch
- 执行productplanZenTest模块的assignKanbanDataTest方法，参数是$normalProduct, '0', 'invalid_order' 属性type @normal
- 执行productplanZenTest模块的assignKanbanDataTest方法，参数是$normalProduct, '', 'begin_asc' 属性type @normal
- 执行productplanZenTest模块的assignKanbanDataTest方法，参数是$branchProduct, '1', 'begin_asc' 属性type @branch

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/productplanzen.unittest.class.php';

su('admin');

$productplanZenTest = new productplanZenTest();

// 准备测试产品对象
$normalProduct = new stdClass();
$normalProduct->id = 1;
$normalProduct->type = 'normal';

$branchProduct = new stdClass();
$branchProduct->id = 2;
$branchProduct->type = 'branch';

r($productplanZenTest->assignKanbanDataTest($normalProduct, '0', 'begin_asc')) && p('type') && e('normal');
r($productplanZenTest->assignKanbanDataTest($branchProduct, '0', 'begin_asc')) && p('type') && e('branch');
r($productplanZenTest->assignKanbanDataTest($normalProduct, '0', 'invalid_order')) && p('type') && e('normal');
r($productplanZenTest->assignKanbanDataTest($normalProduct, '', 'begin_asc')) && p('type') && e('normal');
r($productplanZenTest->assignKanbanDataTest($branchProduct, '1', 'begin_asc')) && p('type') && e('branch');