#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';
su('admin');

function initData()
{
    $casedata = zenData('case');
    $casedata->id->range('1-10');
    $casedata->story->range('1-10');
}

/**

title=测试 testcaseModel->getRelatedStories();
timeout=0
cid=19044

- 测试获取关联的需求
 - 属性1 @用户需求1
 - 属性2 @软件需求2
 - 属性3 @用户需求3
 - 属性4 @软件需求4
 - 属性5 @用户需求5

*/

$storyIDList = array('1', '2', '3', '4', '5');

$testcase = new testcaseTest();
r($testcase->getRelatedStoriesTest($storyIDList)) && p('1;2;3;4;5') && e('用户需求1;软件需求2;用户需求3;软件需求4;用户需求5'); // 测试获取关联的需求