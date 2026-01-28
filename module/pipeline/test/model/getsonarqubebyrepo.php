#!/usr/bin/env php
<?php

/**

title=测试 jobModel::getSonarqubeByRepo();
timeout=0
cid=16848

- 步骤1：查询单个版本库返回该repo的最新任务第1条的name属性 @SonarQube任务Repo1_3
- 步骤2：查询另一个版本库返回该repo的最新任务第2条的name属性 @SonarQube任务Repo2_2
- 步骤3：查询不存在的版本库ID返回空数组 @0
- 步骤4：排除指定jobID后返回该repo的其他任务第1条的name属性 @SonarQube任务Repo1_2
- 步骤5：显示已删除任务参数为true时包含已删除任务第3条的name属性 @已删除SonarQube任务
- 步骤6：不显示已删除任务参数为false时排除已删除任务第3条的name属性 @SonarQube任务Repo3_1
- 步骤7：空版本库ID列表返回空数组 @0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$job = zenData('job');
$job->id->range('1-10');
$job->name->range('SonarQube任务Repo1_1,SonarQube任务Repo1_2,SonarQube任务Repo1_3,SonarQube任务Repo2_1,SonarQube任务Repo2_2,SonarQube任务Repo3_1,已删除SonarQube任务,SonarQube任务Repo4,SonarQube任务Repo5,其他任务');
$job->repo->range('1,1,1,2,2,3,3,4,5,99');
$job->frame->range('sonarqube,sonarqube,sonarqube,sonarqube,sonarqube,sonarqube,sonarqube,sonarqube,sonarqube,phpunit');
$job->engine->range('jenkins,jenkins,jenkins,gitlab,gitlab,jenkins,gitlab,jenkins,gitlab,jenkins');
$job->deleted->range('0,0,0,0,0,0,1,0,0,0');
$job->gen(10);

su('admin');

$jobTest = new jobModelTest();

r($jobTest->getSonarqubeByRepoTest(array(1))) && p('1:name') && e('SonarQube任务Repo1_3'); // 步骤1：查询单个版本库返回该repo的最新任务
r($jobTest->getSonarqubeByRepoTest(array(2))) && p('2:name') && e('SonarQube任务Repo2_2'); // 步骤2：查询另一个版本库返回该repo的最新任务
r($jobTest->getSonarqubeByRepoTest(array(999))) && p() && e('0'); // 步骤3：查询不存在的版本库ID返回空数组
r($jobTest->getSonarqubeByRepoTest(array(1), 3)) && p('1:name') && e('SonarQube任务Repo1_2'); // 步骤4：排除指定jobID后返回该repo的其他任务
r($jobTest->getSonarqubeByRepoTest(array(3), 0, true)) && p('3:name') && e('已删除SonarQube任务'); // 步骤5：显示已删除任务参数为true时包含已删除任务
r($jobTest->getSonarqubeByRepoTest(array(3), 0, false)) && p('3:name') && e('SonarQube任务Repo3_1'); // 步骤6：不显示已删除任务参数为false时排除已删除任务
r($jobTest->getSonarqubeByRepoTest(array())) && p() && e('0'); // 步骤7：空版本库ID列表返回空数组