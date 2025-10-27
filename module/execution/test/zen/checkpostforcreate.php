#!/usr/bin/env php
<?php

/**

title=测试 executionZen::checkPostForCreate();
timeout=0
cid=0

- 执行executionzenTest模块的checkPostForCreateTest方法 属性project @所属项目不能为空。
- 执行executionzenTest模块的checkPostForCreateTest方法 属性project @所属项目不能为空。
- 执行executionzenTest模块的checkPostForCreateTest方法 属性project @所属项目不能为空。
- 执行executionzenTest模块的checkPostForCreateTest方法 属性project @所属项目不能为空。
- 执行executionzenTest模块的checkPostForCreateTest方法 属性project @所属项目不能为空。

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

zenData('project')->gen(0);

su('admin');

$executionzenTest = new executionZenTest();

$_POST = array();
r($executionzenTest->checkPostForCreateTest()) && p('project') && e('所属项目不能为空。');
$_POST['project'] = 0;
r($executionzenTest->checkPostForCreateTest()) && p('project') && e('所属项目不能为空。');
$_POST['project'] = '';
r($executionzenTest->checkPostForCreateTest()) && p('project') && e('所属项目不能为空。');
$_POST['project'] = null;
r($executionzenTest->checkPostForCreateTest()) && p('project') && e('所属项目不能为空。');
$_POST['project'] = false;
r($executionzenTest->checkPostForCreateTest()) && p('project') && e('所属项目不能为空。');