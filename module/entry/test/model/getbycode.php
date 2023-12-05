#!/usr/bin/env php
<?php
/**

title=测试 entryModel::getByCode();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('entry')->gen(100);
zdTable('user')->gen(5);
su('admin');

global $tester;
$entry = $tester->loadModel('entry');

$entryCodeList = array('code1', 'code2', '');

r($entry->getByCode($entryCodeList[0])) && p('name')    && e('这是应用名称1'); // 查询code为code1的entry的name
r($entry->getByCode($entryCodeList[0])) && p('account') && e('admin');         // 查询code为code1的entry的account
r($entry->getByCode($entryCodeList[1])) && p('name')    && e('这是应用名称2'); // 查询code为code2的entry的name
r($entry->getByCode($entryCodeList[1])) && p('account') && e('admin');         // 查询code为code2的entry的account
r($entry->getByCode($entryCodeList[2])) && p()          && e('0');             // 查询code为空的entry
