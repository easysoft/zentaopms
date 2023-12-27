#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
su('admin');

/**

title=instanceModel->getByID();
timeout=0
cid=1

- 查看获取到的域名前缀的长度 @2
- 查看获取到的域名前缀的长度 @3
- 查看获取到的域名前缀的长度 @4
- 查看获取到的域名前缀的长度 @5

*/

global $tester;
$tester->loadModel('instance');

r(strlen($tester->instance->randThirdDomain(2)))   && p('') && e('2'); // 查看获取到的域名前缀的长度
r(strlen($tester->instance->randThirdDomain(3)))   && p('') && e('3'); // 查看获取到的域名前缀的长度
r(strlen($tester->instance->randThirdDomain(4)))   && p('') && e('4'); // 查看获取到的域名前缀的长度
r(strlen($tester->instance->randThirdDomain(5)))   && p('') && e('5'); // 查看获取到的域名前缀的长度