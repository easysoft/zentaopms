#!/usr/bin/env php
<?php
/**

title=测试 entryModel::update();
cid=1
pid=1

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/entry.class.php';

zdTable('entry')->gen(2);
zdTable('user')->gen(5);
su('admin');

$nameEmptyTest  = array('name' => '',              'code' => 'code1', 'account' => 'admin', 'ip' => '*', 'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 0, 'desc' => '这是应用描述1');
$codeEmptyTest  = array('name' => '这是应用名称1', 'code' => '',      'account' => 'admin', 'ip' => '*', 'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 0, 'desc' => '这是应用描述1');
$codeRepeatTest = array('name' => '这是应用名称1', 'code' => 'code2', 'account' => 'admin', 'ip' => '*', 'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 0, 'desc' => '这是应用描述1');
$keyEmptyTest   = array('name' => '这是应用名称1', 'code' => 'code1', 'account' => 'admin', 'ip' => '*', 'key' => '',                                 'freePasswd' => 0, 'desc' => '这是应用描述1');

$allIPTest      = array('name' => '这是应用名称2', 'code' => 'code2',      'account' => 'admin', 'allIP' => 'on', 'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 0, 'desc' => '这是应用描述2');
$freePasswdTest = array('name' => '这是应用名称2', 'code' => 'code2',      'account' => 'admin', 'ip' => '*',     'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 1, 'desc' => '这是应用描述2');
$normalTest     = array('name' => 'normalTest',    'code' => 'normalTest', 'account' => 'admin', 'ip' => '*',     'key' => '792b9b972157d2d8531b43e04c0af021', 'freePasswd' => 1, 'desc' => '这是应用描述2');

$entry = new entryTest();
r($entry->updateObject(1, $nameEmptyTest))  && p('name:0') && e('『名称』不能为空。'); //测试修改名称为空
r($entry->updateObject(1, $codeEmptyTest))  && p('code:0') && e('『代号』不能为空。'); //测试修改代号为空
r($entry->updateObject(1, $codeRepeatTest)) && p('code:0') && e('『代号』已经有『code2』这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。'); //测试修改为已存在的代号
r($entry->updateObject(1, $keyEmptyTest))   && p('key:0')  && e('『密钥』不能为空。'); //测试修改密钥为空

r($entry->updateObject(2, $allIPTest))      && p('0:field,old,new')                 && e('ip,127.0.0.1,*');                  //测试修改为IP不限制
r($entry->updateObject(2, $freePasswdTest)) && p('0:field,old,new;1:field,old,new') && e('account,admin,~~,freePasswd,0,1'); //测试修改freePasswd

r($entry->updateObject(2, $normalTest))     && p('0:field,old,new;1:field,old,new') && e('name,这是应用名称2,normalTest,code,code2,normalTest'); //测试正常修改名称和代号
