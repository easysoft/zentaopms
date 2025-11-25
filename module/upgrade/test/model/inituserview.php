#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->initUserView();
cid=19534

- 测试初始化用户视图 @0

**/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/upgrade.unittest.class.php';

$upgrade = new upgradeTest();
r($upgrade->initUserViewTest()) && p() && e('0'); // 测试初始化用户视图
