#!/usr/bin/env php
<?php

/**

title=测试 cneModel::validateCert();
timeout=0
cid=15637

- 测试步骤1:所有参数都为空的情况属性code @600
- 测试步骤2:证书名称为空的情况属性code @600
- 测试步骤3:PEM证书内容为空的情况属性code @600
- 测试步骤4:私钥内容为空的情况属性code @600
- 测试步骤5:域名参数为空的情况属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

r($cneTest->validateCertTest('', '', '', '')) && p('code') && e('600'); // 测试步骤1:所有参数都为空的情况
r($cneTest->validateCertTest('', '-----BEGIN CERTIFICATE-----\ntest-cert\n-----END CERTIFICATE-----', '-----BEGIN PRIVATE KEY-----\ntest-key\n-----END PRIVATE KEY-----', 'test.example.com')) && p('code') && e('600'); // 测试步骤2:证书名称为空的情况
r($cneTest->validateCertTest('test-cert', '', '-----BEGIN PRIVATE KEY-----\ntest-key\n-----END PRIVATE KEY-----', 'test.example.com')) && p('code') && e('600'); // 测试步骤3:PEM证书内容为空的情况
r($cneTest->validateCertTest('test-cert', '-----BEGIN CERTIFICATE-----\ntest-cert\n-----END CERTIFICATE-----', '', 'test.example.com')) && p('code') && e('600'); // 测试步骤4:私钥内容为空的情况
r($cneTest->validateCertTest('test-cert', '-----BEGIN CERTIFICATE-----\ntest-cert\n-----END CERTIFICATE-----', '-----BEGIN PRIVATE KEY-----\ntest-key\n-----END PRIVATE KEY-----', '')) && p('code') && e('600'); // 测试步骤5:域名参数为空的情况