#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::getPageSummary();
timeout=0
cid=17996

- 步骤1：type为all的混合状态统计 @本页共 <strong>5</strong> 个发布，已发布 <strong>3</strong>，停止维护 <strong>1</strong>。
- 步骤2：type不为all时的简单统计 @本页共 <strong>5</strong> 个发布。
- 步骤3：空数组的all类型统计 @本页共 <strong>0</strong> 个发布，已发布 <strong>0</strong>，停止维护 <strong>0</strong>。
- 步骤4：全部为normal状态 @本页共 <strong>3</strong> 个发布，已发布 <strong>3</strong>，停止维护 <strong>0</strong>。
- 步骤5：全部为terminate状态 @本页共 <strong>2</strong> 个发布，已发布 <strong>0</strong>，停止维护 <strong>2</strong>。

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（本测试无需数据库数据）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$releaseTest = new releaseModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 准备测试数据
$mixedReleases = array(
    (object)array('id' => 1, 'status' => 'normal'),
    (object)array('id' => 2, 'status' => 'normal'),
    (object)array('id' => 3, 'status' => 'terminate'),
    (object)array('id' => 4, 'status' => 'wait'),
    (object)array('id' => 5, 'status' => 'normal')
);

$normalReleases = array(
    (object)array('id' => 1, 'status' => 'normal'),
    (object)array('id' => 2, 'status' => 'normal'),
    (object)array('id' => 3, 'status' => 'normal')
);

$terminateReleases = array(
    (object)array('id' => 1, 'status' => 'terminate'),
    (object)array('id' => 2, 'status' => 'terminate')
);

$emptyReleases = array();

r($releaseTest->getPageSummaryTest($mixedReleases, 'all')) && p() && e('本页共 <strong>5</strong> 个发布，已发布 <strong>3</strong>，停止维护 <strong>1</strong>。'); // 步骤1：type为all的混合状态统计
r($releaseTest->getPageSummaryTest($mixedReleases, 'normal')) && p() && e('本页共 <strong>5</strong> 个发布。'); // 步骤2：type不为all时的简单统计
r($releaseTest->getPageSummaryTest($emptyReleases, 'all')) && p() && e('本页共 <strong>0</strong> 个发布，已发布 <strong>0</strong>，停止维护 <strong>0</strong>。'); // 步骤3：空数组的all类型统计
r($releaseTest->getPageSummaryTest($normalReleases, 'all')) && p() && e('本页共 <strong>3</strong> 个发布，已发布 <strong>3</strong>，停止维护 <strong>0</strong>。'); // 步骤4：全部为normal状态
r($releaseTest->getPageSummaryTest($terminateReleases, 'all')) && p() && e('本页共 <strong>2</strong> 个发布，已发布 <strong>0</strong>，停止维护 <strong>2</strong>。'); // 步骤5：全部为terminate状态