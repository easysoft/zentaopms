#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/entry.class.php';
su('admin');

/**

title=entryModel->getById();
cid=1
pid=1

查询id为1的entry的name >> 这是应用名称1
查询id为1的entry的account >> accountadmin
查询id为1000001的entry的name >> 0
查询id为1000001的entry的account >> 0

*/

$entryIDList = array('1', '1000001');

$entry = new entryTest();

r($entry->getByIdTest($entryIDList[0])) && p('name') && e('这是应用名称1');   // 查询id为1的entry的name
r($entry->getByIdTest($entryIDList[0])) && p('account') && e('accountadmin'); // 查询id为1的entry的account
r($entry->getByIdTest($entryIDList[1])) && p('name') && e('0');               // 查询id为1000001的entry的name
r($entry->getByIdTest($entryIDList[1])) && p('account') && e('0');            // 查询id为1000001的entry的account
