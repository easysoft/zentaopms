#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcase.unittest.class.php';
su('admin');

$caseTable = zenData('case');
$caseTable->story->range(0);
$caseTable->gen(10);

/**

title=测试 testcaseModel->doUpdate();
timeout=0
cid=19032

- 测试更新用例名称
 - 属性title @修改后的用例
 - 属性type @feature
 - 属性pri @1
- 测试更新用例类型
 - 属性title @这个是测试用例2
 - 属性type @install
 - 属性pri @2
- 测试更新用例优先级
 - 属性title @这个是测试用例3
 - 属性type @config
 - 属性pri @1

*/

$testcaseIdList = array(1, 2, 3);

$title = array('title' => '修改后的用例');
$type  = array('type'  => 'install');
$pri   = array('pri'   => '1');

$testcase = new testCaseTest();
r($testcase->doUpdateTest($testcaseIdList[0], $title)) && p('title,type,pri') && e('修改后的用例,feature,1');    // 测试更新用例名称
r($testcase->doUpdateTest($testcaseIdList[1], $type))  && p('title,type,pri') && e('这个是测试用例2,install,2'); // 测试更新用例类型
r($testcase->doUpdateTest($testcaseIdList[2], $pri))   && p('title,type,pri') && e('这个是测试用例3,config,1');  // 测试更新用例优先级
