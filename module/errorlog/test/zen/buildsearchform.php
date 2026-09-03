#!/usr/bin/env php
<?php

/**

title=测试 errorlogZen::buildSearchForm();
timeout=0
cid=0

- 构建搜索表单后session中的module为errorlog @errorlog
- session中的queryID为1 @1
- session中的actionURL正确 @http://example.com/errorlog
- module参数值被替换为错误日志模块列表 @bug,task
- 再次构建后session参数被更新 @http://example.com/errorlog/2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

$errorlog = zenData('errorlog');
$errorlog->requestID->range('req-001{2},req-002{3}');
$errorlog->module->range('bug{3},task{2}');
$errorlog->gen(5);

su('admin');

helper::import($tester->app->getModulePath('', 'errorlog') . 'control.php');

$errorlogZen = new errorlogZenTest();
$searchConfig = array(
    'module'    => 'errorlog',
    'fields'    => array('requestID' => '请求ID', 'module' => '模块'),
    'params'    => array('module' => array('values' => array('all' => '全部')), 'level' => array('values' => array())),
);

$params1 = $errorlogZen->buildSearchFormTest($searchConfig, 1, 'http://example.com/errorlog');
r($params1) && p('module') && e('errorlog'); // 构建搜索表单后session中的module
r($params1) && p('queryID') && e('1'); // session中的queryID
r($params1) && p('actionURL') && e('http://example.com/errorlog'); // session中的actionURL
r($params1['params']['module']['values']) && p('bug,task') && e('bug,task'); // module参数值替换为错误日志模块列表

$params2 = $errorlogZen->buildSearchFormTest($searchConfig, 2, 'http://example.com/errorlog/2');
r($params2) && p('actionURL') && e('http://example.com/errorlog/2'); // 再次构建后session参数被更新
