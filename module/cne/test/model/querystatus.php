#!/usr/bin/env php
<?php

/**

title=测试 cneModel::queryStatus();
timeout=0
cid=15626

- 执行cneTest模块的queryStatusTest方法，参数是1 属性code @0
- 执行cneTest模块的queryStatusTest方法，参数是999  @0
- 执行cneTest模块的queryStatusTest方法  @0
- 执行cneTest模块的queryStatusTest方法，参数是-1  @0
- 执行cneTest模块的queryStatusTest方法，参数是100  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

r($cneTest->queryStatusTest(1)) && p('code') && e('0');
r($cneTest->queryStatusTest(999)) && p() && e('0');
r($cneTest->queryStatusTest(0)) && p() && e('0');
r($cneTest->queryStatusTest(-1)) && p() && e('0');
r($cneTest->queryStatusTest(100)) && p() && e('0');