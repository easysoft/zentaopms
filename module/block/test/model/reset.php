#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/block.class.php';

su('admin');

/**

title=测试 block 模块的update 方法
timeout=0
cid=39

- 检查初始化前的区块初始化状态返回结果 @1

- 检查初始化前是否存在区块数据
 - 属性account @admin
 - 属性dashboard @my
 - 属性module @welcome
 - 属性code @welcome
 - 属性title @欢迎

- 检查初始化后的返回结果 @1

- 检查初始化后的区块初始化状态返回结果 @0

- 检查初始化前是否存在区块数据属性account @0

*/

global $tester;
$tester->loadModel('block');

$blockTest = new blockTest();
$tester->block->reset('my');
$tester->block->initBlock('my');

r($tester->block->getBlockInitStatus('my')) && p('') && e('1'); // 检查初始化前的区块初始化状态返回结果
$myDashboard = $tester->block->getMyDashboard('my');
r(reset($myDashboard)) && p('account,dashboard,module,code,title') && e('admin,my,welcome,welcome,欢迎'); // 检查初始化前是否存在区块数据
r($tester->block->reset('my')) && p('') && e('1'); // 检查初始化后的返回结果
r($tester->block->getBlockInitStatus('my')) && p('') && e('0'); // 检查初始化后的区块初始化状态返回结果
$myDashboard2 = $tester->block->getMyDashboard('my');
r(reset($myDashboard2)) && p('account') && e('0'); // 检查初始化前是否存在区块数据