#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

function initData()
{
    $bugdata = zenData('bug');
    $bugdata->id->range('1-3');
    $bugdata->case->range('0');

    zenData('case')->gen(10);
    $bugdata->gen(3);
}

initData();

/**

title=测试 testcaseModel->linkBugs();
timeout=0
cid=19050

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

$testcase = new testcaseTaoTest();
r($testcase->linkBugsTest($caseIDList[0], $toLinkBugs[0])) && p('0:id,case') && e('1,1');               // 测试修改用例 1 关联 bug 1 
r($testcase->linkBugsTest($caseIDList[0], $toLinkBugs[1])) && p('0:id,case;1:id,case') && e('2,1;3,1'); // 测试修改用例 1 关联 bug 2,3