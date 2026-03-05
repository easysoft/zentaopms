#!/usr/bin/env php
<?php

/**

title=测试 convertModel::checkJiraApi();
timeout=0
cid=15763

- 执行convertTest模块的checkJiraAPITest方法 @0
- 执行convertTest模块的checkJiraAPITest方法 @0
- 执行convertTest模块的checkJiraAPITest方法 @0
- 执行convertTest模块的checkJiraAPITest方法 @0
- 执行convertTest模块的checkJiraAPITest方法 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$convertTest = new convertModelTest();

$_SESSION['jiraApi'] = '';
r($convertTest->checkJiraApiTest()) && p() && e('0');
r($convertTest->checkJiraApiTest()) && p() && e('0');
r($convertTest->checkJiraApiTest()) && p() && e('0');
r($convertTest->checkJiraApiTest()) && p() && e('0');
r($convertTest->checkJiraApiTest()) && p() && e('0');
