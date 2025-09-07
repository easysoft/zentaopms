#!/usr/bin/env php
<?php

/**

title=测试 cneModel::uploadCert();
timeout=0
cid=0

- 步骤1：正常上传证书（API错误返回）属性code @600
- 步骤2：使用空的证书对象上传（API错误返回）属性code @600
- 步骤3：使用自定义channel上传证书（API错误返回）属性code @600
- 步骤4：使用不完整的证书对象上传（API错误返回）属性code @600
- 步骤5：使用复杂证书名称上传（API错误返回）属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

su('admin');

$cneTest = new cneTest();

$normalCert = new stdclass();
$normalCert->name = 'test-certificate';
$normalCert->certificate_pem = '-----BEGIN CERTIFICATE-----\nMIIC5DCCAcwCAQAwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAL\n-----END CERTIFICATE-----';
$normalCert->private_key_pem = '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC\n-----END PRIVATE KEY-----';

$emptyCert = new stdclass();
$emptyCert->name = '';
$emptyCert->certificate_pem = '';
$emptyCert->private_key_pem = '';

$incompleteCert = new stdclass();
$incompleteCert->name = 'incomplete-cert';

$complexCert = new stdclass();
$complexCert->name = 'complex-cert-123_test.domain.com';
$complexCert->certificate_pem = '-----BEGIN CERTIFICATE-----\nMIIC5DCCAcwCAQAwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAL\n-----END CERTIFICATE-----';
$complexCert->private_key_pem = '-----BEGIN PRIVATE KEY-----\nMIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQC\n-----END PRIVATE KEY-----';

r($cneTest->uploadCertTest($normalCert)) && p('code') && e('600'); // 步骤1：正常上传证书（API错误返回）
r($cneTest->uploadCertTest($emptyCert)) && p('code') && e('600'); // 步骤2：使用空的证书对象上传（API错误返回）
r($cneTest->uploadCertTest($normalCert, 'stable')) && p('code') && e('600'); // 步骤3：使用自定义channel上传证书（API错误返回）
r($cneTest->uploadCertTest($incompleteCert)) && p('code') && e('600'); // 步骤4：使用不完整的证书对象上传（API错误返回）
r($cneTest->uploadCertTest($complexCert)) && p('code') && e('600'); // 步骤5：使用复杂证书名称上传（API错误返回）