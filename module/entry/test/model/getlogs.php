#!/usr/bin/env php
<?php
/**

title=测试 entryModel::getLogs();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('log')->gen(10);
zdTable('user')->gen(5);
su('admin');

global $tester;
$entry = $tester->loadModel('entry');

$entryIDList = array('1', '100001');

r(count($entry->getLogs($entryIDList[0]))) && p() && e('1'); //测试获取ID为1的entry日志列表的个数
r(count($entry->getLogs($entryIDList[1]))) && p() && e('0'); //测试获取ID为不存在的entry日志列表的个数
