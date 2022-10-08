#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/entry.class.php';
su('admin');

/**

title=entryModel->getList();
cid=1
pid=1

测试获取列表的个数，目前只有一个 >> 1
测试获取列表某个应用的名称信息 >> 这是应用名称1

*/

$entry = new entryTest();
$list  = $entry->getListTest();

r(count($list)) && p()         && e('1');               //测试获取列表的个数，目前只有一个
r($list)        && p('1:name') && e('这是应用名称1');   //测试获取列表某个应用的名称信息
