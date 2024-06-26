#!/usr/bin/env php
<?php
chdir(__DIR__);
include '../lib/confirmbug.ui.class.php';
$tester = new confirmBugTester();

$bug = array();
$bug['search'][0]  = array('field1' => '是否确认', 'operator1' => '=', 'value1' => '未确认');
$bug['assignedTo'] = 'admin';
$bug['type']       = '其他';
$bug['pri']        = '2';
$bug['deadline']   = '2027-02-15';
$bug['mailto']     = array('multiPicker' => 'admin');
$project = array();
$project['productID'] = 1;

r($tester->confirmBug($project, $bug)) && p('message,status') && e('确认bug成功'); //验证bug表单页必填项校验
