#!/usr/bin/env php
<?php

/**

title=测试 productZen::getKanbanList();
timeout=0
cid=0

- 执行productTest模块的getKanbanListTest方法，参数是'my'  @0
- 执行productTest模块的getKanbanListTest方法，参数是'all'  @2
- 执行productTest模块的getKanbanListTest方法，参数是'closed'  @2
- 执行productTest模块的getKanbanListTest方法，参数是'other'  @2
- 执行productTest模块的getKanbanListTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 简化测试，不生成测试数据

su('admin');

$productTest = new productTest();

r($productTest->getKanbanListTest('my')) && p() && e('0');
r($productTest->getKanbanListTest('all')) && p() && e('2');
r($productTest->getKanbanListTest('closed')) && p() && e('2');
r($productTest->getKanbanListTest('other')) && p() && e('2');
r($productTest->getKanbanListTest()) && p() && e('0');