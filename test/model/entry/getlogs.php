#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/entry.class.php';
su('admin');

/**

title=entryModel->getLogs();
cid=1
pid=1

测试获取列表的个数，ID为1的日志数为1 >> 1
测试获取列表个数，ID为100001的日志数为0 >> 0

*/

$entryIDList = array('1', '100001');

$entry = new entryTest();

$list_1       = $entry->getLogsTest($entryIDList[0]);
$list_1000001 = $entry->getLogsTest($entryIDList[1]);
r(count($list_1))       && p() && e('1'); //测试获取列表的个数，ID为1的日志数为1
r(count($list_1000001)) && p() && e('0'); //测试获取列表个数，ID为100001的日志数为0
