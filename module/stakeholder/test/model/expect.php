#!/usr/bin/env php
<?php
/**

title=测试 stakeholderModel->expect();
cid=1

- 测试不填写期望第expect条的0属性 @『expect』不能为空。
- 测试不填写进展第progress条的0属性 @『progress』不能为空。
- 测试创建一条期望
 - 属性userID @1
 - 属性expect @创建一条期望
 - 属性progress @创建一条进展
 - 属性project @11

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/stakeholder.class.php';

zdTable('expect')->gen(0);
zdTable('user')->gen(5);

$expect   = array('', '创建一条期望');
$progress = array('', '创建一条进展');

$emptyExpect   = array('expect' => $expect[0], 'progress' => $progress[1]);
$emptyProgress = array('expect' => $expect[1], 'progress' => $progress[0]);
$expectData    = array('expect' => $expect[1], 'progress' => $progress[1]);

$stakeholderTester = new stakeholderTest();
r($stakeholderTester->expectTest($emptyExpect))   && p('expect:0')                       && e('『expect』不能为空。');           // 测试不填写期望
r($stakeholderTester->expectTest($emptyProgress)) && p('progress:0')                     && e('『progress』不能为空。');         // 测试不填写进展
r($stakeholderTester->expectTest($expectData))    && p('userID,expect,progress,project') && e('1,创建一条期望,创建一条进展,11'); // 测试创建一条期望
