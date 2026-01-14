#!/usr/bin/env php
<?php

/**

title=测试 convertTao::createCase();
timeout=0
cid=15834

- 执行convertTest模块的createCaseTest方法，参数是1, 1, 1,   @0
- 执行convertTest模块的createCaseTest方法，参数是999, 1, 1,   @0
- 执行convertTest模块的createCaseTest方法，参数是1, 1, 1,   @0
- 执行convertTest模块的createCaseTest方法，参数是1, 1, 1,   @0
- 执行convertTest模块的createCaseTest方法，参数是0, 0, 0,   @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

su('admin');

$convertTest = new convertTaoTest();

r($convertTest->createCaseTest(1, 1, 1, (object)array('id' => 'JIRA-001', 'summary' => '正常测试用例', 'priority' => '2', 'issuestatus' => 'open', 'issuetype' => 'test', 'creator' => 'admin', 'created' => '2023-01-01 10:00:00'), array())) && p('') && e('0');
r($convertTest->createCaseTest(999, 1, 1, (object)array('id' => 'JIRA-002', 'summary' => '无效产品ID测试', 'priority' => '3', 'issuestatus' => 'open', 'issuetype' => 'test', 'creator' => 'admin', 'created' => '2023-01-01 11:00:00'), array())) && p('') && e('0');
r($convertTest->createCaseTest(1, 1, 1, (object)array('id' => '', 'summary' => '', 'priority' => '', 'issuestatus' => '', 'issuetype' => '', 'creator' => '', 'created' => ''), array())) && p('') && e('0');
r($convertTest->createCaseTest(1, 1, 1, (object)array('id' => 'JIRA-004', 'summary' => '缺少字段测试'), array())) && p('') && e('0');
r($convertTest->createCaseTest(0, 0, 0, (object)array('id' => 'JIRA-005', 'summary' => '边界值测试', 'priority' => '1', 'issuestatus' => 'closed', 'issuetype' => 'test', 'creator' => 'testuser', 'created' => '2023-01-01 12:00:00'), array())) && p('') && e('0');