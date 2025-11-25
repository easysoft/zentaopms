#!/usr/bin/env php
<?php

/**

title=测试 userZen::login();
timeout=0
cid=19676

- 执行userZenTest模块的loginTest方法，参数是'', '', '', '', '', ''  @0
- 执行userZenTest模块的loginTest方法，参数是'', '', '', '', '', '', 'admin', '123456' 属性result @success
- 执行userZenTest模块的loginTest方法，参数是'', '', '', '', '', '', 'invaliduser', '123456' 属性result @fail
- 执行userZenTest模块的loginTest方法，参数是'', '', '', '', '', '', '', '123456' 属性result @fail
- 执行userZenTest模块的loginTest方法，参数是'', 'json', '', '', '', '', 'admin', '123456' 属性status @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,test1,test2');
$user->password->range('e10adc3949ba59abbe56e057f20f883e{5}');
$user->realname->range('管理员,用户1,用户2,测试1,测试2');
$user->role->range('admin,qa{2},dev{2}');
$user->email->range('admin@test.com,user1@test.com,user2@test.com,test1@test.com,test2@test.com');
$user->fails->range('0{5}');
$user->deleted->range('0{5}');
$user->gen(5);

su('admin');

$userZenTest = new userZenTest();

r($userZenTest->loginTest('', '', '', '', '', '')) && p() && e('0');
r($userZenTest->loginTest('', '', '', '', '', '', 'admin', '123456')) && p('result') && e('success');
r($userZenTest->loginTest('', '', '', '', '', '', 'invaliduser', '123456')) && p('result') && e('fail');
r($userZenTest->loginTest('', '', '', '', '', '', '', '123456')) && p('result') && e('fail');
r($userZenTest->loginTest('', 'json', '', '', '', '', 'admin', '123456')) && p('status') && e('success');