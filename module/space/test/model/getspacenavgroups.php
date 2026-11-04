#!/usr/bin/env php
<?php

/**

title=测试 spaceModel::getSpaceNavGroups();
timeout=0
cid=0

- 步骤1：研发版返回devops分组 @devops
- 步骤2：GitFox独立版返回独立导航分组 @devops,repo,codescan,ppm,pipeline,artifact
- 步骤3：运维版返回devops分组 @devops
- 步骤4：vision为空时返回devops分组 @devops
- 步骤5：GitFox独立版分组数量为6 @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$spaceTester = new spaceModelTest();

r($spaceTester->getSpaceNavGroupsTest('rnd'))     && p() && e('devops');                                   // 步骤1：研发版返回devops分组
r($spaceTester->getSpaceNavGroupsTest('gitfox'))  && p() && e('devops,repo,codescan,ppm,pipeline,artifact'); // 步骤2：GitFox独立版返回独立导航分组
r($spaceTester->getSpaceNavGroupsTest('or'))      && p() && e('devops');                                   // 步骤3：运维版返回devops分组
r($spaceTester->getSpaceNavGroupsTest(''))        && p() && e('devops');                                   // 步骤4：vision为空时返回devops分组
r(count(explode(',', $spaceTester->getSpaceNavGroupsTest('gitfox')))) && p() && e('6');                    // 步骤5：GitFox独立版分组数量为6
