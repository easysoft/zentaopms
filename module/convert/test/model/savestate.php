#!/usr/bin/env php
<?php

/**

title=测试 convertModel::saveState();
timeout=0
cid=15795

- 执行convertTest模块的saveStateTest方法  @array
- 执行convertTest模块的saveStateTest方法  @array
- 执行convertTest模块的saveStateTest方法  @array
- 执行convertTest模块的saveStateTest方法  @array
- 执行convertTest模块的saveStateTest方法  @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('action')->gen(10);
zenData('user')->gen(5);
zenData('product')->gen(3);

su('admin');

$convertTest = new convertModelTest();

r($convertTest->saveStateTest()) && p() && e('array');
r($convertTest->saveStateTest()) && p() && e('array');
r($convertTest->saveStateTest()) && p() && e('array');
r($convertTest->saveStateTest()) && p() && e('array');
r($convertTest->saveStateTest()) && p() && e('array');