#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/entry.class.php';
su('admin');

/**

title=entryModel->create();
cid=1
pid=1

测试创建应用name为空报错 >> 『名称』不能为空
测试创建应用code为空报错 >> 『代号』不能为空
测试创建应用name为name_test >> name_test
测试创建应用code为code_test >> code_test

*/

$e_name       = array('name' => 'name_test');
$e_code       = array('code' => 'code_test');
$e_name_blank = array('name' => '');
$e_code_blank = array('code' => '');

$entry = new entryTest();

$result_name = $entry->createObject($e_name_blank);
$result_code = $entry->createObject($e_code_blank);
r($result_name['name'][0]) && p() && e('『名称』不能为空');       // 测试创建应用name为空报错
r($result_code['code'][0]) && p() && e('『代号』不能为空');       // 测试创建应用code为空报错
r($entry->createObject($e_name)) && p('name') && e('name_test');  // 测试创建应用name为name_test
r($entry->createObject($e_code)) && p('code') && e('code_test');  // 测试创建应用code为code_test
