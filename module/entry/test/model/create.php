#!/usr/bin/env php
<?php
/**

title=测试 entryModel::create();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/entry.class.php';

zdTable('entry')->gen(1);
zdTable('user')->gen(5);
su('admin');

$emptyNameTest    = array('name' => '',         'code' => 'codeTest', 'account' => 'admin', 'ip' => '', 'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 0, 'desc' => '这是描述');
$emptyCodeTest    = array('name' => 'nameTest', 'code' => '',         'account' => 'admin', 'ip' => '', 'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 0, 'desc' => '这是描述');
$emptyAccountTest = array('name' => 'nameTest', 'code' => 'codeTest', 'account' => '',      'ip' => '', 'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 0, 'desc' => '这是描述');
$emptyKeyTest     = array('name' => 'nameTest', 'code' => 'codeTest', 'account' => 'admin', 'ip' => '', 'key' => '',                                 'freePasswd' => 0, 'desc' => '这是描述');
$codeRepeatTest   = array('name' => 'nameTest', 'code' => 'code1',    'account' => 'admin', 'ip' => '', 'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 0, 'desc' => '这是描述');

$allIPTest      = array('name' => 'allIPTest',      'code' => 'allIPTest',      'account' => 'admin', 'allIP' => 'on',     'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 0, 'desc' => '这是描述');
$freePasswdTest = array('name' => 'freePasswdTest', 'code' => 'freePasswdTest', 'account' => 'admin', 'ip' => '127.0.0.1', 'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 1, 'desc' => '这是描述');

$normalTest = array('name' => 'normalTest', 'code' => 'normalTest', 'account' => 'admin', 'ip' => '127.0.0.1', 'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 0, 'desc' => '这是描述');

$entry = new entryTest();
r($entry->createObject($emptyNameTest))    && p('name:0')    && e('『名称』不能为空。'); //测试name为空
r($entry->createObject($emptyCodeTest))    && p('code:0')    && e('『代号』不能为空。'); //测试code为空
r($entry->createObject($emptyAccountTest)) && p('account:0') && e('『账号』不能为空。'); //测试account为空
r($entry->createObject($emptyKeyTest))     && p('key:0')     && e('『密钥』不能为空。'); //测试key为空
r($entry->createObject($codeRepeatTest))   && p('code:0')    && e('『代号』已经有『code1』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); //测试重复code

r($entry->createObject($allIPTest))      && p('ip')      && e('*');  //不限制IP时，ip设置为*
r($entry->createObject($freePasswdTest)) && p('account') && e('~~'); //开启免密登录时，account设置为空

r($entry->createObject($normalTest)) && p('name,code,account,key') && e('normalTest,normalTest,admin,792b9b972157d2d8531b43e04c0af021'); //正常测试
