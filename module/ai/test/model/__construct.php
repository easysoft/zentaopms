#!/usr/bin/env php
<?php

/**

title=测试 AIResponseException::__construct();
timeout=0
cid=14994

- 执行aiTest模块的__constructTest方法，参数是'invalid_format', 'test response'  @1
- 执行aiTest模块的__constructTest方法，参数是'parse_error', array  @1
- 执行aiTest模块的__constructTest方法，参数是'', 'empty type test'  @1
- 执行aiTest模块的__constructTest方法，参数是'unknown_error', 'unknown response'  @1
- 执行aiTest模块的__constructTest方法，参数是'special_chars', 'special characters'  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ai.unittest.class.php';

su('admin');

$aiTest = new aiTest();

r($aiTest->__constructTest('invalid_format', 'test response')) && p() && e('1');
r($aiTest->__constructTest('parse_error', array('error' => 'parsing failed'))) && p() && e('1');
r($aiTest->__constructTest('', 'empty type test')) && p() && e('1');
r($aiTest->__constructTest('unknown_error', 'unknown response')) && p() && e('1');
r($aiTest->__constructTest('special_chars', 'special characters')) && p() && e('1');