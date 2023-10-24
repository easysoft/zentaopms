#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/entry.class.php';
su('admin');

/**

title=entryModel->saveLog();
cid=1
pid=1

测试插入一条日志 >> entry

*/

$e_id  = 1;
$e_url = 'http://qcmmi.com';

$entry = new entryTest();

r($entry->saveLogTest($e_id, $e_url)) && p('objectType') && e('entry'); //测试插入一条日志
