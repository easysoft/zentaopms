#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
zdTable('user')->gen(5);
zdTable('project')->config('execution')->gen(30);

$userview = zdTable('userview');
$userview->account->range('admin,user1,user2');
$userview->gen(3);
su('admin');

/**

title=测试 transfer->initSysDataFields();
timeout=0
cid=1

- 测试通过project模块的getpairs方法获取数据第project条的1属性 @项目11
- 测试通过user模块的getpairs方法获取数据第user条的admin属性 @admin
- 测试当methodName 不为'ajaxgettbody', 'ajaxgetoptions', 'showimport'时获取到的user数据第user条的user1属性 @用户1(#user1)

*/
global $tester, $app;
$app->methodName = 'ajaxgettbody';
$transfer = $tester->loadModel('transfer');

r($transfer->initSysDataFields()) && p('project:1') && e('项目11'); // 测试通过project模块的getpairs方法获取数据
r($transfer->initSysDataFields()) && p('user:admin') && e('admin'); // 测试通过user模块的getpairs方法获取数据

$app->methodName = 'browse';
r($transfer->initSysDataFields()) && p('user:user1') && e('用户1(#user1)'); // 测试当methodName 不为'ajaxgettbody', 'ajaxgetoptions', 'showimport'时获取到的user数据
