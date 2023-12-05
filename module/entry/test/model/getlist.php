#!/usr/bin/env php
<?php
/**

title=测试 entryModel::getList();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zdTable('entry')->gen(10);
zdTable('user')->gen(5);
su('admin');

global $tester;
$entry = $tester->loadModel('entry');

$entryList = $entry->getList();

r(count($entryList)) && p()         && e('10');            //测试获取列表的个数
r($entryList)        && p('1:name') && e('这是应用名称1'); //测试获取列表某个应用的名称信息
