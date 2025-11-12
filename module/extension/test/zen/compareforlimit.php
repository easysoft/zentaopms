#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::compareForLimit();
timeout=0
cid=0

- 执行extensionTest模块的compareForLimitTest方法，参数是'1.5.0', '', 'between'  @1
- 执行extensionTest模块的compareForLimitTest方法，参数是'2.0.0', 'all', 'between'  @1
- 执行extensionTest模块的compareForLimitTest方法，参数是'1.5.0', array  @1
- 执行extensionTest模块的compareForLimitTest方法，参数是'1.0.0', array  @1
- 执行extensionTest模块的compareForLimitTest方法，参数是'2.0.0', array  @1
- 执行extensionTest模块的compareForLimitTest方法，参数是'0.9.0', array  @1
- 执行extensionTest模块的compareForLimitTest方法，参数是'2.1.0', array  @0
- 执行extensionTest模块的compareForLimitTest方法，参数是'1.5.0', array  @1
- 执行extensionTest模块的compareForLimitTest方法，参数是'0.5.0', array  @0
- 执行extensionTest模块的compareForLimitTest方法，参数是'1.5.0', array  @1
- 执行extensionTest模块的compareForLimitTest方法，参数是'2.5.0', array  @0
- 执行extensionTest模块的compareForLimitTest方法，参数是'1.5.0', array  @0
- 执行extensionTest模块的compareForLimitTest方法，参数是'0.5.0', array  @0
- 执行extensionTest模块的compareForLimitTest方法，参数是'2.5.0', array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester;
$extensionTest = new extensionZenTest();

r($extensionTest->compareForLimitTest('1.5.0', '', 'between')) && p() && e('1');
r($extensionTest->compareForLimitTest('2.0.0', 'all', 'between')) && p() && e('1');
r($extensionTest->compareForLimitTest('1.5.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'between')) && p() && e('1');
r($extensionTest->compareForLimitTest('1.0.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'between')) && p() && e('1');
r($extensionTest->compareForLimitTest('2.0.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'between')) && p() && e('1');
r($extensionTest->compareForLimitTest('0.9.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'between')) && p() && e('1');
r($extensionTest->compareForLimitTest('2.1.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'between')) && p() && e('0');
r($extensionTest->compareForLimitTest('1.5.0', array('min' => '1.0.0'), 'between')) && p() && e('1');
r($extensionTest->compareForLimitTest('0.5.0', array('min' => '1.0.0'), 'between')) && p() && e('0');
r($extensionTest->compareForLimitTest('1.5.0', array('max' => '2.0.0'), 'between')) && p() && e('1');
r($extensionTest->compareForLimitTest('2.5.0', array('max' => '2.0.0'), 'between')) && p() && e('0');
r($extensionTest->compareForLimitTest('1.5.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'noBetween')) && p() && e('0');
r($extensionTest->compareForLimitTest('0.5.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'noBetween')) && p() && e('0');
r($extensionTest->compareForLimitTest('2.5.0', array('min' => '1.0.0', 'max' => '2.0.0'), 'noBetween')) && p() && e('1');