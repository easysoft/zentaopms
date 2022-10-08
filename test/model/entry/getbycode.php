#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/entry.class.php';
su('admin');

/**

title=entryModel->getByCode();
cid=1
pid=1

查询code为code1的entry的name >> 这是应用名称1
查询code为code1的entry的account >> accountadmin
查询code为code2的entry的name >> 0
查询code为code2的entry的account >> 0

*/

$entryCodeList = array('code1', 'code2');

$entry = new entryTest();

r($entry->getByCodeTest($entryCodeList[0])) && p('name') && e('这是应用名称1');   // 查询code为code1的entry的name
r($entry->getByCodeTest($entryCodeList[0])) && p('account') && e('accountadmin'); // 查询code为code1的entry的account
r($entry->getByCodeTest($entryCodeList[1])) && p('name') && e('0');               // 查询code为code2的entry的name
r($entry->getByCodeTest($entryCodeList[1])) && p('account') && e('0');            // 查询code为code2的entry的account
