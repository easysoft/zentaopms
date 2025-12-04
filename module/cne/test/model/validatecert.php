#!/usr/bin/env php
<?php

/**

title=测试 cneModel::validateCert();
timeout=0
cid=0

- 步骤1:传入完整的证书参数和domain参数属性code @600
- 步骤2:传入空的证书名称属性code @600
- 步骤3:传入空的certificate_pem属性code @600
- 步骤4:传入空的private_key_pem属性code @600
- 步骤5:传入空的domain属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

r($cneTest->validateCertTest('test-cert-1', '-----BEGIN CERTIFICATE-----test-content-1-----END CERTIFICATE-----', '-----BEGIN PRIVATE KEY-----test-key-1-----END PRIVATE KEY-----', 'example.com')) && p('code') && e('600'); // 步骤1:传入完整的证书参数和domain参数
r($cneTest->validateCertTest('', '-----BEGIN CERTIFICATE-----test-content-2-----END CERTIFICATE-----', '-----BEGIN PRIVATE KEY-----test-key-2-----END PRIVATE KEY-----', 'example.com')) && p('code') && e('600'); // 步骤2:传入空的证书名称
r($cneTest->validateCertTest('test-cert-3', '', '-----BEGIN PRIVATE KEY-----test-key-3-----END PRIVATE KEY-----', 'example.com')) && p('code') && e('600'); // 步骤3:传入空的certificate_pem
r($cneTest->validateCertTest('test-cert-4', '-----BEGIN CERTIFICATE-----test-content-4-----END CERTIFICATE-----', '', 'example.com')) && p('code') && e('600'); // 步骤4:传入空的private_key_pem
r($cneTest->validateCertTest('test-cert-5', '-----BEGIN CERTIFICATE-----test-content-5-----END CERTIFICATE-----', '-----BEGIN PRIVATE KEY-----test-key-5-----END PRIVATE KEY-----', '')) && p('code') && e('600'); // 步骤5:传入空的domain