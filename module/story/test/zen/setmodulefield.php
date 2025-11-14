#!/usr/bin/env php
<?php

/**

title=测试 storyZen::setModuleField();
timeout=0
cid=18709

- 测试步骤1：正常字段配置和模块ID第module条的default属性 @2
- 测试步骤2：空模块ID，使用cookie中lastStoryModule第module条的default属性 @1
- 测试步骤3：模块ID不在选项中，设为0第module条的default属性 @0
- 测试步骤4：边界值测试，使用负数模块ID第module条的default属性 @0
- 测试步骤5：模块ID为0，使用字段默认值第module条的default属性 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zenData('module')->loadYaml('module', false, 4)->gen(10);
zenData('product')->loadYaml('product', false, 4)->gen(10);

su('admin');

$storyZenTest = new storyZenTest();

r($storyZenTest->setModuleFieldTest(array('module' => array('default' => 1)), 2)) && p('module:default') && e('2'); // 测试步骤1：正常字段配置和模块ID
r($storyZenTest->setModuleFieldTest(array('module' => array('default' => 1)), 0)) && p('module:default') && e('1'); // 测试步骤2：空模块ID，使用cookie中lastStoryModule
r($storyZenTest->setModuleFieldTest(array('module' => array('default' => 1)), 999)) && p('module:default') && e('0'); // 测试步骤3：模块ID不在选项中，设为0
r($storyZenTest->setModuleFieldTest(array('module' => array('default' => 1)), -1)) && p('module:default') && e('0'); // 测试步骤4：边界值测试，使用负数模块ID
r($storyZenTest->setModuleFieldTest(array('module' => array('default' => 3)), 0)) && p('module:default') && e('3'); // 测试步骤5：模块ID为0，使用字段默认值