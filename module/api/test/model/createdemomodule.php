#!/usr/bin/env php
<?php

/**

title=测试 apiModel::createDemoModule();
timeout=0
cid=15095

- 执行apiTest模块的createDemoModuleTest方法，参数是1, '16.0' 属性2964 @1
- 执行apiTest模块的createDemoModuleTest方法，参数是2, '16.0' 属性2963 @18
- 执行apiTest模块的createDemoModuleTest方法，参数是3, '16.0' 属性2962 @35
- 执行apiTest模块的createDemoModuleTest方法，参数是4, '16.0' 属性2961 @52
- 执行apiTest模块的createDemoModuleTest方法，参数是5, '16.0' 属性2960 @69

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('doclib')->loadYaml('doclib', false, 2)->gen(10);
zenData('module')->gen(0);

su('admin');

$apiTest = new apiModelTest();

r($apiTest->createDemoModuleTest(1, '16.0')) && p('2964') && e('1');
r($apiTest->createDemoModuleTest(2, '16.0')) && p('2963') && e('18');
r($apiTest->createDemoModuleTest(3, '16.0')) && p('2962') && e('35');
r($apiTest->createDemoModuleTest(4, '16.0')) && p('2961') && e('52');
r($apiTest->createDemoModuleTest(5, '16.0')) && p('2960') && e('69');