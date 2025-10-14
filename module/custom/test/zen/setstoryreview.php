#!/usr/bin/env php
<?php

/**

title=测试 customZen::setStoryReview();
cid=0

- 测试步骤1：正常设置需求评审配置启用评审 >> 期望成功返回true
- 测试步骤2：正常设置需求评审配置禁用评审 >> 期望成功返回true
- 测试步骤3：设置多个强制评审用户和角色 >> 期望成功返回true
- 测试步骤4：设置强制不评审用户和部门 >> 期望成功返回true
- 测试步骤5：测试不同模块需求评审配置 >> 期望成功返回true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/custom.unittest.class.php';

zenData('config')->loadYaml('zt_config_setstoryreview', false, 2)->gen(20);

su('admin');

$customTest = new customTest();

r($customTest->setStoryReviewTest('story', array(
    'needReview' => '1',
    'forceReview' => array('admin', 'user1'),
    'forceNotReview' => array(),
    'forceReviewRoles' => array('qa', 'dev'),
    'forceNotReviewRoles' => array(),
    'forceReviewDepts' => array('1', '2'),
    'forceNotReviewDepts' => array()
))) && p() && e(true);
r($customTest->setStoryReviewTest('story', array(
    'needReview' => '0',
    'forceReview' => array(),
    'forceNotReview' => array('guest'),
    'forceReviewRoles' => array(),
    'forceNotReviewRoles' => array('tester'),
    'forceReviewDepts' => array(),
    'forceNotReviewDepts' => array('3')
))) && p() && e(true);
r($customTest->setStoryReviewTest('requirement', array(
    'needReview' => '1',
    'forceReview' => array('admin', 'pm'),
    'forceNotReview' => array(),
    'forceReviewRoles' => array('pm', 'qa'),
    'forceNotReviewRoles' => array(),
    'forceReviewDepts' => array('1'),
    'forceNotReviewDepts' => array()
))) && p() && e(true);
r($customTest->setStoryReviewTest('demand', array(
    'needReview' => '1',
    'forceReview' => array('user2'),
    'forceNotReview' => array(),
    'forceReviewRoles' => array('dev'),
    'forceNotReviewRoles' => array(),
    'forceReviewDepts' => array('2', '3'),
    'forceNotReviewDepts' => array()
))) && p() && e(true);
r($customTest->setStoryReviewTest('epic', array(
    'needReview' => '0',
    'forceReview' => array(),
    'forceNotReview' => array('admin', 'user3'),
    'forceReviewRoles' => array(),
    'forceNotReviewRoles' => array('po', 'test'),
    'forceReviewDepts' => array(),
    'forceNotReviewDepts' => array('1', '4')
))) && p() && e(true);