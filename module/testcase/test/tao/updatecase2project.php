#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';
su('admin');

function initData()
{
    $casedata = zenData('case');
    $casedata->id->range('1');
    $casedata->product->range('1');
    $casedata->project->range('1');
    $casedata->story->range('1');

    $projectcasedata = zenData('projectcase');
    $projectcasedata->case->range('1');
    $projectcasedata->product->range('1');
    $projectcasedata->project->range('1');

    $projectstorydata = zenData('projectstory');
    $projectstorydata->story->range('1-2');
    $projectstorydata->project->range('1-4');

    $casedata->gen(1);
    $projectcasedata->gen(1);
    $projectstorydata->gen(4);
}

initData();

/**

title=测试 testcaseModel->updateCase2Project();
timeout=0
cid=19054

- 测试修改用例 1 产品 1 => 2
 - 第0条的project属性 @1
 - 第0条的product属性 @2
 - 第0条的case属性 @1
- 测试修改用例 1 需求 1 => 2
 - 第0条的project属性 @2
 - 第0条的product属性 @1
 - 第0条的case属性 @1
 - 第1条的project属性 @4
 - 第1条的product属性 @1
 - 第1条的case属性 @1

*/

$caseIDList     = array('1');
$objectTypeList = array('product', 'story');
$objectIDList   = array('2');

$testcase = new testcaseTest();

r($testcase->updateCase2ProjectTest($caseIDList[0], $objectTypeList[0], $objectIDList[0])) && p('0:project,product,case')                        && e('1,2,1');       // 测试修改用例 1 产品 1 => 2
r($testcase->updateCase2ProjectTest($caseIDList[0], $objectTypeList[1], $objectIDList[0])) && p('0:project,product,case;1:project,product,case') && e('2,1,1;4,1,1'); // 测试修改用例 1 需求 1 => 2