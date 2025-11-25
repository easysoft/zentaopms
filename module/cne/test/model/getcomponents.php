#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getComponents();
timeout=0
cid=15616

- 执行cneTest模块的getComponentsTest方法 属性code @600
- 执行cneTest模块的getComponentsTest方法，参数是999  @0
- 执行cneTest模块的getComponentsTest方法 属性code @400
- 执行cneTest模块的getComponentsTest方法，参数是null  @0
- 执行cneTest模块的getComponentsTest方法，参数是2 属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 创建测试实例
$cneTest = new cneTest();

r($cneTest->getComponentsTest()) && p('code') && e('600');
r($cneTest->getComponentsTest(999)) && p() && e('0');
r($cneTest->getComponentsTest(0)) && p('code') && e('400');
r($cneTest->getComponentsTest(null)) && p() && e('0');
r($cneTest->getComponentsTest(2)) && p('code') && e('600');