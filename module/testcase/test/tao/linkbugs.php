#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

function initData()
{
    $bugdata = zdTable('bug');
    $bugdata->id->range('1-3');
    $bugdata->case->range('0');

    $bugdata->gen(3);
}

/**

title=测试 testcaseModel->linkBug();
timeout=0
cid=1

- 测试修改用例 1 关联 bug 1
 - 第0条的id属性 @1
 - 第0条的case属性 @1
- 测试修改用例 1 关联 bug 2,3
 - 第0条的id属性 @2
 - 第0条的case属性 @1
 - 第1条的id属性 @3
 - 第1条的case属性 @1

*/

initData();

$caseIDList = array('1');
$toLinkBugs = array(array('1'), array('2', '3'));

$testcase = new testcaseTest();
r($testcase->linkBugsTest($caseIDList[0], $toLinkBugs[0])) && p('0:id,case') && e('1,1');               // 测试修改用例 1 关联 bug 1 
r($testcase->linkBugsTest($caseIDList[0], $toLinkBugs[1])) && p('0:id,case;1:id,case') && e('2,1;3,1'); // 测试修改用例 1 关联 bug 2,3
