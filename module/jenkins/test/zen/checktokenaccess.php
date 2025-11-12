#!/usr/bin/env php
<?php

/**

title=测试 jenkinsZen::checkTokenAccess();
timeout=0
cid=0

- 步骤1: 测试空URL情况属性account @Jenkins权限验证失败，请检查账号信息是否正确
- 步骤2: 测试空账号情况属性account @Jenkins权限验证失败，请检查账号信息是否正确
- 步骤3: 测试空密码和空Token情况属性account @Jenkins权限验证失败，请检查账号信息是否正确
- 步骤4: 测试使用密码认证失败情况属性account @Jenkins权限验证失败，请检查账号信息是否正确
- 步骤5: 测试使用Token认证失败情况属性account @Jenkins权限验证失败，请检查账号信息是否正确

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$jenkinsTest = new jenkinsZenTest();

r($jenkinsTest->checkTokenAccessTest('', 'admin', 'password', '')) && p('account') && e('Jenkins权限验证失败，请检查账号信息是否正确'); // 步骤1: 测试空URL情况
r($jenkinsTest->checkTokenAccessTest('http://localhost:8080', '', 'password', '')) && p('account') && e('Jenkins权限验证失败，请检查账号信息是否正确'); // 步骤2: 测试空账号情况
r($jenkinsTest->checkTokenAccessTest('http://localhost:8080', 'admin', '', '')) && p('account') && e('Jenkins权限验证失败，请检查账号信息是否正确'); // 步骤3: 测试空密码和空Token情况
r($jenkinsTest->checkTokenAccessTest('http://invalid-jenkins-server.local', 'admin', 'wrongpassword', '')) && p('account') && e('Jenkins权限验证失败，请检查账号信息是否正确'); // 步骤4: 测试使用密码认证失败情况
r($jenkinsTest->checkTokenAccessTest('http://invalid-jenkins-server.local', 'admin', '', 'wrongtoken123')) && p('account') && e('Jenkins权限验证失败，请检查账号信息是否正确'); // 步骤5: 测试使用Token认证失败情况