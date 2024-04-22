#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/dev.unittest.class.php';

/**

title=测试 devModel::getLinkTitle();
cid=1
pid=1

检查my模块的导航名称 >> 地盘

*/

$devTester = new devTest();
r($devTester->getLinkTitleTest()) && p('my') && e("地盘"); // 检查my模块的导航名称
