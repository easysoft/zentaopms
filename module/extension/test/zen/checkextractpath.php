#!/usr/bin/env php
<?php

/**

title=测试 extensionZen::checkExtractPath();
timeout=0
cid=16479

- 执行extensionTest模块的checkExtractPathTest方法，参数是'', $checkResult1 属性result @ok
- 执行extensionTest模块的checkExtractPathTest方法，参数是'nonexistent_plugin', $checkResult2 属性result @ok
- 执行extensionTest模块的checkExtractPathTest方法，参数是'test_plugin', $checkResult3 属性result @ok
- 执行extensionTest模块的checkExtractPathTest方法，参数是'test_plugin', $checkResult4 属性errors @Previous error<br />
- 执行checkExtractPathTest('test_plugin', $checkResult5)模块的dirs2Created方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

global $tester, $app, $config;
$app->rawModule = 'extension';
$app->rawMethod = 'browse';

$extensionTest = new extensionZenTest();

/* 准备测试用的checkResult对象 */
$checkResult1 = new stdclass();
$checkResult1->result        = 'ok';
$checkResult1->errors        = '';
$checkResult1->mkdirCommands = '';
$checkResult1->chmodCommands = '';
$checkResult1->dirs2Created  = array();

$checkResult2 = new stdclass();
$checkResult2->result        = 'ok';
$checkResult2->errors        = '';
$checkResult2->mkdirCommands = '';
$checkResult2->chmodCommands = '';
$checkResult2->dirs2Created  = array();

$checkResult3 = new stdclass();
$checkResult3->result        = 'ok';
$checkResult3->errors        = '';
$checkResult3->mkdirCommands = '';
$checkResult3->chmodCommands = '';
$checkResult3->dirs2Created  = array();

$checkResult4 = new stdclass();
$checkResult4->result        = 'ok';
$checkResult4->errors        = 'Previous error<br />';
$checkResult4->mkdirCommands = '';
$checkResult4->chmodCommands = '';
$checkResult4->dirs2Created  = array();

$checkResult5 = new stdclass();
$checkResult5->result        = 'ok';
$checkResult5->errors        = '';
$checkResult5->mkdirCommands = '';
$checkResult5->chmodCommands = '';
$checkResult5->dirs2Created  = array('test_dir1', 'test_dir2');

r($extensionTest->checkExtractPathTest('', $checkResult1)) && p('result') && e('ok');
r($extensionTest->checkExtractPathTest('nonexistent_plugin', $checkResult2)) && p('result') && e('ok');
r($extensionTest->checkExtractPathTest('test_plugin', $checkResult3)) && p('result') && e('ok');
r($extensionTest->checkExtractPathTest('test_plugin', $checkResult4)) && p('errors') && e('Previous error<br />');
r(is_array($extensionTest->checkExtractPathTest('test_plugin', $checkResult5)->dirs2Created)) && p() && e(1);