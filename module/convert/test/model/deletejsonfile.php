#!/usr/bin/env php
<?php

/**

title=测试 convertModel::deleteJsonFile();
timeout=0
cid=15770

- 测试删除Json文件
 - 属性totalFiles @4
 - 属性deletedCount @4
- 测试删除Json文件
 - 属性totalFiles @5
 - 属性deletedCount @4
- 测试删除Json文件
 - 属性totalFiles @6
 - 属性deletedCount @5
- 测试删除Json文件
 - 属性totalFiles @6
 - 属性deletedCount @5
- 测试删除Json文件
 - 属性totalFiles @6
 - 属性deletedCount @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

global $app;
$jiraPath = $app->getTmpRoot() . 'jirafile/json/';

// 创建测试目录和测试文件
if(!is_dir($jiraPath)) mkdir($jiraPath, 0777, true);

// 先删掉多余文件
$files = glob($jiraPath . '*');
foreach($files as $file) @unlink($file);

// 创建一些测试文件
$testFiles = array('action.json', 'project.json', 'issue.json', 'user.json');
foreach($testFiles as $file) file_put_contents($jiraPath . $file, "{1:'asd'}");

r($convertTest->deleteJsonFileTest()) && p('totalFiles,deletedCount') && e('4,4'); // 测试删除Json文件

// 创建一些测试文件
$testFiles = array('action.json', 'project.json', 'issue.json', 'user.json', 'aaa.xml');
foreach($testFiles as $file) file_put_contents($jiraPath . $file, "{1:'asd'}");

r($convertTest->deleteJsonFileTest()) && p('totalFiles,deletedCount') && e('5,4'); // 测试删除Json文件

// 创建一些测试文件
$testFiles = array('action.json', 'project.json', 'issue.json', 'user.json', 'aaa.xml', 'bb.json');
foreach($testFiles as $file) file_put_contents($jiraPath . $file, "{1:'asd'}");

r($convertTest->deleteJsonFileTest()) && p('totalFiles,deletedCount') && e('6,5'); // 测试删除Json文件

// 创建一些测试文件
$testFiles = array('action.json', 'project.json', 'issue.json', 'user.json', 'aaa.xml', 'cc.json');
foreach($testFiles as $file) file_put_contents($jiraPath . $file, "{1:'asd'}");

r($convertTest->deleteJsonFileTest()) && p('totalFiles,deletedCount') && e('6,5'); // 测试删除Json文件

// 创建一些测试文件
$testFiles = array('action.json', 'project.json', 'issue.json', 'user.json', 'aaa.xml', 'dd.json');
foreach($testFiles as $file) file_put_contents($jiraPath . $file, "{1:'asd'}");

r($convertTest->deleteJsonFileTest()) && p('totalFiles,deletedCount') && e('6,5'); // 测试删除Json文件
