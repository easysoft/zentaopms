#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/confirmbug.ui.class.php';
$tester = new confirmBugTester();

$bug = array();
$bug['search'][0]     = array('field1' => 'Bug状态', 'operator1' => '=', 'value1' => '激活');
$bug['isResolved']    = '激活';
$bug['assignedTo']    = 'admin';
$bug['resolution']    = '已解决';
$bug['resolvedBuild'] = '主干';
$bug['resolvedDate']  = '2027-02-15';
$project = array();
$project['productID'] = 1;

r($tester->resolveBug($project, $bug)) && p('message,status') && e('解决bug成功'); //验证bug表单页必填项校验
