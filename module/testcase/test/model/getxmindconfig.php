#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getXmindConfig();
cid=1
pid=1

*/

$module = array('module' => 'MM');
$scene  = array('scene' => 'SS');
$case   = array('case' => 'CC');
$pri    = array('pri' => 'PP');
$group  = array('group' => 'GG');

$config1  = array_merge($module, $scene, $case, $pri, $group);
$config2  = array_merge($scene, $case, $pri, $group);
$config3  = array_merge($module, $case, $pri, $group);
$config4  = array_merge($module, $scene, $pri, $group);
$config5  = array_merge($module, $scene, $case, $group);
$config6  = array_merge($module, $scene, $case, $pri);
$config7  = array_merge($module);
$config8  = array_merge($scene);
$config9  = array_merge($case);
$config10 = array_merge($pri);
$config11 = array_merge($group);
$config12 = array();

$testcase = new testcaseTest();

r($testcase->getXmindConfigTest($config1))  && p('module,scene,case,pri,group') && e('MM,SS,CC,PP,GG'); // 测试获取设置了 module scene case pri group 的配置
r($testcase->getXmindConfigTest($config2))  && p('module,scene,case,pri,group') && e('M,SS,CC,PP,GG');  // 测试获取设置了 scene case pri group 的配置
r($testcase->getXmindConfigTest($config3))  && p('module,scene,case,pri,group') && e('MM,S,CC,PP,GG');  // 测试获取设置了 module case pri group 的配置
r($testcase->getXmindConfigTest($config4))  && p('module,scene,case,pri,group') && e('MM,SS,C,PP,GG');  // 测试获取设置了 module scene pri group 的配置
r($testcase->getXmindConfigTest($config5))  && p('module,scene,case,pri,group') && e('MM,SS,CC,P,GG');  // 测试获取设置了 module scene case group 的配置
r($testcase->getXmindConfigTest($config6))  && p('module,scene,case,pri,group') && e('MM,SS,CC,PP,G');  // 测试获取设置了 module scene case pri 的配置
r($testcase->getXmindConfigTest($config7))  && p('module,scene,case,pri,group') && e('MM,S,C,P,G');     // 测试获取设置了 module 的配置
r($testcase->getXmindConfigTest($config8))  && p('module,scene,case,pri,group') && e('M,SS,C,P,G');     // 测试获取设置了 scene 的配置
r($testcase->getXmindConfigTest($config9))  && p('module,scene,case,pri,group') && e('M,S,CC,P,G');     // 测试获取设置了 case 的配置
r($testcase->getXmindConfigTest($config10)) && p('module,scene,case,pri,group') && e('M,S,C,PP,G');     // 测试获取设置了 pri 的配置
r($testcase->getXmindConfigTest($config11)) && p('module,scene,case,pri,group') && e('M,S,C,P,GG');     // 测试获取设置了 group 的配置
