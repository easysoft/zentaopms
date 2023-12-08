#!/usr/bin/env php
<?php
/**

title=测试 adminModel->getApiConfig();
timeout=0
cid=1

- 测试获取禅道官网配置信息是否成功 @Success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/admin.class.php';

zdTable('user')->gen(5);
su('admin');

$admin = new adminTest();
r($admin->getApiConfigTest()) && p() && e('Success'); //测试获取禅道官网配置信息是否成功
