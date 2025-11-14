#!/usr/bin/env php
<?php

/**

title=测试 gogsModel::apiGetSingleProject();
timeout=0
cid=16688

- 测试步骤1：不存在的gogsID @0
- 测试步骤2：gogsID为0的边界值 @0
- 测试步骤3：空字符串项目ID @0
- 测试步骤4：非gogs类型服务器 @0
- 测试步骤5：有效gogs服务器但API调用失败 @0
- 测试步骤6：已删除服务器 @0
- 测试步骤7：有效gogs服务器项目不存在 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gogs.unittest.class.php';

$pipeline = zenData('pipeline');
$pipeline->id->range('1-5');
$pipeline->name->range('Gogs有效服务器,Gogs测试服务器,无效Gogs服务器,空配置服务器,已删除服务器');
$pipeline->type->range('gogs,gogs,gitlab,gogs,gitea');
$pipeline->url->range('https://gogsdev.qc.oop.cc,http://valid-gogs.test.com,http://invalid-gogs.com,,');
$pipeline->token->range('valid_gogs_token_123,another_valid_token,invalid_token_456,,expired_token');
$pipeline->deleted->range('0,0,0,0,1');
$pipeline->gen(5);

zenData('oauth')->gen(3);

su('admin');

$gogsTest = new gogsTest();

r($gogsTest->apiGetSingleProjectTest(999, 'test/project')) && p() && e('0'); // 测试步骤1：不存在的gogsID
r($gogsTest->apiGetSingleProjectTest(0, 'test/project')) && p() && e('0'); // 测试步骤2：gogsID为0的边界值
r($gogsTest->apiGetSingleProjectTest(1, '')) && p() && e('0'); // 测试步骤3：空字符串项目ID
r($gogsTest->apiGetSingleProjectTest(3, 'invalid/project')) && p() && e('0'); // 测试步骤4：非gogs类型服务器
r($gogsTest->apiGetSingleProjectTest(4, 'easycorp/unittest')) && p() && e('0'); // 测试步骤5：有效gogs服务器但API调用失败
r($gogsTest->apiGetSingleProjectTest(5, 'easycorp/unittest')) && p() && e('0'); // 测试步骤6：已删除服务器
r($gogsTest->apiGetSingleProjectTest(2, 'test/project')) && p() && e('0'); // 测试步骤7：有效gogs服务器项目不存在