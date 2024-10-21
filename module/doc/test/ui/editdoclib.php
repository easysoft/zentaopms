#!/usr/bin/env php
<?php

/**

title=编辑文档库测试
timeout=0
cid=0

- 编辑文档库成功
 - 测试结果 @编辑文档库成功
 - 最终测试状态 @SUCCESS
- 删除文档库成功
 - 测试结果 @删除文档库成功
 - 最终测试状态 @SUCCESS

*/

chdir(__DIR__);
include '../lib/editdoclib.ui.class.php';

$tester = new createDocTester();
$tester->login();

$libName = new stdClass();
$libName->libName = '我的文档库1';

$editLibName = new stdClass();
$editLibName->editName = '编辑文档库1';

r($tester->editDocLib($libName, $editLibName)) && p('message,status') && e('编辑文档库成功,SUCCESS'); //编辑文档库成功
r($tester->deleteDocLib($editLibName))         && p('message,status') && e('删除文档库成功,SUCCESS'); //删除文档库成功