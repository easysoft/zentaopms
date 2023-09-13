#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';

zdTable('case')->gen('20');
zdTable('story')->gen('20');
zdTable('project')->gen('100', true, false);
zdTable('project')->config('execution')->gen('100', false, false);
zdTable('projectcase')->gen('100');
zdTable('projectstory')->gen('4');
zdTable('user')->gen('1');

su('admin');

/**

title=测试 testcaseModel->syncCase2Project();
cid=1
pid=1

*/

$caseIDList = array(1, 5, 9, 13, 17);

$testcase = new testcaseTest();

r($testcase->syncCase2ProjectTest($caseIDList[0])) && p() && e('11,101'); // 测试同步用例 1  到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[1])) && p() && e('12,102'); // 测试同步用例 5  到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[2])) && p() && e('103'); // 测试同步用例 9  到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[3])) && p() && e('104'); // 测试同步用例 13 到关联项目中
r($testcase->syncCase2ProjectTest($caseIDList[4])) && p() && e('105'); // 测试同步用例 17 到关联项目中
