#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->getMindConfig();
cid=18991

- 测试获取设置了 module scene case pri group 的配置
 - 属性module @MM
 - 属性scene @SS
 - 属性case @CC
 - 属性pri @PP
 - 属性group @GG
- 测试获取设置了 scene case pri group 的配置
 - 属性module @M
 - 属性scene @SS
 - 属性case @CC
 - 属性pri @PP
 - 属性group @GG
- 测试获取设置了 module case pri group 的配置
 - 属性module @MM
 - 属性scene @S
 - 属性case @CC
 - 属性pri @PP
 - 属性group @GG
- 测试获取设置了 module scene pri group 的配置
 - 属性module @MM
 - 属性scene @SS
 - 属性case @C
 - 属性pri @PP
 - 属性group @GG
- 测试获取设置了 module scene case group 的配置
 - 属性module @MM
 - 属性scene @SS
 - 属性case @CC
 - 属性pri @P
 - 属性group @GG
- 测试获取设置了 module scene case pri 的配置
 - 属性module @MM
 - 属性scene @SS
 - 属性case @CC
 - 属性pri @PP
 - 属性group @G
- 测试获取设置了 module 的配置
 - 属性module @MM
 - 属性scene @S
 - 属性case @C
 - 属性pri @P
 - 属性group @G
- 测试获取设置了 scene 的配置
 - 属性module @M
 - 属性scene @SS
 - 属性case @C
 - 属性pri @P
 - 属性group @G
- 测试获取设置了 case 的配置
 - 属性module @M
 - 属性scene @S
 - 属性case @CC
 - 属性pri @P
 - 属性group @G
- 测试获取设置了 pri 的配置
 - 属性module @M
 - 属性scene @S
 - 属性case @C
 - 属性pri @PP
 - 属性group @G
- 测试获取设置了 group 的配置
 - 属性module @M
 - 属性scene @S
 - 属性case @C
 - 属性pri @P
 - 属性group @GG

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

$type = array('xmind', 'freemind');

$testcase = new testcaseModelTest();

r($testcase->getMindConfigTest($type[0], $config1))  && p('module,scene,case,pri,group') && e('MM,SS,CC,PP,GG'); // 测试获取设置了 module scene case pri group 的配置
r($testcase->getMindConfigTest($type[0], $config2))  && p('module,scene,case,pri,group') && e('M,SS,CC,PP,GG');  // 测试获取设置了 scene case pri group 的配置
r($testcase->getMindConfigTest($type[0], $config3))  && p('module,scene,case,pri,group') && e('MM,S,CC,PP,GG');  // 测试获取设置了 module case pri group 的配置
r($testcase->getMindConfigTest($type[0], $config4))  && p('module,scene,case,pri,group') && e('MM,SS,C,PP,GG');  // 测试获取设置了 module scene pri group 的配置
r($testcase->getMindConfigTest($type[0], $config5))  && p('module,scene,case,pri,group') && e('MM,SS,CC,P,GG');  // 测试获取设置了 module scene case group 的配置
r($testcase->getMindConfigTest($type[0], $config6))  && p('module,scene,case,pri,group') && e('MM,SS,CC,PP,G');  // 测试获取设置了 module scene case pri 的配置
r($testcase->getMindConfigTest($type[1], $config7))  && p('module,scene,case,pri,group') && e('MM,S,C,P,G');     // 测试获取设置了 module 的配置
r($testcase->getMindConfigTest($type[1], $config8))  && p('module,scene,case,pri,group') && e('M,SS,C,P,G');     // 测试获取设置了 scene 的配置
r($testcase->getMindConfigTest($type[1], $config9))  && p('module,scene,case,pri,group') && e('M,S,CC,P,G');     // 测试获取设置了 case 的配置
r($testcase->getMindConfigTest($type[1], $config10)) && p('module,scene,case,pri,group') && e('M,S,C,PP,G');     // 测试获取设置了 pri 的配置
r($testcase->getMindConfigTest($type[1], $config11)) && p('module,scene,case,pri,group') && e('M,S,C,P,GG');     // 测试获取设置了 group 的配置
