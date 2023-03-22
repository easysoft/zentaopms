#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/entry.class.php';
su('admin');

/**

title=entryModel->update();
cid=1
pid=1

测试更新entry名称 >> name,这是应用名称1,这是应用名称2
测试更新entry代号 >> code,code1,code2
测试更新entry名称和代号 >> name,这是应用名称2,这是应用名称1;code,code2,code1
测试不更新name >> 没有数据更新

*/

$entryID = 1;

$e_upName        = array('name' => '这是应用名称2');
$e_upCode        = array('code' => 'code2');
$e_upNameAndCode = array('name' => '这是应用名称1', 'code' => 'code1');
$e_unName        = array('name' => '这是应用名称1');

$entry = new entryTest();

r($entry->updateObject($entryID, $e_upName))        && p('0:field,old,new')                 && e('name,这是应用名称1,这是应用名称2');                   //测试更新entry名称
r($entry->updateObject($entryID, $e_upCode))        && p('0:field,old,new')                 && e('code,code1,code2');                                   //测试更新entry代号
r($entry->updateObject($entryID, $e_upNameAndCode)) && p('0:field,old,new;1:field,old,new') && e('name,这是应用名称2,这是应用名称1;code,code2,code1');  //测试更新entry名称和代号
r($entry->updateObject($entryID, $e_unName))        && p()                                  && e('没有数据更新');                                       //测试不更新name
