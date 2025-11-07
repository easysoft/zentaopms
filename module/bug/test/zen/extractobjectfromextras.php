#!/usr/bin/env php
<?php

/**

title=测试 bugZen::extractObjectFromExtras();
timeout=0
cid=0

- 执行bugTest模块的extractObjectFromExtrasTest方法，参数是clone $baseBug, array
 - 属性title @Original Bug Title
 - 属性steps @Original Bug Steps
 - 属性pri @3
- 执行bugTest模块的extractObjectFromExtrasTest方法，参数是clone $baseBug, array
 - 属性title @BUG1
 - 属性project @1
 - 属性execution @101
- 执行bugTest模块的extractObjectFromExtrasTest方法，参数是clone $baseBug, array 属性buildID @11
- 执行bugTest模块的extractObjectFromExtrasTest方法，参数是clone $baseBug, array
 - 属性title @自定义1的待办
 - 属性steps @这是一个待办的描述1
 - 属性pri @1
- 执行bugTest模块的extractObjectFromExtrasTest方法，参数是clone $baseBug, array
 - 属性title @BUG1
 - 属性steps @这是一个待办的描述2
 - 属性pri @2
- 执行bugTest模块的extractObjectFromExtrasTest方法，参数是clone $baseBug, array
 - 属性title @BUG2
 - 属性buildID @12
- 执行bugTest模块的extractObjectFromExtrasTest方法，参数是clone $baseBug, array
 - 属性title @开发任务12
 - 属性steps @这是一个待办的描述3
 - 属性pri @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('bug')->loadYaml('extractobjectfromextras/bug', false, 2)->gen(10);
zenData('case')->loadYaml('extractobjectfromextras/case', false, 2)->gen(10);
zenData('todo')->loadYaml('extractobjectfromextras/todo', false, 2)->gen(10);
zenData('testtask')->loadYaml('extractobjectfromextras/testtask', false, 2)->gen(10);
zenData('testresult')->loadYaml('extractobjectfromextras/testresult', false, 2)->gen(10);
zenData('testrun')->loadYaml('extractobjectfromextras/testrun', false, 2)->gen(10);
zenData('product')->gen(5);
zenData('user')->gen(10);
zenData('file')->gen(0);

su('admin');

$bugTest = new bugZenTest();

$baseBug = new stdClass();
$baseBug->product    = 1;
$baseBug->project    = 1;
$baseBug->execution  = 101;
$baseBug->module     = 1;
$baseBug->title      = 'Original Bug Title';
$baseBug->steps      = 'Original Bug Steps';
$baseBug->pri        = 3;
$baseBug->severity   = 3;
$baseBug->type       = 'codeerror';
$baseBug->assignedTo = 'admin';
$baseBug->deadline   = '';
$baseBug->os         = '';
$baseBug->browser    = '';
$baseBug->buildID    = '';

r($bugTest->extractObjectFromExtrasTest(clone $baseBug, array())) && p('title,steps,pri') && e('Original Bug Title,Original Bug Steps,3');
r($bugTest->extractObjectFromExtrasTest(clone $baseBug, array('bugID' => 1))) && p('title,project,execution') && e('BUG1,1,101');
r($bugTest->extractObjectFromExtrasTest(clone $baseBug, array('testtask' => 1))) && p('buildID') && e('11');
r($bugTest->extractObjectFromExtrasTest(clone $baseBug, array('todoID' => 1))) && p('title,steps,pri') && e('自定义1的待办,这是一个待办的描述1,1');
r($bugTest->extractObjectFromExtrasTest(clone $baseBug, array('todoID' => 2))) && p('title,steps,pri') && e('BUG1,这是一个待办的描述2,2');
r($bugTest->extractObjectFromExtrasTest(clone $baseBug, array('bugID' => 2, 'testtask' => 2))) && p('title,buildID') && e('BUG2,12');
r($bugTest->extractObjectFromExtrasTest(clone $baseBug, array('todoID' => 3))) && p('title,steps,pri') && e('开发任务12,这是一个待办的描述3,3');