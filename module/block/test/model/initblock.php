#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';
su('admin');

/**

title=测试 blockModel->initBlock();
timeout=0
cid=1

- 测试获取正常的block的内容
 - 属性account @admin
 - 属性dashboard @my
 - 属性module @welcome
 - 属性code @welcome
 - 属性title @欢迎

- 测试获取正常的block的内容
 - 属性account @admin
 - 属性dashboard @my
 - 属性module @project
 - 属性code @project
 - 属性title @项目列表

*/

global $tester, $app;
$tester->loadModel('block');
$tester->block->reset('my');

$tester->block->initBlock('my');
$newBlcoks = $tester->block->getMyDashboard('my');

r(reset($newBlcoks)) && p('account,dashboard,module,code,title') && e('admin,my,welcome,welcome,欢迎');        // 测试获取正常的block的内容
r(end($newBlcoks)) && p('account,dashboard,module,code,title') && e('admin,my,project,project,项目列表');        // 测试获取正常的block的内容
