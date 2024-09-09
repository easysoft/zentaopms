#!/usr/bin/env php
<?php

chdir(__DIR__);
include '../lib/manageline.ui.class.php';
zendata('project')->loadYaml('program', false, 2)->gen(10);
zendata('module')->loadYaml('lines', false, 2)->gen(5);
zendata('product')->loadYaml('product', false, 2)->gen(10);

$tester = new manageLineTester();
$tester->login();

$line = new stdClass();
$line->name    = '产品线1';
$line->program = '项目集1';

r($tester->createLine($line)) && p('message,status') && e('产品线创建成功,SUCCESS');
r($tester->createLine($line)) && p('message,status') && e('产品线提示信息正确,SUCCESS');

$tester->closeBrowser();
