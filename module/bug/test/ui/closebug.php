#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/confirmbug.ui.class.php';
$tester = new confirmBugTester();

$bug = array();
$bug['search'][0] = array('field1' => 'Bug状态', 'operator1' => '=', 'value1' => '已解决');
$project = array();
$project['productID'] = 1;

r($tester->closeBug($project, $bug)) && p('message,status') && e('关闭bug成功'); //验证bug表单页必填项校验
