#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getByReviewBy();
timeout=0
cid=18507

- 步骤1：正常情况 - admin用户查询需要审核的story数量 @3
- 步骤2：边界值 - admin用户查询需要审核的requirement数量 @0
- 步骤3：异常输入 - 指定单个产品ID查询待审核故事 @3
- 步骤4：权限验证 - 指定分支1过滤查询待审核故事 @0
- 步骤5：业务规则 - 查询分支0的待审核story @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$storyTable = zenData('story');
$storyTable->id->range('1-10');
$storyTable->product->range('1{4},2{3},3{3}');
$storyTable->branch->range('0{6},1{2},2{2}');
$storyTable->module->range('1{4},2{3},3{3}');
$storyTable->type->range('story{6},requirement{4}');
$storyTable->status->range('reviewing{6},active{2},closed{1},draft{1}');
$storyTable->deleted->range('0');
$storyTable->title->range('Story1,Story2,Story3,Story4,Story5,Story6,Story7,Story8,Story9,Story10');
$storyTable->vision->range('rnd');
$storyTable->version->range('1');
$storyTable->gen(10);

// 手动插入storyreview数据
global $tester;
$tester->dao->delete()->from(TABLE_STORYREVIEW)->exec();
$tester->dao->insert(TABLE_STORYREVIEW)
    ->data(array('story' => 1, 'version' => 1, 'reviewer' => 'admin', 'result' => '', 'reviewDate' => '0000-00-00 00:00:00'))
    ->exec();
$tester->dao->insert(TABLE_STORYREVIEW)
    ->data(array('story' => 2, 'version' => 1, 'reviewer' => 'admin', 'result' => '', 'reviewDate' => '0000-00-00 00:00:00'))
    ->exec();
$tester->dao->insert(TABLE_STORYREVIEW)
    ->data(array('story' => 3, 'version' => 1, 'reviewer' => 'admin', 'result' => '', 'reviewDate' => '0000-00-00 00:00:00'))
    ->exec();
$tester->dao->insert(TABLE_STORYREVIEW)
    ->data(array('story' => 7, 'version' => 1, 'reviewer' => 'admin', 'result' => '', 'reviewDate' => '0000-00-00 00:00:00'))
    ->exec();

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($storyTest->getByReviewByTest(array(1,2,3), 'all', '', 'admin', 'story')) && p() && e('3'); // 步骤1：正常情况 - admin用户查询需要审核的story数量
r($storyTest->getByReviewByTest(array(1,2,3), 'all', '', 'admin', 'requirement')) && p() && e('0'); // 步骤2：边界值 - admin用户查询需要审核的requirement数量
r($storyTest->getByReviewByTest(1, 'all', '', 'admin', 'story')) && p() && e('3'); // 步骤3：异常输入 - 指定单个产品ID查询待审核故事
r($storyTest->getByReviewByTest(array(1,2,3), 1, '', 'admin', 'story')) && p() && e('0'); // 步骤4：权限验证 - 指定分支1过滤查询待审核故事
r($storyTest->getByReviewByTest(array(1,2,3), 0, '', 'admin', 'story')) && p() && e('3'); // 步骤5：业务规则 - 查询分支0的待审核story