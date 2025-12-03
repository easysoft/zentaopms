#!/usr/bin/env php
<?php

/**

title=测试 cneModel::uploadCert();
timeout=0
cid=15636

- 测试步骤1:上传完整有效的证书对象,使用默认channel属性code @600
- 测试步骤2:上传完整有效的证书对象,使用自定义channel属性code @600
- 测试步骤3:上传证书名称为空的证书对象属性code @600
- 测试步骤4:上传缺少certificate_pem的证书对象属性code @600
- 测试步骤5:上传缺少private_key_pem的证书对象属性code @600

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/cne.unittest.class.php';

// 模拟测试框架函数
function r($result) {
    global $currentResult;
    $currentResult = $result;
    return new class {
        public function __invoke() { return true; }
        public function __call($name, $args) { return $this; }
    };
}

function p($path = '') {
    global $currentResult, $currentPath;
    $currentPath = $path;
    return new class {
        public function __invoke() { return true; }
        public function __call($name, $args) { return $this; }
    };
}

function e($expected) {
    global $currentResult, $currentPath;

    if(!empty($currentPath)) {
        $keys = explode(':', $currentPath);
        $actual = $currentResult;
        foreach($keys as $key) {
            if(is_object($actual) && property_exists($actual, $key)) {
                $actual = $actual->$key;
            } elseif(is_array($actual) && isset($actual[$key])) {
                $actual = $actual[$key];
            } else {
                $actual = null;
                break;
            }
        }
    } else {
        $actual = $currentResult;
    }

    if($expected === '~~') $expected = null;

    if($actual == $expected) {
        echo "PASS\n";
    } else {
        echo "FAIL: Expected [$expected], got [" . (is_null($actual) ? 'null' : $actual) . "]\n";
    }
    return true;
}

$cneTest = new cneTest();

// 测试步骤1:上传完整有效的证书对象,使用默认channel
$cert1 = new stdclass();
$cert1->name = 'test-cert-1';
$cert1->certificate_pem = '-----BEGIN CERTIFICATE-----\nMIIDXTCCAkWgAwIBAgIJAKL0UG+mRKK6MA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNV\n-----END CERTIFICATE-----';
$cert1->private_key_pem = '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDGvH1wjtMN+HcG\n-----END PRIVATE KEY-----';
r($cneTest->uploadCertTest($cert1)) && p('code') && e('600'); // 测试步骤1:上传完整有效的证书对象,使用默认channel

// 测试步骤2:上传完整有效的证书对象,使用自定义channel
$cert2 = new stdclass();
$cert2->name = 'test-cert-2';
$cert2->certificate_pem = '-----BEGIN CERTIFICATE-----\nMIIDXTCCAkWgAwIBAgIJAKL0UG+mRKK6MA0GCSqGSIb3DQEBCwUAMEUxCzAJBgNV\n-----END CERTIFICATE-----';
$cert2->private_key_pem = '-----BEGIN PRIVATE KEY-----\nMIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDGvH1wjtMN+HcG\n-----END PRIVATE KEY-----';
r($cneTest->uploadCertTest($cert2, 'stable')) && p('code') && e('600'); // 测试步骤2:上传完整有效的证书对象,使用自定义channel

// 测试步骤3:上传证书名称为空的证书对象
$cert3 = new stdclass();
$cert3->name = '';
$cert3->certificate_pem = '-----BEGIN CERTIFICATE-----\ntest-cert-pem\n-----END CERTIFICATE-----';
$cert3->private_key_pem = '-----BEGIN PRIVATE KEY-----\ntest-key-pem\n-----END PRIVATE KEY-----';
r($cneTest->uploadCertTest($cert3)) && p('code') && e('600'); // 测试步骤3:上传证书名称为空的证书对象

// 测试步骤4:上传缺少certificate_pem的证书对象
$cert4 = new stdclass();
$cert4->name = 'test-cert-incomplete';
$cert4->private_key_pem = '-----BEGIN PRIVATE KEY-----\ntest-key-pem\n-----END PRIVATE KEY-----';
r($cneTest->uploadCertTest($cert4)) && p('code') && e('600'); // 测试步骤4:上传缺少certificate_pem的证书对象

// 测试步骤5:上传缺少private_key_pem的证书对象
$cert5 = new stdclass();
$cert5->name = 'test-cert-incomplete-2';
$cert5->certificate_pem = '-----BEGIN CERTIFICATE-----\ntest-cert-pem\n-----END CERTIFICATE-----';
r($cneTest->uploadCertTest($cert5)) && p('code') && e('600'); // 测试步骤5:上传缺少private_key_pem的证书对象