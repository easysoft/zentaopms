#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

function initData()
{
    $casedata = zdTable('case');
    $casedata->id->range('1-10');
    $casedata->story->range('1-10');
}

/**

title=测试 testcaseModel->getRelatedStories();
timeout=0
cid=1

- 测试获取关联的需求
 - 属性1 @用户需求1
 - 属性2 @软件需求2

*/

$storyIDList = array('1', '2', '3');

$testcase = new testcaseTest();
r($testcase->getRelatedStoriesTest($storyIDList)) && p('1;2') && e('用户需求1;软件需求2'); // 测试获取关联的需求