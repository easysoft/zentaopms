#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

/**

title=测试 testcaseModel->doUpdate();
timeout=0
cid=1


*/

$testcaseIdList = array(1, 2, 3);

$title = array('title' => '修改后的用例');
$type  = array('type'  => 'install');
$pri   = array('pri'   => '1');

$testcase = new testCaseTest();
r($testcase->doUpdateTest($testcaseIdList[0], $title)) && p('title,type,pri') && e('修改后的用例,feature,3');      // 测试更新用例名称
r($testcase->doUpdateTest($testcaseIdList[1], $type))  && p('title,type,pri') && e('测试创建测试用例2,install,1'); // 测试更新用例类型
r($testcase->doUpdateTest($testcaseIdList[2], $pri))   && p('title,type,pri') && e('测试创建测试用例3,feature,1'); // 测试更新用例优先级
