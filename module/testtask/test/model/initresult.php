#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::initResult();
timeout=0
cid=19204

- 执行testtaskTest模块的initResultTest方法，参数是$now
 - 属性case @0
 - 属性version @1
 - 属性caseResult @pass
- 执行testtaskTest模块的initResultTest方法，参数是$fixedTime 属性date @2023-12-25 10:30:00
- 执行testtaskTest模块的initResultTest方法，参数是$now 属性case @0
- 执行testtaskTest模块的initResultTest方法，参数是$now 属性version @1
- 执行testtaskTest模块的initResultTest方法，参数是$now 属性caseResult @pass

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtask.unittest.class.php';

su('admin');

$testtaskTest = new testtaskTest();

$now = helper::now();
$fixedTime = '2023-12-25 10:30:00';

r($testtaskTest->initResultTest($now)) && p('case,version,caseResult') && e('0,1,pass');
r($testtaskTest->initResultTest($fixedTime)) && p('date') && e('2023-12-25 10:30:00');
r($testtaskTest->initResultTest($now)) && p('case') && e('0');
r($testtaskTest->initResultTest($now)) && p('version') && e('1');
r($testtaskTest->initResultTest($now)) && p('caseResult') && e('pass');