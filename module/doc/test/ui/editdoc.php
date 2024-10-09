#!/usr/bin/env php
<?php

/**

title=编辑文档测试
timeout=0
cid=0

- 编辑文档成功
 - 测试结果 @编辑文档成功
 - 最终测试状态 @SUCCESS
- 移动文档成功
 - 测试结果 @移动文档成功
 - 最终测试状态 @SUCCESS
- 删除文档成功
 - 测试结果 @删除文档成功
 - 最终测试状态 @SUCCESS

*/
chdir(__DIR__);
include '../lib/editdoc.ui.class.php';

$tester = new createDocTester();
$tester->login();

$docName = new stdClass();
$docName->dcName = '文档1';

$editDocName = new stdClass();
$editDocName->editName = '编辑文档1';

$libName = new stdClass();
$libName->myDocLib = '我的文档库1';

r($tester->editDoc($docName, $editDocName)) && p('message,status') && e('编辑文档成功,SUCCESS'); //编辑文档成功
r($tester->moveDoc($libName))               && p('message,status') && e('移动文档成功,SUCCESS'); //移动文档成功
r($tester->deleteDoc())                     && p('message,status') && e('删除文档成功,SUCCESS'); //删除文档成功