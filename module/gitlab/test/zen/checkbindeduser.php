#!/usr/bin/env php
<?php

/**

title=测试 gitlabZen::checkBindedUser();
timeout=0
cid=0

- 执行gitlabTest模块的checkBindedUserTest方法，参数是1, 'admin', true  @admin_pass
- 执行gitlabTest模块的checkBindedUserTest方法，参数是1, 'binded_user', false  @success
- 执行gitlabTest模块的checkBindedUserTest方法，参数是1, 'unbinded_user', false  @error:必须先绑定GitLab用户
- 执行gitlabTest模块的checkBindedUserTest方法，参数是1, '', false  @error:必须先绑定GitLab用户
- 执行gitlabTest模块的checkBindedUserTest方法，参数是1, 'nonexistent_user', false  @error:必须先绑定GitLab用户

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/gitlab.unittest.class.php';

zenData('pipeline')->gen(5);
zenData('oauth')->gen(5);

su('admin');

$gitlabTest = new gitlabTest();

r($gitlabTest->checkBindedUserTest(1, 'admin', true)) && p() && e('admin_pass');
r($gitlabTest->checkBindedUserTest(1, 'binded_user', false)) && p() && e('success');
r($gitlabTest->checkBindedUserTest(1, 'unbinded_user', false)) && p() && e('error:必须先绑定GitLab用户');
r($gitlabTest->checkBindedUserTest(1, '', false)) && p() && e('error:必须先绑定GitLab用户');
r($gitlabTest->checkBindedUserTest(1, 'nonexistent_user', false)) && p() && e('error:必须先绑定GitLab用户');