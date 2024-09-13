#!/usr/bin/env php
<?php

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

r($tester->editDoc($docName, $editDocName)) && p('message,status') && e('编辑文档表单页提示信息正确,SUCCESS');
r($tester->moveDoc($libName))               && p('message,status') && e('移动文档成功，SUCCESS');
r($tester->deleteDoc())                     && p('message,status') && e('删除文档成功，SUCCESS');
