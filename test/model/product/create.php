#!/usr/bin/env php
<?php
include dirname(dirname(dirname(__FILE__))) . '/lib/init.php';
include dirname(dirname(dirname(__FILE__))) . '/class/product.class.php';

#su('admin');

/**

title=测试productModel->create();
cid=1
pid=1

测试正常的创建 >> case1
测试不填产品代号的情况 >> 『code』不能为空。
测试创建重复的产品 >> 『code』已经有『testcase1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。
测试不填产品名称的情况 >> 『name』不能为空。
测试传入name和code >> case3,testcase3
测试传入program、name、code >> 3,case4,testcase4
测试传入program、name、code、type、status >> 4,branch,closed
测试不填权限控制的情况 >> 『code』不能为空。

*/

$createtest = new Product('admin');

$t_create = array('name' => 'case1', 'code' => 'testcase1');
$t_noid = array('name' => 'case2');
$t_repeat = array('name' => 'case1', 'code' => 'testcase1');
$t_noname = array('code' => 'testcase1');
$t_namecode = array('name' => 'case3', 'code' => 'testcase3');
$t_pronamecode = array('program' => '3', 'name' => 'case4', 'code' => 'testcase4');
$t_intypestatus = array('program' => '4', 'name' => 'case5', 'code' => 'testcase5', 'type' => 'branch', 'status' => 'closed');
$t_noacl = array('name' => 'case12', 'code' => 'testcase1', 'acl' => '');

r($createtest->createObject($t_create))       && p('name')                && e('case1');              // 测试正常的创建
r($createtest->createObject($t_noid))         && p('code:0')              && e('『code』不能为空。'); // 测试不填产品代号的情况
r($createtest->createObject($t_repeat))       && p('code:0')              && e('『code』已经有『testcase1』这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。'); // 测试创建重复的产品
r($createtest->createObject($t_noname))       && p('name:0')              && e('『name』不能为空。'); // 测试不填产品名称的情况
r($createtest->createObject($t_namecode))     && p('name,code')           && e('case3,testcase3');    // 测试传入name和code
r($createtest->createObject($t_pronamecode))  && p('program,name,code')   && e('3,case4,testcase4');  // 测试传入program、name、code
r($createtest->createObject($t_intypestatus)) && p('program,type,status') && e('4,branch,closed');    // 测试传入program、name、code、type、status
r($createtest->createObject($t_noacl))        && p('acl:0')               && e('『code』不能为空。'); // 测试不填权限控制的情况
system("./ztest init");