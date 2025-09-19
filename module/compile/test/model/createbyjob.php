#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 compileModel::createByJob();
timeout=0
cid=1

- 测试步骤1：使用有效jobID和tag类型创建构建记录 >> 期望返回完整的构建对象 @Job1,1,v1.0.0,admin
- 测试步骤2：使用有效jobID和commit类型创建构建记录 >> 期望返回构建对象并设置commit字段 @Job2,2,abc123,admin
- 测试步骤3：使用有效jobID但不传入data参数 >> 期望返回构建对象，对应字段为空 @Job3,3,
- 测试步骤4：使用无效的jobID创建构建记录 >> 期望返回false @0
- 测试步骤5：使用0作为jobID创建构建记录 >> 期望返回false @0
- 测试步骤6：测试不同type参数的处理 >> 期望正确设置对应字段 @Job4,4,branch-dev
- 测试步骤7：验证创建的构建记录包含完整信息 >> 期望包含job、name、创建者等信息 @Job5,5,test-data,~~

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/compile.unittest.class.php';

$compile = zenData('compile');
$compile->gen(0);

$job = zenData('job');
$job->loadYaml('job_createbyjob', false, 2)->gen(5);

su('admin');

$compileTest = new compileTest();

r($compileTest->createByJobTest(1, 'v1.0.0', 'tag')) && p('name,job,tag,createdBy') && e('Job1,1,v1.0.0,admin');
r($compileTest->createByJobTest(2, 'abc123', 'commit')) && p('name,job,commit,createdBy') && e('Job2,2,abc123,admin');
r($compileTest->createByJobTest(3, '', 'tag')) && p('name,job,tag') && e('Job3,3,');
r($compileTest->createByJobTest(999, 'test', 'tag')) && p() && e('0');
r($compileTest->createByJobTest(0, 'test', 'tag')) && p() && e('0');
r($compileTest->createByJobTest(4, 'branch-dev', 'branch')) && p('name,job,branch') && e('Job4,4,branch-dev');
r($compileTest->createByJobTest(5, 'test-data', 'version')) && p('name,job,version,createdDate') && e('Job5,5,test-data,~');