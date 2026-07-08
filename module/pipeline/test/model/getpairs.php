#!/usr/bin/env php
<?php

/**

title=测试 jobModel::getPairs();
timeout=0
cid=16847

- 执行jobTest模块的getPairsTest方法，参数是1, 'jenkins' 属性1 @Jenkins任务1
- 执行jobTest模块的getPairsTest方法，参数是2, 'gitlab' 属性2 @GitLab流水线1
- 执行jobTest模块的getPairsTest方法，参数是999, 'jenkins'  @0
- 执行jobTest模块的getPairsTest方法，参数是1, '' 属性1 @Jenkins任务1
- 执行jobTest模块的getPairsTest方法，参数是3, 'jenkins' 属性5 @Jenkins任务3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 准备测试数据
$job = zenData('job');
$job->id->range('1-10');
$job->name->range('Jenkins任务1,GitLab流水线1,Jenkins任务2,GitLab流水线2,Jenkins任务3,GitLab流水线3,测试作业1,测试作业2,删除的任务,混合任务');
$job->repo->range('1,2,1,2,3,3,1,2,1,2');
$job->engine->range('jenkins,gitlab,jenkins,gitlab,jenkins,gitlab,jenkins,gitlab,jenkins,gitlab');
$job->deleted->range('0,0,0,0,0,0,0,0,1,0');
$job->gen(10);

// 用户登录
su('admin');

$pipelineTester = new pipelineModelTest();
r($pipelineTester->getPairsTest($types[0])) && p('1') && e('gitLab'); // 获取type为空的流水线信息
r($pipelineTester->getPairsTest($types[1])) && p('1') && e('gitLab'); // 获取type为gitlab的流水线信息
r($pipelineTester->getPairsTest($types[2])) && p(0)   && e('0');      // 获取type为test的流水线信息
