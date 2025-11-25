#!/usr/bin/env php
<?php

/**

title=测试 userZen::setReferer();
timeout=0
cid=19684

- 执行userZenTest模块的setRefererTest方法，参数是''  @0
- 执行userZenTest模块的setRefererTest方法，参数是''  @0
- 执行userZenTest模块的setRefererTest方法，参数是'module/user/test/zen/user-view.html'  @0
- 执行userZenTest模块的setRefererTest方法，参数是'http://malicious.com/evil'  @0
- 执行userZenTest模块的setRefererTest方法，参数是helper::safe64Encode  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$userZenTest = new userZenTest();

r($userZenTest->setRefererTest('')) && p() && e('0');
r($userZenTest->setRefererTest('')) && p() && e('0');
r($userZenTest->setRefererTest('module/user/test/zen/user-view.html')) && p() && e('0');
r($userZenTest->setRefererTest('http://malicious.com/evil')) && p() && e('0');
r($userZenTest->setRefererTest(helper::safe64Encode('module/user/test/zen/task-browse.html'))) && p() && e('0');