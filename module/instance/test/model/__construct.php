#!/usr/bin/env php
<?php

/**

title=测试 instanceModel::__construct();
timeout=0
cid=16777

- 执行instanceTest模块的__constructTest方法 
 - 属性cneLoaded @1
 - 属性actionLoaded @1
 - 属性parentCalled @1
- 执行instanceTest模块的__constructTest方法 属性cneLoaded @1
- 执行instanceTest模块的__constructTest方法 属性actionLoaded @1
- 执行instanceTest模块的__constructTest方法 属性parentCalled @1
- 执行instanceTest模块的__constructTest方法  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/instance.unittest.class.php';

su('admin');

$instanceTest = new instanceTest();

r($instanceTest->__constructTest()) && p('cneLoaded,actionLoaded,parentCalled') && e('1,1,1');
r($instanceTest->__constructTest()) && p('cneLoaded') && e('1');
r($instanceTest->__constructTest()) && p('actionLoaded') && e('1');
r($instanceTest->__constructTest()) && p('parentCalled') && e('1');
r(is_object($instanceTest->__constructTest())) && p() && e('1');