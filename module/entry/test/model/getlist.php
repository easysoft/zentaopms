#!/usr/bin/env php
<?php
/**

title=测试 entryModel::getList();
cid=16249

- 测试获取列表的个数 @10
- 测试获取列表某个应用的名称信息第1条的name属性 @这是应用名称1
- 测试获取列表某个应用的名称信息第1条的name属性 @这是应用名称2
- 测试获取列表某个应用的名称信息第1条的name属性 @这是应用名称3
- 测试获取列表某个应用的名称信息第1条的name属性 @这是应用名称4

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('entry')->gen(10);
zenData('user')->gen(5);
su('admin');

global $tester;
$entry = $tester->loadModel('entry');

$entryList = $entry->getList();

r(count($entryList)) && p()         && e('10');            //测试获取列表的个数
r($entryList)        && p('1:name') && e('这是应用名称1'); //测试获取列表某个应用的名称信息
r($entryList)        && p('2:name') && e('这是应用名称2'); //测试获取列表某个应用的名称信息
r($entryList)        && p('3:name') && e('这是应用名称3'); //测试获取列表某个应用的名称信息
r($entryList)        && p('4:name') && e('这是应用名称4'); //测试获取列表某个应用的名称信息
