#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/view.ui.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(10);
zendata('design')->loadYaml('design', false, 2)->gen(2);
$tester = new viewTester();
$tester->login();

$design = array();

r($tester->view($design)) && p('message') && e('设计详情页信息正确'); //检查设计详情页字段信息

$tester->closeBrowser();
