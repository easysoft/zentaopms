#!/usr/bin/env php
<?php

/**

title=测试 cneModel::uploadCert();
timeout=0
cid=15636

- 步骤1:使用默认channel上传证书属性code @600
- 步骤2:使用自定义channel上传证书属性code @600
- 步骤3:使用空证书名称上传属性code @600
- 步骤4:证书对象缺少必要字段属性code @600
- 步骤5:完整有效的证书对象上传属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

// 准备测试用的证书对象
$validCert = new stdclass();
$validCert->name = 'test-cert';
$validCert->certificate_pem = '-----BEGIN CERTIFICATE-----\ntest-cert-pem\n-----END CERTIFICATE-----';
$validCert->private_key_pem = '-----BEGIN PRIVATE KEY-----\ntest-key-pem\n-----END PRIVATE KEY-----';

$emptyCert = new stdclass();
$emptyCert->name = '';
$emptyCert->certificate_pem = '';
$emptyCert->private_key_pem = '';

$incompleteCert = new stdclass();
$incompleteCert->name = 'incomplete-cert';
// 缺少certificate_pem和private_key_pem字段

r($cneTest->uploadCertTest($validCert, '')) && p('code') && e('600'); // 步骤1:使用默认channel上传证书
r($cneTest->uploadCertTest($validCert, 'stable')) && p('code') && e('600'); // 步骤2:使用自定义channel上传证书
r($cneTest->uploadCertTest($emptyCert, '')) && p('code') && e('600'); // 步骤3:使用空证书名称上传
r($cneTest->uploadCertTest($incompleteCert, '')) && p('code') && e('600'); // 步骤4:证书对象缺少必要字段
r($cneTest->uploadCertTest($validCert, 'production')) && p('code') && e('600'); // 步骤5:完整有效的证书对象上传