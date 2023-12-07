#!/usr/bin/env php
<?php
declare(strict_types=1);
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/personnel.class.php';

/**

title=测试 personnelModel->buildSearchForm();
cid=1
pid=1

*/

$personnel = new personnelTest();

$queryID   = array(0, 1);
$actionURL = array('actionURL1', 'actionURL2');

r($personnel->buildSearchFormTest($queryID[0], $actionURL[0])) && p('queryID,actionURL') && e('0,actionURL1'); // 测试构建queryID 0 actionURL1 的搜索表单
r($personnel->buildSearchFormTest($queryID[0], $actionURL[1])) && p('queryID,actionURL') && e('0,actionURL2'); // 测试构建queryID 0 actionURL2 的搜索表单
r($personnel->buildSearchFormTest($queryID[1], $actionURL[0])) && p('queryID,actionURL') && e('1,actionURL1'); // 测试构建queryID 1 actionURL1 的搜索表单
r($personnel->buildSearchFormTest($queryID[1], $actionURL[1])) && p('queryID,actionURL') && e('1,actionURL2'); // 测试构建queryID 1 actionURL2 的搜索表单
