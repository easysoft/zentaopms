#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::getCellByCard();
timeout=0
cid=16909

- 执行kanbanTest模块的getCellByCardTest方法，参数是1, 1 
 - 属性lane @1
 - 属性column @1
- 执行kanbanTest模块的getCellByCardTest方法，参数是3, 1 
 - 属性lane @1
 - 属性column @2
- 执行kanbanTest模块的getCellByCardTest方法，参数是999, 1  @0
- 执行kanbanTest模块的getCellByCardTest方法，参数是1, 999  @0
- 执行kanbanTest模块的getCellByCardTest方法，参数是0, 1  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

$cell = zenData('kanbancell');
$cell->loadYaml('kanbancell_getcellbycard', false, 2);
$cell->gen(10);

su('admin');

$kanbanTest = new kanbanModelTest();

r($kanbanTest->getCellByCardTest(1, 1)) && p('lane,column') && e('1,1');
r($kanbanTest->getCellByCardTest(3, 1)) && p('lane,column') && e('1,2');
r($kanbanTest->getCellByCardTest(999, 1)) && p() && e('0');
r($kanbanTest->getCellByCardTest(1, 999)) && p() && e('0');
r($kanbanTest->getCellByCardTest(0, 1)) && p() && e('0');