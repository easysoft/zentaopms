#!/usr/bin/env php
<?php

/**

title=测试 cneModel::uploadCert();
timeout=0
cid=0

- 步骤1:传入完整的证书对象和channel参数属性code @600
- 步骤2:使用默认channel,cert对象完整属性code @600
- 步骤3:证书对象name字段为空字符串属性code @600
- 步骤4:证书对象certificate_pem字段为空字符串属性code @600
- 步骤5:证书对象private_key_pem字段为空字符串属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$cneTest = new cneModelTest();

// 创建测试证书对象
$cert1 = new stdClass();
$cert1->name = 'test-cert-1';
$cert1->certificate_pem = '-----BEGIN CERTIFICATE-----test-content-1-----END CERTIFICATE-----';
$cert1->private_key_pem = '-----BEGIN PRIVATE KEY-----test-key-1-----END PRIVATE KEY-----';

$cert2 = new stdClass();
$cert2->name = 'test-cert-2';
$cert2->certificate_pem = '-----BEGIN CERTIFICATE-----test-content-2-----END CERTIFICATE-----';
$cert2->private_key_pem = '-----BEGIN PRIVATE KEY-----test-key-2-----END PRIVATE KEY-----';

$cert3 = new stdClass();
$cert3->name = '';
$cert3->certificate_pem = '-----BEGIN CERTIFICATE-----test-content-3-----END CERTIFICATE-----';
$cert3->private_key_pem = '-----BEGIN PRIVATE KEY-----test-key-3-----END PRIVATE KEY-----';

$cert4 = new stdClass();
$cert4->name = 'test-cert-4';
$cert4->certificate_pem = '';
$cert4->private_key_pem = '-----BEGIN PRIVATE KEY-----test-key-4-----END PRIVATE KEY-----';

$cert5 = new stdClass();
$cert5->name = 'test-cert-5';
$cert5->certificate_pem = '-----BEGIN CERTIFICATE-----test-content-5-----END CERTIFICATE-----';
$cert5->private_key_pem = '';

r($cneTest->uploadCertTest($cert1, 'stable')) && p('code') && e('600'); // 步骤1:传入完整的证书对象和channel参数
r($cneTest->uploadCertTest($cert2, '')) && p('code') && e('600'); // 步骤2:使用默认channel,cert对象完整
r($cneTest->uploadCertTest($cert3, 'stable')) && p('code') && e('600'); // 步骤3:证书对象name字段为空字符串
r($cneTest->uploadCertTest($cert4, 'stable')) && p('code') && e('600'); // 步骤4:证书对象certificate_pem字段为空字符串
r($cneTest->uploadCertTest($cert5, 'stable')) && p('code') && e('600'); // 步骤5:证书对象private_key_pem字段为空字符串