#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

/**

title=测试 testcaseModel->saveXmindConfig();
cid=1
pid=1


*/

$module = array('key' => 'module', 'value' => 'M');
$scene  = array('key' => 'scene', 'value' => 'S');
$case   = array('key' => 'case', 'value' => 'C');
$pri    = array('key' => 'pri', 'value' => 'P');
$group  = array('key' => 'group', 'value' => 'G');

$configList1 = array($module, $scene);
$configList2 = array($scene, $case);
$configList3 = array($case, $pri);
$configList4 = array($pri, $group);
$configList5 = array($group, $module);

$testcase = new testCaseTest();
r($testcase->saveXmindConfigTest($configList1)) && p() && e('module:M,scene:S'); // 测试存储 xmind 导出的配置 module scene
r($testcase->saveXmindConfigTest($configList2)) && p() && e('scene:S,case:C'); // 测试存储 xmind 导出的配置 scene case
r($testcase->saveXmindConfigTest($configList3)) && p() && e('case:C,pri:P'); // 测试存储 xmind 导出的配置 case pri
r($testcase->saveXmindConfigTest($configList4)) && p() && e('pri:P,group:G'); // 测试存储 xmind 导出的配置 pri group
r($testcase->saveXmindConfigTest($configList5)) && p() && e('group:G,module:M'); // 测试存储 xmind 导出的配置 group pri
