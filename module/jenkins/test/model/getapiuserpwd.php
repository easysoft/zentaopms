#!/usr/bin/env php
<?php

/**

title=测试jenkinsModel->getApiUserPWD();
cid=16830

- 测试获取 jenkins 1 的jenkins api 密码串 @root:glpat-b8Sa1pM9k9ygxMZYPN6w
- 测试获取 jenkins 2 的jenkins api 密码串 @sonar:squ_00a8b5794f6fa527bfb8f2a6f382264b5d306b17
- 测试获取 jenkins 3 的jenkins api 密码串 @jenkins:11eb8b38c99143c7c6d872291e291abff4
- 测试获取 jenkins 4 的jenkins api 密码串 @gitea:6149a6013047301b116389d50db5cbf599772082
- 测试获取 jenkins 5 的jenkins api 密码串 @gogs-admin:0c37d25758930f24e955dd0307bd37e975e3b457

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen('1');

su('admin');

$jenkins = new jenkinsModelTest();

$pipeline1 = new stdClass();
$pipeline1->account  = 'root';
$pipeline1->token    = 'glpat-b8Sa1pM9k9ygxMZYPN6w';
$pipeline1->password = base64_encode('unused');

$pipeline2 = new stdClass();
$pipeline2->account  = 'sonar';
$pipeline2->token    = 'squ_00a8b5794f6fa527bfb8f2a6f382264b5d306b17';
$pipeline2->password = base64_encode('unused');

$pipeline3 = new stdClass();
$pipeline3->account  = 'jenkins';
$pipeline3->token    = '';
$pipeline3->password = base64_encode('11eb8b38c99143c7c6d872291e291abff4');

$pipeline4 = new stdClass();
$pipeline4->account  = 'gitea';
$pipeline4->token    = '';
$pipeline4->password = base64_encode('6149a6013047301b116389d50db5cbf599772082');

$pipeline5 = new stdClass();
$pipeline5->account  = 'gogs-admin';
$pipeline5->token    = '';
$pipeline5->password = base64_encode('0c37d25758930f24e955dd0307bd37e975e3b457');

r($jenkins->instance->getApiUserPWD($pipeline1)) && p() && e('root:glpat-b8Sa1pM9k9ygxMZYPN6w');                     // 测试获取 jenkins 1 的jenkins api 密码串
r($jenkins->instance->getApiUserPWD($pipeline2)) && p() && e('sonar:squ_00a8b5794f6fa527bfb8f2a6f382264b5d306b17');  // 测试获取 jenkins 2 的jenkins api 密码串
r($jenkins->instance->getApiUserPWD($pipeline3)) && p() && e('jenkins:11eb8b38c99143c7c6d872291e291abff4');          // 测试获取 jenkins 3 的jenkins api 密码串
r($jenkins->instance->getApiUserPWD($pipeline4)) && p() && e('gitea:6149a6013047301b116389d50db5cbf599772082');      // 测试获取 jenkins 4 的jenkins api 密码串
r($jenkins->instance->getApiUserPWD($pipeline5)) && p() && e('gogs-admin:0c37d25758930f24e955dd0307bd37e975e3b457'); // 测试获取 jenkins 5 的jenkins api 密码串
