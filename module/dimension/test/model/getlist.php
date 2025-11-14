#!/usr/bin/env php
<?php

/**

title=测试 dimensionModel::getList();
timeout=0
cid=16036

- 执行dimensionTester模块的getListTest方法 第1条的name属性 @宏观管理维度
- 执行dimensionTester模块的getListTest方法 第1条的code属性 @macro
- 执行dimensionTester模块的getListTest方法 第2条的name属性 @效能管理维度
- 执行dimensionTester模块的getListTest方法 第3条的code属性 @quality
- 执行dimensionTester模块的getListTestWithCount方法  @8

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dimension.unittest.class.php';

su('admin');

$dimensionTester = new dimensionTest();

r($dimensionTester->getListTest()) && p('1:name') && e('宏观管理维度');
r($dimensionTester->getListTest()) && p('1:code') && e('macro');
r($dimensionTester->getListTest()) && p('2:name') && e('效能管理维度');
r($dimensionTester->getListTest()) && p('3:code') && e('quality');
r($dimensionTester->getListTestWithCount()) && p() && e('8');