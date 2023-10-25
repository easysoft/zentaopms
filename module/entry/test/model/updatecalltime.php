#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/entry.class.php';
su('admin');

/**

title=entryModel->updateCalledTime();
cid=1
pid=1

测试更新entry代号存在的情况 >> 这是应用名称1
测试更新entry代号不存在的情况 >> 0

*/

$code = array('code1','code2');
$time = 2;

$entry = new entryTest();

r($entry->updateCalledTimeTest($code[0], $time)) && p('name') && e('这是应用名称1');  //测试更新entry代号存在的情况
r($entry->updateCalledTimeTest($code[1], $time)) && p()       && e('~~');              //测试更新entry代号不存在的情况
