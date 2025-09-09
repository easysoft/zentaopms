#!/usr/bin/env php
<?php

/**

title=测试 searchModel::getOldQuery();
timeout=0
cid=0

- 执行searchTest模块的getOldQueryTest方法，参数是1 
 - 属性id @1
 - 属性account @admin
- 执行searchTest模块的getOldQueryTest方法，参数是999  @0
- 执行searchTest模块的getOldQueryTest方法，参数是3 属性id @3
- 执行searchTest模块的getOldQueryTest方法，参数是2 
 - 属性id @2
 - 属性account @admin
- 执行searchTest模块的getOldQueryTest方法  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

zenData('userquery')->loadYaml('userquery_getoldquery', false, 2)->gen(10);

su('admin');

$searchTest = new searchTest();

r($searchTest->getOldQueryTest(1)) && p('id,account') && e('1,admin');
r($searchTest->getOldQueryTest(999)) && p() && e('0');
r($searchTest->getOldQueryTest(3)) && p('id') && e('3');
r($searchTest->getOldQueryTest(2)) && p('id,account') && e('2,admin');
r($searchTest->getOldQueryTest(0)) && p() && e('0');