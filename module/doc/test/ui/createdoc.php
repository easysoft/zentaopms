#!/usr/bin/env php
<?php

chdir(__DIR__);
include '../lib/createdoc.ui.class.php';

$tester = new createDocTester();
$tester->login();

$libName = array();
$libName['null']    = '';
$libName['myDocLib'] = '我的文档库1';

r($tester->createDocLib($libName['null']))     && p('message,status') && e('创建文档表单页提示信息正确,SUCCESS'); // 缺少文档名称，创建失败
r($tester->createDocLib($libName['myDocLib'])) && p('message,status') && e('创建文档表单页提示信息正确,SUCCESS'); // 创建我的文档库，创建成功
