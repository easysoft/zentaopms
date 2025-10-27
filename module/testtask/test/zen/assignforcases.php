#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::assignForCases();
timeout=0
cid=0

- 执行testtaskTest模块的assignForCasesTest方法  @0
- 执行testtaskTest模块的assignForCasesTest方法  @0
- 执行testtaskTest模块的assignForCasesTest方法  @0
- 执行testtaskTest模块的assignForCasesTest方法  @0
- 执行testtaskTest模块的assignForCasesTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

zendata('testtask')->loadYaml('testtask_assignforcases', false, 2)->gen(5);

su('admin');

$testtaskTest = new testtaskZenTest();

r($testtaskTest->assignForCasesTest()) && p() && e('0');
r($testtaskTest->assignForCasesTest((object)array('id' => 2, 'name' => '产品2', 'type' => 'branch', 'shadow' => 0), (object)array('id' => 2, 'execution' => 2, 'branch' => '1', 'name' => '多分支测试单'), array(), array(), 0, 'all', 0, 'id_desc')) && p() && e('0');
r($testtaskTest->assignForCasesTest((object)array('id' => 1, 'name' => '产品1', 'type' => 'normal', 'shadow' => 0), (object)array('id' => 1, 'execution' => 1, 'branch' => '0', 'name' => '带场景的测试单'), array((object)array('id' => 1, 'case' => 1)), array((object)array('id' => 101, 'case' => 101)), 5, 'bymodule', 5, 'case_desc')) && p() && e('0');
r($testtaskTest->assignForCasesTest((object)array('id' => 3, 'name' => '产品3', 'type' => 'normal', 'shadow' => 0), (object)array('id' => 3, 'execution' => 3, 'branch' => '0', 'name' => '私有执行测试单'), array(), array(), 0, 'all', 0, 'id_asc')) && p() && e('0');
r($testtaskTest->assignForCasesTest((object)array('id' => 1, 'name' => '产品1', 'type' => 'normal', 'shadow' => 0), (object)array('id' => 1, 'execution' => 1, 'branch' => '0', 'name' => '套件浏览测试单'), array(), array(), 0, 'bysuite', 10, 'priority_desc')) && p() && e('0');