#!/usr/bin/env php
<?php

/**
title=挂起执行
timeout=0
cid=1
*/

chdir(__DIR__);
include '../lib/suspendexecution.ui.class.php';

zendata('project')->loadYaml('execution', false, 2)->gen(10);
$tester = new suspendExecutionTester();
$tester->login();

r($tester->suspend('101')) && p('status,message') && e('SUCCESS,挂起执行成功'); //挂起未开始的执行
r($tester->suspend('103')) && p('status,message') && e('SUCCESS,挂起执行成功'); //挂起进行中的执行
$tester->closeBrowser();
