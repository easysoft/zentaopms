#!/usr/bin/env php
<?php

/**

title=创建文档测试
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/createapi.ui.class.php';

$tester = new createDocTester();
$tester->login();

$apiDoc = new stdClass();
$apiDoc->docA = 'apiDocA';
