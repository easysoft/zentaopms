#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/custom.class.php';

$langTable = zdTable('lang');
$langTable->lang->range('zh-cn');
$langTable->module->range('custom');
$langTable->section->range('URSRList');
$langTable->key->range('1-5');
$langTable->value->range('`{"SRName":"\\\u8f6f\\\u4ef6\\\u9700\\\u6c42","URName":"\\\u7528\\\u6237\\\u9700\\\u6c42"}`,`{"SRName":"\\\u7814\\\u53d1\\\u9700\\\u6c42","URName":"\\\u7528\\\u6237\\\u9700\\\u6c42"}`,`{"SRName":"\\\u8f6f\\\u9700","URName":"\\\u7528\\\u9700"}`,`{"SRName":"\\\u6545\\\u4e8b","URName":"\\\u53f2\\\u8bd7"}`,`{"SRName":"\\\u9700\\\u6c42","URName":"\\\u7528\\\u6237\\\u9700\\\u6c42"}`');
$langTable->system->range('1{4},0');
$langTable->gen(5);

zdTable('user')->gen(5);
su('admin');

/**

title=测试 customModel->getURSRList();
timeout=0
cid=1

*/

$customTester = new customTest();
r($customTester->getURSRListTest()) && p('1:SRName,URName,system') && e('软件需求,用户需求,1');  //测试正常查询
