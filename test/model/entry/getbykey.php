#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/entry.class.php';
su('admin');

/**

title=entryModel->getByKey();
cid=1
pid=1

查询key为792b9b972157d2d8531b43e04c0af021的entry的name >> 这是应用名称1
查询key为792b9b972157d2d8531b43e04c0af021的entry的account >> accountadmin
查询key为空的entry的name >> 0
查询key为空的entry的account >> 0

*/

$entryKeyList = array('792b9b972157d2d8531b43e04c0af021', '');

$entry = new entryTest();

r($entry->getByKeyTest($entryKeyList[0])) && p('name') && e('这是应用名称1');   // 查询key为792b9b972157d2d8531b43e04c0af021的entry的name
r($entry->getByKeyTest($entryKeyList[0])) && p('account') && e('accountadmin'); // 查询key为792b9b972157d2d8531b43e04c0af021的entry的account
r($entry->getByKeyTest($entryKeyList[1])) && p('name') && e('0');               // 查询key为空的entry的name
r($entry->getByKeyTest($entryKeyList[1])) && p('account') && e('0');            // 查询key为空的entry的account
