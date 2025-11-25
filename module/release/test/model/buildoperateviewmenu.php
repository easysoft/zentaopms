#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::buildOperateViewMenu();
timeout=0
cid=17982

- 测试正常状态发布的操作菜单第0条的text属性 @停止维护
- 测试停止维护状态发布的操作菜单第0条的text属性 @激活
- 测试已删除发布的操作菜单 @0
- 测试不存在发布的操作菜单 @0
- 测试操作菜单项数量 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

// 以管理员身份登录
su('admin');

$releaseTester = new releaseTest();

r($releaseTester->buildOperateViewMenuTest(1)) && p('0:text') && e('停止维护');  // 测试正常状态发布的操作菜单
r($releaseTester->buildOperateViewMenuTest(2)) && p('0:text') && e('激活');     // 测试停止维护状态发布的操作菜单
r($releaseTester->buildOperateViewMenuTest(3)) && p() && e('0');               // 测试已删除发布的操作菜单
r($releaseTester->buildOperateViewMenuTest(999)) && p() && e('0');             // 测试不存在发布的操作菜单
r(count($releaseTester->buildOperateViewMenuTest(1))) && p() && e('3');       // 测试操作菜单项数量