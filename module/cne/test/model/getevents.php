#!/usr/bin/env php
<?php

/**

title=测试 cneModel::getEvents();
timeout=0
cid=15620

- 执行cneTest模块的getEventsTest方法，参数是1, ''  @200
- 执行cneTest模块的getEventsTest方法，参数是2, 'mysql'  @200
- 执行cneTest模块的getEventsTest方法，参数是3, ''  @0
- 执行cneTest模块的getEventsTest方法，参数是4, ''  @600
- 执行cneTest模块的getEventsTest方法，参数是0, ''  @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->getEventsTest(1, '')) && p() && e('200');
r($cneTest->getEventsTest(2, 'mysql')) && p() && e('200');
r($cneTest->getEventsTest(3, '')) && p() && e('0');
r($cneTest->getEventsTest(4, '')) && p() && e('600');
r($cneTest->getEventsTest(0, '')) && p() && e('600');