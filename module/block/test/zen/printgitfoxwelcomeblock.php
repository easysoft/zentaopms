#!/usr/bin/env php
<?php

/**

title=测试 blockZen::printGitfoxWelcomeBlock();
timeout=0
cid=0

- 步骤1：验证与我相关包含三个统计项 @1
- 步骤2：验证待我评审数量正确 @1
- 步骤3：验证我发起的评审数量正确 @2
- 步骤4：验证待我解决的问题数量正确 @2
- 步骤5：验证不再展示指派给我 @0
- 步骤6：验证欢迎语包含陪伴时长 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

global $config, $tester;

// 2. 模拟GitFox独立版本
$config->edition = 'gitfox';
$config->vision  = 'gitfox';

// 3. 数据准备
zendata('user')->loadYaml('user_printwelcomeblock', false, 2)->gen(10);
zendata('company')->loadYaml('company_printwelcomeblock', false, 2)->gen(1);

su('admin');

/* 清空评审与问题数据，保证统计结果稳定。 */
$tester->dao->query('TRUNCATE TABLE ' . TABLE_PPM);
$tester->dao->query('TRUNCATE TABLE ' . TABLE_PPMREVIEWERS);
$tester->dao->query('TRUNCATE TABLE ' . TABLE_SCANISSUE);

/* 我发起的评审：2条未关闭、1条已关闭，另有1条由其他用户发起。 */
$reviews = array(
    array('id' => 1, 'title' => '评审请求A', 'repoID' => 1, 'createdBy' => 'admin', 'status' => 'opened', 'reviewStatus' => '', 'deleted' => 0),
    array('id' => 2, 'title' => '评审请求B', 'repoID' => 1, 'createdBy' => 'admin', 'status' => 'merged', 'reviewStatus' => '', 'deleted' => 0),
    array('id' => 3, 'title' => '评审请求C', 'repoID' => 1, 'createdBy' => 'admin', 'status' => 'closed', 'reviewStatus' => '', 'deleted' => 0),
    array('id' => 4, 'title' => '评审请求D', 'repoID' => 1, 'createdBy' => 'user1', 'status' => 'opened', 'reviewStatus' => '', 'deleted' => 0)
);
foreach($reviews as $review) $tester->dao->insert(TABLE_PPM)->data((object)$review)->exec();

/* 待我评审：评审请求A由当前用户评审且未决策。 */
$reviewer = array('requestID' => 1, 'repoID' => 1, 'account' => 'admin', 'decision' => 'pending', 'createdBy' => 'user1');
$tester->dao->insert(TABLE_PPMREVIEWERS)->data((object)$reviewer)->exec();

/* 待我解决的问题：我发起的评审中处于激活状态的问题。 */
$issues = array(
    array('issueKey' => 'issue-001', 'ppmID' => 1, 'title' => '问题1', 'status' => 'todo',    'deleted' => 0),
    array('issueKey' => 'issue-002', 'ppmID' => 1, 'title' => '问题2', 'status' => 'solving', 'deleted' => 0),
    array('issueKey' => 'issue-003', 'ppmID' => 2, 'title' => '问题3', 'status' => 'solved',  'deleted' => 0),
    array('issueKey' => 'issue-004', 'ppmID' => 4, 'title' => '问题4', 'status' => 'todo',    'deleted' => 0),
    array('issueKey' => 'issue-005', 'ppmID' => 1, 'title' => '问题5', 'status' => 'todo',    'deleted' => 1)
);
foreach($issues as $issue) $tester->dao->insert(TABLE_SCANISSUE)->data((object)$issue)->exec();

// 4. 创建测试实例（变量名与模块名一致）
$blockTest = new blockZenTest();

/* GitFox版本的欢迎语与统计项语言项。 */
$tester->app->loadLang('block');
$extLangPath = $tester->app->getModuleExtPath('block', 'lang');
include $extLangPath['common'] . 'zh-cn/gitfox.php';

$result = $blockTest->printGitfoxWelcomeBlockTest('1年2月3天', '06:00');

// 5. 强制要求：必须包含至少5个测试步骤
r(isset($result->reviewByMe['reviewByMe']) && isset($result->reviewByMe['myCreatedReview']) && isset($result->reviewByMe['myIssue'])) && p() && e('1'); // 步骤1：验证与我相关包含三个统计项
r($result->reviewByMe['reviewByMe']['number'])      && p() && e('1'); // 步骤2：验证待我评审数量正确
r($result->reviewByMe['myCreatedReview']['number']) && p() && e('2'); // 步骤3：验证我发起的评审数量正确
r($result->reviewByMe['myIssue']['number'])         && p() && e('2'); // 步骤4：验证待我解决的问题数量正确
r(count($result->assignToMe))                       && p() && e('0'); // 步骤5：验证不再展示指派给我
r(strpos($result->welcomeSummary, '1年2月3天') !== false) && p() && e('1'); // 步骤6：验证欢迎语包含陪伴时长
