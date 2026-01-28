#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::buildCreateView();
timeout=0
cid=17787

- 执行programplanTest模块的buildCreateViewTest方法，参数是1, 'normal' 属性success @1
- 执行programplanTest模块的buildCreateViewTest方法，参数是2, 'ipd' 属性success @1
- 执行programplanTest模块的buildCreateViewTest方法，参数是3, 'withProduct' 属性success @1
- 执行programplanTest模块的buildCreateViewTest方法，参数是4, 'withPlan' 属性success @1
- 执行programplanTest模块的buildCreateViewTest方法，参数是5, 'stageType' 属性success @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

zenData('project');
zenData('product');
zenData('user');

su('admin');

$programplanTest = new programplanZenTest();

r($programplanTest->buildCreateViewTest(1, 'normal')) && p('success') && e('1');
r($programplanTest->buildCreateViewTest(2, 'ipd')) && p('success') && e('1');
r($programplanTest->buildCreateViewTest(3, 'withProduct')) && p('success') && e('1');
r($programplanTest->buildCreateViewTest(4, 'withPlan')) && p('success') && e('1');
r($programplanTest->buildCreateViewTest(5, 'stageType')) && p('success') && e('1');
