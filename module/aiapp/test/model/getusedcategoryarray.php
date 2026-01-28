#!/usr/bin/env php
<?php

/**

title=测试 aiappModel::getUsedCategoryArray();
timeout=0
cid=15087

- 执行aiappTest模块的getUsedCategoryArrayTest方法  @Array
- 执行aiappTest模块的getUsedCategoryArrayTest方法  @(
- 执行aiappTest模块的getUsedCategoryArrayTest方法  @[work] => 工作
- 执行aiappTest模块的getUsedCategoryArrayTest方法  @)
- 执行aiappTest模块的getUsedCategoryArrayTest方法  @Array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$aiappTest = new aiappModelTest();

r($aiappTest->getUsedCategoryArrayTest()) && p() && e('Array');
r($aiappTest->getUsedCategoryArrayTest()) && p() && e('(');
r($aiappTest->getUsedCategoryArrayTest()) && p() && e('[work] => 工作');
r($aiappTest->getUsedCategoryArrayTest()) && p() && e(')');
r($aiappTest->getUsedCategoryArrayTest()) && p() && e('Array');