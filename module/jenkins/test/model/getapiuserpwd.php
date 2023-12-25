#!/usr/bin/env php
<?php

/**

title=测试jenkinsModel->getApiUserPWD();
cid=1
pid=1

- 测试获取 jenkins 1 的jenkins api 密码串 @root:glpat-b8Sa1pM9k9ygxMZYPN6w
- 测试获取 jenkins 2 的jenkins api 密码串 @sonar:squ_00a8b5794f6fa527bfb8f2a6f382264b5d306b17
- 测试获取 jenkins 3 的jenkins api 密码串 @jenkins:11eb8b38c99143c7c6d872291e291abff4
- 测试获取 jenkins 4 的jenkins api 密码串 @gitea:6149a6013047301b116389d50db5cbf599772082
- 测试获取 jenkins 5 的jenkins api 密码串 @gogs-admin:6aafc3d332b70312a680fedf26039c67e98cfabe

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/jenkins.class.php';

zdTable('pipeline')->gen('5');
zdTable('user')->gen('1');

su('admin');

$jenkins = new jenkinsTest();

$jenkinsID = array(1, 2, 3, 4, 5);

r($jenkins->getApiUserPWDTest($jenkinsID[0])) && p() && e('root:glpat-b8Sa1pM9k9ygxMZYPN6w');                     // 测试获取 jenkins 1 的jenkins api 密码串
r($jenkins->getApiUserPWDTest($jenkinsID[1])) && p() && e('sonar:squ_00a8b5794f6fa527bfb8f2a6f382264b5d306b17');  // 测试获取 jenkins 2 的jenkins api 密码串
r($jenkins->getApiUserPWDTest($jenkinsID[2])) && p() && e('jenkins:11eb8b38c99143c7c6d872291e291abff4');          // 测试获取 jenkins 3 的jenkins api 密码串
r($jenkins->getApiUserPWDTest($jenkinsID[3])) && p() && e('gitea:6149a6013047301b116389d50db5cbf599772082');      // 测试获取 jenkins 4 的jenkins api 密码串
r($jenkins->getApiUserPWDTest($jenkinsID[4])) && p() && e('gogs-admin:6aafc3d332b70312a680fedf26039c67e98cfabe'); // 测试获取 jenkins 5 的jenkins api 密码串
