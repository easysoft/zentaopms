#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::checkDisplayCards();
timeout=0
cid=16878

- 执行kanbanTest模块的checkDisplayCardsTest方法，参数是10  @1
- 执行kanbanTest模块的checkDisplayCardsTest方法，参数是3  @1
- 执行kanbanTest模块的checkDisplayCardsTest方法，参数是100  @1
- 执行kanbanTest模块的checkDisplayCardsTest方法，参数是2  @0
- 执行kanbanTest模块的checkDisplayCardsTest方法，参数是101  @0
- 执行kanbanTest模块的checkDisplayCardsTest方法  @0
- 执行kanbanTest模块的checkDisplayCardsTest方法，参数是-5  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$kanbanTest = new kanbanModelTest();

r($kanbanTest->checkDisplayCardsTest(10)) && p() && e('1');
r($kanbanTest->checkDisplayCardsTest(3)) && p() && e('1');
r($kanbanTest->checkDisplayCardsTest(100)) && p() && e('1');
r($kanbanTest->checkDisplayCardsTest(2)) && p() && e('0');
r($kanbanTest->checkDisplayCardsTest(101)) && p() && e('0');
r($kanbanTest->checkDisplayCardsTest(0)) && p() && e('0');
r($kanbanTest->checkDisplayCardsTest(-5)) && p() && e('0');