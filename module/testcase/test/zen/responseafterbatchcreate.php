#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::responseAfterBatchCreate();
timeout=0
cid=0

- 步骤1：正常QA tab环境
 - 属性result @success
 - 属性load @/testcase-browse-productID=1&branch=all.html
- 步骤2：项目tab环境属性result @success
- 步骤3：模态框AJAX请求
 - 属性result @success
 - 属性closeModal @1
- 步骤4：JSON视图类型属性result @success
- 步骤5：DAO错误情况
 - 属性result @fail
 - 属性message @测试错误信息

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseTest();

// 4. 测试步骤：必须包含至少5个测试步骤
r($testcaseTest->responseAfterBatchCreateTest(1, 'all', array('app' => array('tab' => 'qa')))) && p('result,load') && e('success,/testcase-browse-productID=1&branch=all.html'); // 步骤1：正常QA tab环境
r($testcaseTest->responseAfterBatchCreateTest(1, 'all', array('app' => array('tab' => 'project'), 'session' => array('project' => 5)))) && p('result') && e('success'); // 步骤2：项目tab环境
r($testcaseTest->responseAfterBatchCreateTest(1, 'all', array('request' => array('HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'), 'app' => array('requestType' => 'GET', 'rawParams' => array('modal'))))) && p('result,closeModal') && e('success,1'); // 步骤3：模态框AJAX请求
r($testcaseTest->responseAfterBatchCreateTest(1, 'all', array('viewType' => 'json'))) && p('result') && e('success'); // 步骤4：JSON视图类型
r($testcaseTest->responseAfterBatchCreateTest(1, 'all', array('daoError' => array('测试错误信息')))) && p('result,message') && e('fail,测试错误信息'); // 步骤5：DAO错误情况