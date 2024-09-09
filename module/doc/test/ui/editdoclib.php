#!/usr/bin/env php
<?php

chdir(__DIR__);
include '../lib/editdoclib.ui.class.php';

$tester = new createDocTester();
$tester->login();

$libName = new stdClass();
$libName->libName = '我的文档库1';

$editLibName = new stdClass();
$editLibName->editName = '编辑文档库1';

r($tester->editDocLib($libName, $editLibName)) && p('message,status') && e('编辑文档库成功,SUCCESS');
r($tester->deleteDocLib($editLibName))         && p('message,status') && e('删除文档库成功,SUCCESS');
