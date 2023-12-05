#!/usr/bin/env php
<?php
/**

title=测试 entryModel::getByKey();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('entry')->gen(10);
zdTable('user')->gen(5);
su('admin');

global $tester;
$entry = $tester->loadModel('entry');

$entryKeyList = array('792b9b972157d2d8531b43e04c0af021', '');

r($entry->getByKey($entryKeyList[0])) && p('name')    && e('这是应用名称1');   // 查询key为792b9b972157d2d8531b43e04c0af021的entry的name
r($entry->getByKey($entryKeyList[0])) && p('account') && e('admin');           // 查询key为792b9b972157d2d8531b43e04c0af021的entry的account
r($entry->getByKey($entryKeyList[1])) && p()          && e('0');               // 查询key为空的entry
