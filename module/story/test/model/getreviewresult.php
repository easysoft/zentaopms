#!/usr/bin/env php
<?php

/**

title=测试 storyModel->getReviewResult();
timeout=0
cid=18554

- 传入空参数 @pass
- 设置 allpass，都是传入pass结果 @pass
- 设置 allpass，传入 pass 和 reject @reject
- 设置 allpass，传入 clarify 和 pass @clarify
- 设置 allpass，传入 revert 和 pass @revert
- 设置 allpass，传入 reject 和 pass @reject
- 设置 allpass，传入 clarify @clarify
- 设置 allpass，传入 revert @revert
- 设置 allpass，传入 reject @reject
- 设置 halfpass，传入 pass 和 reject @reject

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

global $tester;
$storyModel = $tester->loadModel('story');
r($storyModel->getReviewResult(array())) && p() && e('pass'); //传入空参数

$storyModel->config->story->reviewRules = 'allpass';
r($storyModel->getReviewResult(array('pass', 'pass'))) && p() && e('pass');                              //设置 allpass，都是传入pass结果
r($storyModel->getReviewResult(array('pass', 'pass', 'reject'))) && p() && e('reject');                  //设置 allpass，传入 pass 和 reject
r($storyModel->getReviewResult(array('clarify', 'clarify', 'clarify', 'pass'))) && p() && e('clarify');  //设置 allpass，传入 clarify 和 pass
r($storyModel->getReviewResult(array('revert', 'revert', 'revert', 'pass'))) && p() && e('revert');      //设置 allpass，传入 revert 和 pass
r($storyModel->getReviewResult(array('reject', 'reject', 'reject', 'pass'))) && p() && e('reject');      //设置 allpass，传入 reject 和 pass

r($storyModel->getReviewResult(array('clarify', '', '', ''))) && p() && e('clarify'); //设置 allpass，传入 clarify
r($storyModel->getReviewResult(array('revert',  '', '', ''))) && p() && e('revert');  //设置 allpass，传入 revert
r($storyModel->getReviewResult(array('reject',  '', '', ''))) && p() && e('reject');  //设置 allpass，传入 reject

$storyModel->config->story->reviewRules = 'halfpass';
r($storyModel->getReviewResult(array('pass', 'pass', 'reject'))) && p() && e('reject'); //设置 halfpass，传入 pass 和 reject