#!/usr/bin/env php
<?php

/**

title=测试 convertModel::getJiraProjectRoleActor();
timeout=0
cid=15779

- 执行convertTest模块的getJiraProjectRoleActorTest方法，参数是'normal' 第1001条的admin属性 @admin
- 执行convertTest模块的getJiraProjectRoleActorTest方法，参数是'normal' 第1001条的JIRAUSER100属性 @JIRAUSER100
- 执行convertTest模块的getJiraProjectRoleActorTest方法，参数是'user_role' 第1001条的user001属性 @user001
- 执行convertTest模块的getJiraProjectRoleActorTest方法，参数是'empty'  @1
- 执行convertTest模块的getJiraProjectRoleActorTest方法，参数是'no_pid'  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

su('admin');

$convertTest = new convertTest();

r($convertTest->getJiraProjectRoleActorTest('normal')) && p('1001:admin') && e('admin');
r($convertTest->getJiraProjectRoleActorTest('normal')) && p('1001:JIRAUSER100') && e('JIRAUSER100');
r($convertTest->getJiraProjectRoleActorTest('user_role')) && p('1001:user001') && e('user001');
r(is_array($convertTest->getJiraProjectRoleActorTest('empty'))) && p() && e('1');
r(count($convertTest->getJiraProjectRoleActorTest('no_pid'))) && p() && e('0');