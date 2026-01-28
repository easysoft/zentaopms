#!/usr/bin/env php
<?php

/**

title=测试 projectreleaseTao::getBranchName();
timeout=0
cid=17973

- 执行projectreleaseTest模块的getBranchNameTest方法，参数是1, '1', $branchGroup  @branch1
- 执行projectreleaseTest模块的getBranchNameTest方法，参数是1, '1, 2, 3', $branchGroup  @branch1,branch2,branch3

- 执行projectreleaseTest模块的getBranchNameTest方法，参数是1, '', $branchGroup  @0
- 执行projectreleaseTest模块的getBranchNameTest方法，参数是999, '1', $branchGroup  @0
- 执行projectreleaseTest模块的getBranchNameTest方法，参数是1, '999', $branchGroup  @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('branch')->loadYaml('branch', false, 2)->gen(5);

su('admin');

$projectreleaseTest = new projectreleaseTaoTest();

$branchGroup = array(
    1 => array(
        1 => 'branch1',
        2 => 'branch2',
        3 => 'branch3'
    ),
    2 => array(
        4 => 'branch4',
        5 => 'branch5'
    )
);

r($projectreleaseTest->getBranchNameTest(1, '1', $branchGroup)) && p() && e('branch1');
r($projectreleaseTest->getBranchNameTest(1, '1,2,3', $branchGroup)) && p() && e('branch1,branch2,branch3');
r($projectreleaseTest->getBranchNameTest(1, '', $branchGroup)) && p() && e('0');
r($projectreleaseTest->getBranchNameTest(999, '1', $branchGroup)) && p() && e('0');
r($projectreleaseTest->getBranchNameTest(1, '999', $branchGroup)) && p() && e('0');