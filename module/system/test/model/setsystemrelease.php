#!/usr/bin/env php
<?php

/**

title=测试 systemModel::setSystemRelease();
timeout=0
cid=18747

- 执行system模块的setSystemRelease方法，参数是1, 2, '2024-01-15 10:00:00') ? '1' : '0  @1
- 执行system模块的setSystemRelease方法，参数是999, 1, '2024-01-15 10:00:00') ? '1' : '0  @0
- 执行system模块的setSystemRelease方法，参数是1, 1, '') ? '1' : '0  @0
- 执行system模块的setSystemRelease方法，参数是2, 0, '') ? '1' : '0  @1
- 执行system模块的setSystemRelease方法，参数是3, 5, '2024-02-20 15:30:00') ? '1' : '0  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';

zenData('system')->gen(5);
zenData('release')->gen(10);

su('admin');

global $tester;
$system = $tester->loadModel('system');

r($system->setSystemRelease(1, 2, '2024-01-15 10:00:00') ? '1' : '0') && p() && e('1');
r($system->setSystemRelease(999, 1, '2024-01-15 10:00:00') ? '1' : '0') && p() && e('0');
r($system->setSystemRelease(1, 1, '') ? '1' : '0') && p() && e('0');
r($system->setSystemRelease(2, 0, '') ? '1' : '0') && p() && e('1');
r($system->setSystemRelease(3, 5, '2024-02-20 15:30:00') ? '1' : '0') && p() && e('1');