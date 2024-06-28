#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/confirmbug.ui.class.php';
$tester = new confirmBugTester();

$bug = array();
$bug['search'][] = array('field1' => 'Bug状态', 'operator1' => '=', 'value1' => '激活');
$project = array();
$project['productID'] = 1;

r($tester->editBug($project, $bug)) && p('message,status') && e('编辑bug成功'); //验证直接编辑bug是否成功

$bug['bugName']  = 'bug' . time();
r($tester->editBug($project, $bug)) && p('message,status') && e('编辑bug名称成功'); //验证编辑bug名称是否成功
