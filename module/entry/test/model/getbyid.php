#!/usr/bin/env php
<?php
/**

title=测试 entryModel::getById();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/entry.class.php';

zdTable('entry')->gen(100);
zdTable('user')->gen(5);
su('admin');

global $tester;
$entry = $tester->loadModel('entry');

$entryIDList = array('1', '1000001');

r($entry->getById($entryIDList[0])) && p('name')    && e('这是应用名称1'); // 查询id为1的entry的name
r($entry->getById($entryIDList[0])) && p('account') && e('admin');         // 查询id为1的entry的account
r($entry->getById($entryIDList[1])) && p()          && e('0');             // 查询不存在ID的entry
