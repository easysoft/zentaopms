#!/usr/bin/env php
<?php

/**

title=测试 fileZen::buildDownloadTable();
timeout=0
cid=16541

- 执行fileZenTest模块的buildDownloadTableZenTest方法，参数是$fields, $rows, 'other'), 0, 7  @<table>
- 执行fileZenTest模块的buildDownloadTableZenTest方法，参数是$storyFields, $storyRows, 'story'), 'href=') !== false  @1
- 执行fileZenTest模块的buildDownloadTableZenTest方法，参数是$taskFields, $taskRows, 'task'), 'Task Name 1</a>') !== false  @1
- 执行fileZenTest模块的buildDownloadTableZenTest方法，参数是$emptyFields, $emptyRows, 'other'), 0, 7  @<table>
- 执行fileZenTest模块的buildDownloadTableZenTest方法，参数是$complexFields, $complexRows, 'other', $rowspans, $colspans), 'rowspan=') !== false  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/filezen.unittest.class.php';

$fileZenTest = new fileZenTest();

// 测试步骤1：基本HTML表格生成
$fields = array('id' => 'ID', 'name' => '名称', 'status' => '状态');
$rows = array(
    (object)array('id' => 1, 'name' => 'test1', 'status' => 'active'),
    (object)array('id' => 2, 'name' => 'test2', 'status' => 'closed')
);
r(substr($fileZenTest->buildDownloadTableZenTest($fields, $rows, 'other'), 0, 7)) && p() && e('<table>');

// 测试步骤2：story类型数据行生成链接  
$storyFields = array('id' => 'ID', 'title' => '标题', 'status' => '状态');
$storyRows = array(
    (object)array('id' => 1, 'title' => 'Story Title 1', 'status' => 'active')
);
r(strpos($fileZenTest->buildDownloadTableZenTest($storyFields, $storyRows, 'story'), 'href=') !== false) && p() && e('1');

// 测试步骤3：task类型数据行生成链接
$taskFields = array('id' => 'ID', 'name' => '任务名称', 'status' => '状态');
$taskRows = array(
    (object)array('id' => 1, 'name' => 'Task Name 1', 'status' => 'wait')
);
r(strpos($fileZenTest->buildDownloadTableZenTest($taskFields, $taskRows, 'task'), 'Task Name 1</a>') !== false) && p() && e('1');

// 测试步骤4：空数据行情况
$emptyFields = array('id' => 'ID', 'name' => '名称');
$emptyRows = array();
r(substr($fileZenTest->buildDownloadTableZenTest($emptyFields, $emptyRows, 'other'), 0, 7)) && p() && e('<table>');

// 测试步骤5：包含rowspan和colspan的复杂表格
$complexFields = array('id' => 'ID', 'name' => '名称', 'type' => '类型', 'status' => '状态');
$complexRows = array(
    (object)array('id' => 1, 'name' => 'test1', 'type' => 'bug', 'status' => 'active'),
    (object)array('id' => 2, 'name' => 'test2', 'type' => 'bug', 'status' => 'closed')
);
$rowspans = array(0 => array('rows' => array('type' => 2)));
$colspans = array(1 => array('cols' => array('name' => 2)));
r(strpos($fileZenTest->buildDownloadTableZenTest($complexFields, $complexRows, 'other', $rowspans, $colspans), 'rowspan=') !== false) && p() && e('1');