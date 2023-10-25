#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/entry.class.php';
su('admin');

zdTable('entry')->gen(0);

/**

title=entryModel->create();
cid=1
pid=1

测试name为空,code为空,account为空,freePasswd为空报错 >> 『名称』不能为空。
测试name为测试应用,code为空,account为空,freePasswd为1报错 >> 『代号』不能为空。,『代号』应当为字母或数字的组合。
测试name为测试应用,code为code_11111,account为空,freePasswd为1报错 >> code_11111
测试name为测试应用,code为code_22222,account为空,freePasswd为1报错 >> code_22222

*/

$nameList       = array('', '测试应用');
$codeList       = array('', 'code_11111', 'code_22222');
$accountList    = array('admin', '');
$freePasswdList = array(0, 1);

$entry = new entryTest();

r($entry->createObject($nameList[0], $codeList[0], $accountList[0], $freePasswdList[0])) && p('name:0') && e('『名称』不能为空。');                                     // 测试name为空,code为空,account为空,freePasswd为空报错
r($entry->createObject($nameList[1], $codeList[0], $accountList[0], $freePasswdList[1])) && p('code:0,1') && e('『代号』不能为空。,『代号』应当为字母或数字的组合。');  // 测试name为测试应用,code为空,account为空,freePasswd为1报错
r($entry->createObject($nameList[1], $codeList[1], $accountList[1], $freePasswdList[1])) && p('code') && e('code_11111');                                               // 测试name为测试应用,code为code_11111,account为空,freePasswd为1报错
r($entry->createObject($nameList[1], $codeList[2], $accountList[1], $freePasswdList[1])) && p('code') && e('code_22222');                                               // 测试name为测试应用,code为code_22222,account为空,freePasswd为1报错

