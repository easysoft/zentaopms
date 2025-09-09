#!/usr/bin/env php
<?php

/**

title=测试 releaseModel::deleteRelated();
timeout=0
cid=0

- 执行releaseTest模块的deleteRelatedTest方法，参数是1, 'story', 1  @rue
- 执行releaseTest模块的deleteRelatedTest方法，参数是4, 'build', []  @alse
- 执行releaseTest模块的deleteRelatedTest方法，参数是3, 'project', '5, 6, 7'  @rue
- 执行releaseTest模块的deleteRelatedTest方法，参数是2, 'bug', [2, 3, 4]  @alse
- 执行releaseTest模块的deleteRelatedTest方法，参数是5, 'branch', 999  @rue

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

$table = zenData('releaserelated');
$table->release->range('1-5{6}');
$table->objectID->range('1-50');
$table->objectType->range('story{10}, bug{8}, project{5}, build{4}, branch{3}');
$table->gen(30);

su('admin');

$releaseTest = new releaseTest();

r($releaseTest->deleteRelatedTest(1, 'story', 1)) && p() && e(true);
r($releaseTest->deleteRelatedTest(4, 'build', [])) && p() && e(false);
r($releaseTest->deleteRelatedTest(3, 'project', '5,6,7')) && p() && e(true);
r($releaseTest->deleteRelatedTest(2, 'bug', [2, 3, 4])) && p() && e(false);
r($releaseTest->deleteRelatedTest(5, 'branch', 999)) && p() && e(true);