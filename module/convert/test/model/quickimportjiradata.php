#!/usr/bin/env php
<?php

/**

title=测试 convertModel::quickImportJiraData();
timeout=0
cid=15763

- 执行convertTest模块的quickImportJiraDataTest方法  @1
- 执行convertTest模块的quickImportJiraDataTest方法  @1
- 执行convertTest模块的quickImportJiraDataTest方法  @1
- 执行convertTest模块的quickImportJiraDataTest方法  @1
- 执行convertTest模块的quickImportJiraDataTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

r($convertTest->quickImportJiraDataTest()) && p() && e('1');
r($convertTest->quickImportJiraDataTest()) && p() && e('1');
r($convertTest->quickImportJiraDataTest()) && p() && e('1');
r($convertTest->quickImportJiraDataTest()) && p() && e('1');
r($convertTest->quickImportJiraDataTest()) && p() && e('1');
