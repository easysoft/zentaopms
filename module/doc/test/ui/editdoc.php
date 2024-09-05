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

r($tester->editDoc($editDocName)) && p('message,status') && e('编辑文档表单页提示信息正确,SUCCESS');
