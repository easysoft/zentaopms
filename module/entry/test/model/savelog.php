#!/usr/bin/env php
<?php
/**

title=测试 entryModel::saveLog();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/entry.class.php';

zdTable('user')->gen(5);
su('admin');

$entryID = 1;
$url     = 'http://qcmmi.com';

$entry = new entryTest();
r($entry->saveLogTest($entryID, $url)) && p('objectID,objectType') && e('1,entry'); //测试插入一条日志
