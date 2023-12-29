#!/usr/bin/env php
<?php
/**

title=测试 entryModel::updateCalledTime();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/entry.class.php';

zdTable('entry')->gen(1);
zdTable('user')->gen(5);
su('admin');

$code = array('code1','noCode');
$time = 2;

$entry = new entryTest();
r($entry->updateCalledTimeTest($code[0], $time)) && p('calledTime') && e('2'); //测试calledTime是否更新成功
r($entry->updateCalledTimeTest($code[1], $time)) && p()             && e('0'); //测试更新entry代号不存在的情况
