#!/usr/bin/env php
<?php

/**

title=测试 convertTao::importJiraProject();
timeout=0
cid=0

- 执行convertTest模块的importJiraProjectTest方法，参数是array  @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

if(!defined('JIRA_TMPRELATION')) define('JIRA_TMPRELATION', '`jiratmprelation`');

zenData('user')->gen(5);
zenData('project')->gen(5);
zenData('product')->gen(5);

global $app;
$app->session->set('jiraMethod', 'file');
$app->session->set('jiraUser', array('password' => '123456', 'group' => 1, 'mode' => 'account'));

su('admin');

$convertTest = new convertTest();

r($convertTest->importJiraProjectTest(array(
    '1001' => (object)array(
        'id' => '1001',
        'pkey' => 'TEST',
        'originalkey' => 'TEST_OLD',
        'pname' => 'Test Project',
        'description' => 'Test Description',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
))) && p() && e('true');

r($convertTest->importJiraProjectTest(array(
    '1002' => (object)array(
        'id' => '1002',
        'pkey' => 'DEL',
        'originalkey' => 'DEL_OLD',
        'pname' => 'Deleted Project',
        'description' => 'Deleted Description',
        'ptype' => 'software',
        'pstatus' => 'deleted'
    )
))) && p() && e('true');

r($convertTest->importJiraProjectTest(array())) && p() && e('true');

r($convertTest->importJiraProjectTest(array(
    '1003' => (object)array(
        'id' => '1003',
        'pkey' => 'ARCH',
        'originalkey' => 'ARCH_OLD',
        'pname' => 'Archived Project',
        'description' => 'Archived Description',
        'ptype' => 'software',
        'pstatus' => 'archived'
    ),
    '1004' => (object)array(
        'id' => '1004',
        'pkey' => 'NORM',
        'originalkey' => 'NORM_OLD',
        'pname' => 'Normal Project',
        'description' => 'Normal Description',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
))) && p() && e('true');

r($convertTest->importJiraProjectTest(array(
    '1001' => (object)array(
        'id' => '1001',
        'pkey' => 'EXIST',
        'originalkey' => 'EXIST_OLD',
        'pname' => 'Existing Project',
        'description' => 'Existing Description',
        'ptype' => 'software',
        'pstatus' => 'active'
    )
))) && p() && e('true');