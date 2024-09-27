#!/usr/bin/env php
<?php

/**
title=批量关闭计划
timeout=0
cid=0

*/
chdir(__DIR__);
include '../lib/batchchangestatus.ui.class.php';
zendata('product')->loadYaml('product', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan', false, 2)->gen(10);
$tester = new batchChangeStatusTester();
$tester->login();

$planurl['product'] = 2;
r($tester->batchClose($planurl)) && p('message,status') && e("批量关闭计划成功,SUCCESS");//批量关闭计划
$tester->closeBrowser();
