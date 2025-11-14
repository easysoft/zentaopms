#!/usr/bin/env php
<?php

/**

title=测试 gitlabModel::apiGet();
timeout=0
cid=16597

- 步骤1：使用有效的host URL发送API请求 @success
- 步骤2：使用有效的host ID发送API请求 @success
- 步骤3：使用无效的host URL格式 @return null
- 步骤4：使用不存在的host ID @return null
- 步骤5：使用空字符串作为API参数 @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

// 准备测试数据
$pipeline = zenData('pipeline');
$pipeline->id->range('1-5');
$pipeline->name->range('gitlab1,gitlab2,gitlab3,gitlab4,gitlab5');
$pipeline->type->range('gitlab{5}');
$pipeline->url->range('https://gitlabdev.qc.oop.cc{5}');
$pipeline->token->range('glpat-b8Sa1pM9k9ygxMZYPN6w{5}');
ob_start();
$pipeline->gen(5);
ob_end_clean();

// 用户登录
su('admin');

// 创建测试实例
$gitlabTest = new gitlabTest();

r($gitlabTest->apiGetTest('https://gitlabdev.qc.oop.cc/api/v4%s?private_token=glpat-b8Sa1pM9k9ygxMZYPN6w', '/user')) && p() && e('success'); // 步骤1：使用有效的host URL发送API请求
r($gitlabTest->apiGetTest(1, '/user')) && p() && e('success'); // 步骤2：使用有效的host ID发送API请求
r($gitlabTest->apiGetTest('abc.com', '/user')) && p() && e('return null'); // 步骤3：使用无效的host URL格式
r($gitlabTest->apiGetTest(999, '/user')) && p() && e('return null'); // 步骤4：使用不存在的host ID
r($gitlabTest->apiGetTest(1, '')) && p() && e('success'); // 步骤5：使用空字符串作为API参数