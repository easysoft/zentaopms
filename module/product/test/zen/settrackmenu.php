#!/usr/bin/env php
<?php

/**

title=测试 productZen::setTrackMenu();
timeout=0
cid=17618

- 步骤1:测试productID=1,branch=main,projectID=0属性executionSuccess @1
- 步骤2:测试productID=2,branch=dev,projectID=0属性executionSuccess @1
- 步骤3:测试productID=1,branch为空,projectID=0属性executionSuccess @1
- 步骤4:测试productID=1,branch=feature,projectID=0属性executionSuccess @1
- 步骤5:测试productID=3,branch=release,projectID=0属性executionSuccess @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$productTest = new productZenTest();

r($productTest->setTrackMenuTest(1, 'main', 0)) && p('executionSuccess') && e('1'); // 步骤1:测试productID=1,branch=main,projectID=0
r($productTest->setTrackMenuTest(2, 'dev', 0)) && p('executionSuccess') && e('1'); // 步骤2:测试productID=2,branch=dev,projectID=0
r($productTest->setTrackMenuTest(1, '', 0)) && p('executionSuccess') && e('1'); // 步骤3:测试productID=1,branch为空,projectID=0
r($productTest->setTrackMenuTest(1, 'feature', 0)) && p('executionSuccess') && e('1'); // 步骤4:测试productID=1,branch=feature,projectID=0
r($productTest->setTrackMenuTest(3, 'release', 0)) && p('executionSuccess') && e('1'); // 步骤5:测试productID=3,branch=release,projectID=0