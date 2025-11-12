#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildLinkBugSearchForm();
timeout=0
cid=0

- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$normalRelease, 0, 'bug' 第actionURL条的contains属性 @true
- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$branchRelease, 1, 'bug' 属性branchConfigured @true
- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$normalRelease, 0, 'bug' 属性queryID @0
- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$normalRelease, 5, 'bug' 属性queryID @5
- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$normalRelease, 1, 'leftBug' 第type条的contains属性 @true
- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$branchRelease, 2, 'bug' 属性configComplete @true

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/releasezen.unittest.class.php';

su('admin');

$releaseTest = new releaseZenTest();

$normalRelease = new stdClass();
$normalRelease->id = 1;
$normalRelease->product = 1;
$normalRelease->branch = '0';
$normalRelease->productType = 'normal';

$branchRelease = new stdClass();
$branchRelease->id = 2;
$branchRelease->product = 2;
$branchRelease->branch = '1,2';
$branchRelease->productType = 'branch';

r($releaseTest->buildLinkBugSearchFormTest($normalRelease, 0, 'bug')) && p('actionURL:contains') && e('true');
r($releaseTest->buildLinkBugSearchFormTest($branchRelease, 1, 'bug')) && p('branchConfigured') && e('true');
r($releaseTest->buildLinkBugSearchFormTest($normalRelease, 0, 'bug')) && p('queryID') && e('0');
r($releaseTest->buildLinkBugSearchFormTest($normalRelease, 5, 'bug')) && p('queryID') && e('5');
r($releaseTest->buildLinkBugSearchFormTest($normalRelease, 1, 'leftBug')) && p('type:contains') && e('true');
r($releaseTest->buildLinkBugSearchFormTest($branchRelease, 2, 'bug')) && p('configComplete') && e('true');