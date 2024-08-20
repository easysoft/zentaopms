#!/usr/bin/env php
<?php

/**
title=关闭执行
timeout=0
cid=1
*/

chdir(__DIR__);
include '../lib/closeexecution.ui.class.php';

zendata('project')->loadYaml('execution', false, 2)->gen(10);

$tester = new closeExecutionTester();
$tester->login();

$realEnd = array(date('Y-m-d', strtotime('+20 days')), date('Y-m-d'));

r($tester->closeWithGreaterDate($realEnd[0])) && p('message') && e('关闭执行表单页提示信息正确');
r($tester->close($realEnd[1]))                && p('message') && e('关闭执行成功');
$tester->closeBrowser();
