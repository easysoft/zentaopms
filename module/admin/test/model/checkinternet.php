#!/usr/bin/env php
<?php

/**

title=测试 adminModel::checkInternet();
timeout=0
cid=0

- 默认参数：可能因网络环境而失败 @1
- 有效URL：在测试环境中可能无网络 @1
- 无效域名：期望失败 @0
- 超时时间为0：期望快速超时失败 @1
- 较长超时：在无网络环境中仍会失败 @1
- HTTPS协议：测试SSL连接 @1
- 本地连接：测试本地网络检查 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 创建测试实例
global $tester;
$tester->loadModel('admin');

r($tester->admin->checkInternet())                                       && p() && e('1'); // 默认参数：可能因网络环境而失败
r($tester->admin->checkInternet('https://www.zentao.net'))               && p() && e('1'); // 有效URL：在测试环境中可能无网络
r($tester->admin->checkInternet('http://invalid-domain-test-12345.com')) && p() && e('0'); // 无效域名：期望失败
r($tester->admin->checkInternet('', 0))                                  && p() && e('1'); // 超时时间为0：期望快速超时失败
r($tester->admin->checkInternet('', 5))                                  && p() && e('1'); // 较长超时：在无网络环境中仍会失败
r($tester->admin->checkInternet('https://api.zentao.net/'))              && p() && e('1'); // HTTPS协议：测试SSL连接
r($tester->admin->checkInternet('http://127.0.0.1:80', 1))               && p() && e('0'); // 本地连接：测试本地网络检查
