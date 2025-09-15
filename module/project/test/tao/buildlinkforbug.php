#!/usr/bin/env php
<?php

/**

title=- 执行projectTest模块的buildLinkForBugTest方法，参数是'create'  @baseHelper::createLink(): Argument
timeout=0
cid=1

- 执行projectTest模块的buildLinkForBugTest方法，参数是'create'  @Undefined variable $module:
- 执行projectTest模块的buildLinkForBugTest方法，参数是'edit'  @/home/z/rzto/module/project/test/tao/buildlinkforbug.php?m=project&f=bug&projectID=%s
- 执行projectTest模块的buildLinkForBugTest方法，参数是''  @projectTao::buildLinkForBug(): Return value must be of type string, none returned

- 执行projectTest模块的buildLinkForBugTest方法，参数是'invalid'  @projectTao::buildLinkForBug(): Return value must be of type string, none returned

- 执行projectTest模块的buildLinkForBugTest方法，参数是'test'  @projectTao::buildLinkForBug(): Return value must be of type string, none returned

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

su('admin');

$projectTest = new projectTest();

r($projectTest->buildLinkForBugTest('create')) && p() && e('Undefined variable $module:');
r($projectTest->buildLinkForBugTest('edit')) && p() && e('/home/z/rzto/module/project/test/tao/buildlinkforbug.php?m=project&f=bug&projectID=%s');
r($projectTest->buildLinkForBugTest('')) && p() && e('projectTao::buildLinkForBug(): Return value must be of type string, none returned');
r($projectTest->buildLinkForBugTest('invalid')) && p() && e('projectTao::buildLinkForBug(): Return value must be of type string, none returned');
r($projectTest->buildLinkForBugTest('test')) && p() && e('projectTao::buildLinkForBug(): Return value must be of type string, none returned');