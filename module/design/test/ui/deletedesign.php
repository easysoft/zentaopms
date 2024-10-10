#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/deletedesign.ui.class.php';

zendata('project')->loadYaml('project', false, 2)->gen(10);
zendata('design')->loadYaml('design', false, 2)->gen(2);
$tester = new deleteDesignTester();
$tester->login();

$design = array();

r($tester->deleteDesign($design)) && p('message') && e('删除设计成功'); //检查删除设计成功

$tester->closeBrowser();
