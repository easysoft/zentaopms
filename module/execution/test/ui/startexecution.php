#!/usr/bin/env php
<?php

/**
title=开始执行
timeout=0
cid=1
*/

chdir(__DIR__);
include '../lib/startexecution.ui.class.php';

zendata('project')->loadYaml('execution', false, 2)->gen(10);
$tester = new startExecutionTester();
$tester->login();

$realBegan = array(date('Y-m-d', strtotime('+20 days')), date('Y-m-d'));

r($tester->startWithGreaterDate($realBegan[0])) && p('status,message') && e('SUCCESS,开始执行表单页提示信息正确');
r($tester->start($realBegan[1]))                && p('status,message') && e('SUCCESS,开始执行成功');
$tester->closeBrowser();
