#!/usr/bin/env php
<?php

/**

title=测试jenkinsModel->getJobPairs();
cid=1
pid=1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/jenkins.class.php';

zdTable('job')->config('job')->gen('10');
zdTable('user')->gen('1');

su('admin');

$jenkins = new jenkinsTest();

$jenkinsID = array(1, 2, 3, 0, 111);

r($jenkins->getJobPairsTest($jenkinsID[0])) && p() && e('1:这是一个Job1,6:这是一个Job6');  // 测试获取 jenkins 1 的构建任务
r($jenkins->getJobPairsTest($jenkinsID[1])) && p() && e('2:这是一个Job2,7:这是一个Job7');  // 测试获取 jenkins 2 的构建任务
r($jenkins->getJobPairsTest($jenkinsID[2])) && p() && e('3:这是一个Job3');                 // 测试获取 jenkins 3 的构建任务
r($jenkins->getJobPairsTest($jenkinsID[3])) && p() && e('0');                              // 测试获取 jenkins 0 的构建任务
r($jenkins->getJobPairsTest($jenkinsID[4])) && p() && e('0');                              // 测试获取 jenkins 111 的构建任务
