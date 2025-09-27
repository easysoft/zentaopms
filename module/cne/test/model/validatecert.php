#!/usr/bin/env php
<?php

/**

title=测试 cneModel::validateCert();
timeout=0
cid=0

- 步骤1：正常证书验证但API连接失败属性code @600
- 步骤2：证书名为空的情况属性code @600
- 步骤3：PEM证书内容为空的情况属性code @600
- 步骤4：私钥为空的情况属性code @600
- 步骤5：域名为空的情况属性code @600

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$cneTest = new cneTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($cneTest->validateCertTest('test-cert', '-----BEGIN CERTIFICATE-----\ntest\n-----END CERTIFICATE-----', '-----BEGIN PRIVATE KEY-----\ntest\n-----END PRIVATE KEY-----', 'example.com')) && p('code') && e('600'); // 步骤1：正常证书验证但API连接失败
r($cneTest->validateCertTest('', '-----BEGIN CERTIFICATE-----\ntest\n-----END CERTIFICATE-----', '-----BEGIN PRIVATE KEY-----\ntest\n-----END PRIVATE KEY-----', 'example.com')) && p('code') && e('600'); // 步骤2：证书名为空的情况
r($cneTest->validateCertTest('test-cert', '', '-----BEGIN PRIVATE KEY-----\ntest\n-----END PRIVATE KEY-----', 'example.com')) && p('code') && e('600'); // 步骤3：PEM证书内容为空的情况
r($cneTest->validateCertTest('test-cert', '-----BEGIN CERTIFICATE-----\ntest\n-----END CERTIFICATE-----', '', 'example.com')) && p('code') && e('600'); // 步骤4：私钥为空的情况
r($cneTest->validateCertTest('test-cert', '-----BEGIN CERTIFICATE-----\ntest\n-----END CERTIFICATE-----', '-----BEGIN PRIVATE KEY-----\ntest\n-----END PRIVATE KEY-----', '')) && p('code') && e('600'); // 步骤5：域名为空的情况