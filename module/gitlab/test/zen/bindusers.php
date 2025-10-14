#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::bindUsers();
timeout=0
cid=0

- 执行gitlabTest模块的bindUsersTest方法，参数是1, array  @success
- 执行gitlabTest模块的bindUsersTest方法，参数是1, array  @success
- 执行gitlabTest模块的bindUsersTest方法，参数是1, array  @success
- 执行gitlabTest模块的bindUsersTest方法，参数是1, array  @success
- 执行gitlabTest模块的bindUsersTest方法，参数是1, array  @success

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

zendata('oauth')->loadYaml('oauth_bindusers', false, 2)->gen(5);
zendata('user')->loadYaml('user_bindusers', false, 2)->gen(10);
zendata('action')->gen(0);

su('admin');

$gitlabTest = new gitlabTest();

r($gitlabTest->bindUsersTest(1, array('100' => 'user1'), array('100' => 'GitLab User 1'), array('user1' => (object)array('realname' => '用户一')))) && p() && e('success');
r($gitlabTest->bindUsersTest(1, array('101' => 'user2'), array('101' => 'GitLab User 2'), array('user2' => (object)array('realname' => '用户二')))) && p() && e('success');
r($gitlabTest->bindUsersTest(1, array('100' => 'user3'), array('100' => 'GitLab User 1'), array('user3' => (object)array('realname' => '用户三')))) && p() && e('success');
r($gitlabTest->bindUsersTest(1, array('102' => ''), array('102' => 'Empty User'), array())) && p() && e('success');
r($gitlabTest->bindUsersTest(1, array('103' => 'user4', '104' => 'user5', '105' => ''), array('103' => 'User 4', '104' => 'User 5', '105' => 'Empty'), array('user4' => (object)array('realname' => '用户四'), 'user5' => (object)array('realname' => '用户五')))) && p() && e('success');