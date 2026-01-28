#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::formatZtfLog();
timeout=0
cid=19162

- 执行testtaskTest模块的formatZtfLogTest方法，参数是'', array  @0
- 执行testtaskTest模块的formatZtfLogTest方法，参数是'{"test": "value"}', array  @0
- 执行testtaskTest模块的formatZtfLogTest方法，参数是'{"log": "Test log line 1\nTest log line 2"}', array  @<li>Test log line 1</li><li>Test log line 2</li>
- 执行testtaskTest模块的formatZtfLogTest方法，参数是'{"log": "Test case: 失败\nTest case: pass"}', array  @<li>Test case: <span class='result-testcase fail'>失败</span></li><li>Test case: <span class='result-testcase pass'>通过</span></li>
- 执行testtaskTest模块的formatZtfLogTest方法，参数是'{"log": "Test log"}', array  @<li>Test log</li><li class='result-testcase pass'>共有3个步骤，2个通过，1个失败。</li>

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$testtaskTest = new testtaskModelTest();

r($testtaskTest->formatZtfLogTest('', array())) && p() && e('0');
r($testtaskTest->formatZtfLogTest('{"test": "value"}', array())) && p() && e('0');
r($testtaskTest->formatZtfLogTest('{"log": "Test log line 1\nTest log line 2"}', array())) && p() && e('<li>Test log line 1</li><li>Test log line 2</li>');
r($testtaskTest->formatZtfLogTest('{"log": "Test case: 失败\nTest case: pass"}', array())) && p() && e("<li>Test case: <span class='result-testcase fail'>失败</span></li><li>Test case: <span class='result-testcase pass'>通过</span></li>");
r($testtaskTest->formatZtfLogTest('{"log": "Test log"}', array(array('result' => 'pass'), array('result' => 'fail'), array('result' => 'pass')))) && p() && e("<li>Test log</li><li class='result-testcase pass'>共有3个步骤，2个通过，1个失败。</li>");