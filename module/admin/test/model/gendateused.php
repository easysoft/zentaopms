#!/usr/bin/env php
<?php

include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

zenData('action')->gen(10);

/**

title=测试 adminModel::genDateUsed();
timeout=0
cid=14978

- 查看生成的日期使用情况
 - 属性year @0
 - 属性month @10
 - 属性day @1
 - 属性hour @0
 - 属性minute @0
 - 属性secound @0
- 查看生成的日期使用情况
 - 属性year @0
 - 属性month @1
 - 属性day @30
 - 属性hour @0
 - 属性minute @0
 - 属性secound @0

*/

global $tester;

$tester->loadModel('admin');
$result = $tester->admin->genDateUsed('2025-01-01');
r($result) && p('year,month,day,hour,minute,secound') && e('0,10,1,0,0,0'); // 查看生成的日期使用情况

$result = $tester->admin->genDateUsed('2026-01-01');
r($result) && p('year,month,day,hour,minute,secound') && e('0,1,30,0,0,0'); // 查看生成的日期使用情况