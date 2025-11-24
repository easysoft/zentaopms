#!/usr/bin/env php
<?php

/**

title=测试 commonModel::printDuration();
timeout=0
cid=0

- 执行commonTest模块的printDurationTest方法，参数是40000000, 'y-m-d-h-i-s'  @1年3月7天23小时6分40秒
- 执行commonTest模块的printDurationTest方法，参数是3661, 'y-m-d-h-i-s'  @1小时1分1秒
- 执行commonTest模块的printDurationTest方法，参数是3661, ''  @0
- 执行commonTest模块的printDurationTest方法，参数是0, 'y-m-d-h-i-s'  @0
- 执行commonTest模块的printDurationTest方法，参数是90061, 'y-m-d-h-i-s'  @1天1小时1分1秒
- 执行commonTest模块的printDurationTest方法，参数是100000000, 'y-m-d-h-i-s'  @3年2月2天9小时46分40秒
- 执行commonTest模块的printDurationTest方法，参数是40000000, 'y-m'  @1年3月

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$commonTest = new commonModelTest();

r($commonTest->printDurationTest(40000000, 'y-m-d-h-i-s')) && p() && e('1年3月7天23小时6分40秒');
r($commonTest->printDurationTest(3661, 'y-m-d-h-i-s')) && p() && e('1小时1分1秒');
r($commonTest->printDurationTest(3661, '')) && p() && e('0');
r($commonTest->printDurationTest(0, 'y-m-d-h-i-s')) && p() && e('0');
r($commonTest->printDurationTest(90061, 'y-m-d-h-i-s')) && p() && e('1天1小时1分1秒');
r($commonTest->printDurationTest(100000000, 'y-m-d-h-i-s')) && p() && e('3年2月2天9小时46分40秒');
r($commonTest->printDurationTest(40000000, 'y-m')) && p() && e('1年3月');