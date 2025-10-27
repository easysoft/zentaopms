#!/usr/bin/env php
<?php

/**

title=测试 repoZen::checkClient();
timeout=0
cid=0

- 执行repoTest模块的checkClientZenTest方法，参数是array  @0
- 执行repoTest模块的checkClientZenTest方法，参数是array  @1
- 执行repoTest模块的checkClientZenTest方法，参数是array  @1
- 执行repoTest模块的checkClientZenTest方法，参数是array  @1
- 执行repoTest模块的checkClientZenTest方法，参数是array  @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/repo.unittest.class.php';

zendata('repo');

su('admin');

$repoTest = new repoTest();

r($repoTest->checkClientZenTest(array('SCM' => 'Git', 'client' => ''), array('inContainer' => false, 'checkClient' => true))) && p() && e('0');
r($repoTest->checkClientZenTest(array('SCM' => 'Subversion', 'client' => 'svn'), array('inContainer' => true))) && p() && e('1');
r($repoTest->checkClientZenTest(array('SCM' => 'Git', 'client' => ''), array('checkClient' => false))) && p() && e('1');
r($repoTest->checkClientZenTest(array('SCM' => 'Gitlab', 'client' => ''), array('notSyncSCM' => array('Gitlab')))) && p() && e('1');
r($repoTest->checkClientZenTest(array('SCM' => 'Git', 'client' => 'git'), array('hasVersionFile' => true))) && p() && e('1');