#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

/**

title=测试 testcaseModel->saveMindConfig();
cid=19020

- 测试存储 xmind 导出的配置 module scene @module:M,scene:S

- 测试存储 xmind 导出的配置 scene case @scene:S,case:C

- 测试存储 xmind 导出的配置 case pri @case:C,pri:P

- 测试存储 xmind 导出的配置 pri group @pri:P,group:G

- 测试存储 xmind 导出的配置 group pri @group:G,module:M

- 测试存储 freemind 导出的配置 module scene @module:M,scene:S

- 测试存储 freemind 导出的配置 scene case @scene:S,case:C

- 测试存储 freemind 导出的配置 case pri @case:C,pri:P

- 测试存储 freemind 导出的配置 pri group @pri:P,group:G

- 测试存储 freemind 导出的配置 group pri @group:G,module:M

*/

$type   = array('xmind', 'freemind');
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
r($testcase->saveMindConfigTest($type[0], $configList1)) && p() && e('module:M,scene:S'); // 测试存储 xmind 导出的配置 module scene
r($testcase->saveMindConfigTest($type[0], $configList2)) && p() && e('scene:S,case:C');   // 测试存储 xmind 导出的配置 scene case
r($testcase->saveMindConfigTest($type[0], $configList3)) && p() && e('case:C,pri:P');     // 测试存储 xmind 导出的配置 case pri
r($testcase->saveMindConfigTest($type[0], $configList4)) && p() && e('pri:P,group:G');    // 测试存储 xmind 导出的配置 pri group
r($testcase->saveMindConfigTest($type[0], $configList5)) && p() && e('group:G,module:M'); // 测试存储 xmind 导出的配置 group pri
r($testcase->saveMindConfigTest($type[1], $configList1)) && p() && e('module:M,scene:S'); // 测试存储 freemind 导出的配置 module scene
r($testcase->saveMindConfigTest($type[1], $configList2)) && p() && e('scene:S,case:C');   // 测试存储 freemind 导出的配置 scene case
r($testcase->saveMindConfigTest($type[1], $configList3)) && p() && e('case:C,pri:P');     // 测试存储 freemind 导出的配置 case pri
r($testcase->saveMindConfigTest($type[1], $configList4)) && p() && e('pri:P,group:G');    // 测试存储 freemind 导出的配置 pri group
r($testcase->saveMindConfigTest($type[1], $configList5)) && p() && e('group:G,module:M'); // 测试存储 freemind 导出的配置 group pri
