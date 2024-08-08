#!/usr/bin/env php
<?php

/**

title=测试 zahostModel->ping();
timeout=0
cid=1

- 测试不是正常地址得到的结果 @no
- 测试网络不通的地址得到的结果 @no

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zahost.unittest.class.php';
su('admin');

$addressList = array('a', '10.0.0.222');

$zahost = new zahostTest();
r($zahost->pingTest($addressList[0])) && p() && e('no');  //测试不是正常地址得到的结果
r($zahost->pingTest($addressList[1])) && p() && e('no');  //测试网络不通的地址得到的结果
