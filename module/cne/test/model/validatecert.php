#!/usr/bin/env php
<?php

/**

title=测试 cneModel::validateCert();
timeout=0
cid=15637

- 步骤1:使用空证书名称验证证书属性code @600
- 步骤2:使用空证书PEM内容验证证书属性code @600
- 步骤3:使用空私钥验证证书属性code @600
- 步骤4:使用空域名验证证书属性code @600
- 步骤5:使用完整有效参数验证证书属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

$cneTest = new cneTest();

// 准备测试数据
$validCertName = 'test-tls-cert';
$validPem = '-----BEGIN CERTIFICATE-----
MIIDXTCCAkWgAwIBAgIJAKZ8zFQvKmJGMA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNV
BAYTAkNOMQswCQYDVQQIDAJCSjEQMA4GA1UEBwwHQmVpamluZzEXMBUGA1UEAwwO
dGVzdC50ZXN0LmNvbTAeFw0yNDAxMDEwMDAwMDBaFw0yNTAxMDEwMDAwMDBaMEUx
-----END CERTIFICATE-----';
$validKey = '-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQC7VJTUt9Us8cKj
MzEfYyjiWA4R4/M2bS1+fWIcPm15j7APdBHHU3aMk5mBmISxQcUrxPcKj7E3+XA0
-----END PRIVATE KEY-----';
$validDomain = 'test.test.com';

r($cneTest->validateCertTest('', $validPem, $validKey, $validDomain)) && p('code') && e('600'); // 步骤1:使用空证书名称验证证书
r($cneTest->validateCertTest($validCertName, '', $validKey, $validDomain)) && p('code') && e('600'); // 步骤2:使用空证书PEM内容验证证书
r($cneTest->validateCertTest($validCertName, $validPem, '', $validDomain)) && p('code') && e('600'); // 步骤3:使用空私钥验证证书
r($cneTest->validateCertTest($validCertName, $validPem, $validKey, '')) && p('code') && e('600'); // 步骤4:使用空域名验证证书
r($cneTest->validateCertTest($validCertName, $validPem, $validKey, $validDomain)) && p('code') && e('600'); // 步骤5:使用完整有效参数验证证书