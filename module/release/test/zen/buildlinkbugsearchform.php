#!/usr/bin/env php
<?php

/**

title=测试 releaseZen::buildLinkBugSearchForm();
timeout=0
cid=0

- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$normalRelease, 0, 'bug'
 - 属性hasProductField @0
 - 属性hasProjectField @0
 - 属性style @simple
 - 属性queryID @0
- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$branchRelease, 1, 'bug'
 - 属性hasBranchField @1
 - 属性queryID @1
 - 属性productType @branch
- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$normalRelease, 5, 'bug'
 - 属性actionURL @mock_action_url_release_1_type_bug
 - 属性type @bug
- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$branchRelease, 10, 'leftBug'
 - 属性actionURL @mock_action_url_release_2_type_leftBug
 - 属性type @leftBug
- 执行releaseTest模块的buildLinkBugSearchFormTest方法，参数是$normalRelease, -1, 'bug'
 - 属性queryID @-1
 - 属性hasProductField @0
 - 属性hasProjectField @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/release.unittest.class.php';

zenData('release');
zenData('product');
zenData('branch');
zenData('build');
zenData('productplan');
zenData('tree');

su('admin');

$releaseTest = new releaseTest();

$normalRelease = new stdclass();
$normalRelease->id = 1;
$normalRelease->product = 1;
$normalRelease->branch = '0';
$normalRelease->productType = 'normal';

$branchRelease = new stdclass();
$branchRelease->id = 2;
$branchRelease->product = 2;
$branchRelease->branch = '1,2';
$branchRelease->productType = 'branch';

r($releaseTest->buildLinkBugSearchFormTest($normalRelease, 0, 'bug')) && p('hasProductField,hasProjectField,style,queryID') && e('0,0,simple,0');
r($releaseTest->buildLinkBugSearchFormTest($branchRelease, 1, 'bug')) && p('hasBranchField,queryID,productType') && e('1,1,branch');
r($releaseTest->buildLinkBugSearchFormTest($normalRelease, 5, 'bug')) && p('actionURL,type') && e('mock_action_url_release_1_type_bug,bug');
r($releaseTest->buildLinkBugSearchFormTest($branchRelease, 10, 'leftBug')) && p('actionURL,type') && e('mock_action_url_release_2_type_leftBug,leftBug');
r($releaseTest->buildLinkBugSearchFormTest($normalRelease, -1, 'bug')) && p('queryID,hasProductField,hasProjectField') && e('-1,0,0');