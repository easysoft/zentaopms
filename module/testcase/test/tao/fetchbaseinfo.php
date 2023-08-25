#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/testcase.class.php';
su('admin');

zdTable('case')->gen(2);

/**

title=测试 testcaseTao->fetchBaseInfo();
cid=1
pid=1

*/

$testcase = new testcaseTest();

r($testcase->fetchBaseInfo(0))        && p() && e('0'); // caseID 参数为 0 返回 false。
r($testcase->fetchBaseInfo(3))        && p() && e('0'); // caseID 参数在数据库中不存在返回 false。
r($testcase->fetchBaseInfo(4))        && p() && e('0'); // caseID 参数在数据库中不存在返回 false。
r($testcase->fetchBaseInfo(-1))       && p() && e('0'); // caseID 参数小于 mediumint unsigned 类型最小值返回 false。
r($testcase->fetchBaseInfo(16777216)) && p() && e('0'); // caseID 参数大于 mediumint unsigned 类型最大值返回 false。

r($testcase->fetchBaseInfo(1)) && p('id,title,deleted') && e('1,这个是测试用例1,0'); // caseID 参数为 1 返回用例信息。
r($testcase->fetchBaseInfo(2)) && p('id,title,deleted') && e('2,这个是测试用例2,0'); // caseID 参数为 2 返回用力信息。
