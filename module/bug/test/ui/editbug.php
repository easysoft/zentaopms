#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/confirmbug.ui.class.php';
$tester = new confirmBugTester();

$bug = array();
$bug['search'][] = array('field1' => 'Bug状态', 'operator1' => '=', 'value1' => '激活');
$bug['bugName']  = 'bug' . time();
$project = array();
$project['productID'] = 1;

r($tester->editBug($project, $bug)) && p('message,status') && e('解决bug成功'); //验证bug表单页必填项校验
