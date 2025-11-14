#!/usr/bin/env php
<?php

/**

title=测试 ciModel::sendRequest();
timeout=0
cid=15591

- 步骤1：正常情况 @1
- 步骤2：空数据 @1
- 步骤3：无效URL @0
- 步骤4：错误认证 @0
- 步骤5：PARAM_TAG处理 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/ci.unittest.class.php';

su('admin');

$ci = new ciTest();

$validUrl   = 'https://jenkinsdev.qc.oop.cc/job/paramsJob/buildWithParameters/api/json';
$invalidUrl = 'https://invalid.example.com/invalid/path';

$validPWD   = 'jenkins:11eb8b38c99143c7c6d872291e291abff4';
$invalidPWD = 'invalid:password';

$emptyData = new stdclass();

$normalData = new stdclass();
$normalData->PARAM_TAG = 'tag_test1';
$normalData->PARAM_REVISION = 'revision123';

$tagData = new stdclass();
$tagData->PARAM_TAG = 'v1.0.0';
$tagData->PARAM_REVISION = 'should_be_cleared';

r($ci->sendRequestTest($validUrl, $normalData, $validPWD))   && p() && e('1');  // 步骤1：正常情况
r($ci->sendRequestTest($validUrl, $emptyData, $validPWD))    && p() && e('1');  // 步骤2：空数据
r($ci->sendRequestTest($invalidUrl, $normalData, $validPWD)) && p() && e('0');  // 步骤3：无效URL
r($ci->sendRequestTest($validUrl, $normalData, $invalidPWD)) && p() && e('0');  // 步骤4：错误认证
r($ci->sendRequestTest($validUrl, $tagData, $validPWD))      && p() && e('1');  // 步骤5：PARAM_TAG处理