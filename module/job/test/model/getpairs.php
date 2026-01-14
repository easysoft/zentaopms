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

// 创建测试实例
$jobTest = new jobModelTest();

// 测试步骤1：正常情况下获取repo为1的jenkins作业键值对
r($jobTest->getPairsTest(1, 'jenkins')) && p('1') && e('Jenkins任务1');

// 测试步骤2：正常情况下获取repo为2的gitlab作业键值对
r($jobTest->getPairsTest(2, 'gitlab')) && p('2') && e('GitLab流水线1');

// 测试步骤3：边界值测试-查询不存在的repo ID的作业键值对
r($jobTest->getPairsTest(999, 'jenkins')) && p() && e(0);

// 测试步骤4：过滤测试-不指定engine参数获取所有作业
r($jobTest->getPairsTest(1, '')) && p('1') && e('Jenkins任务1');

// 测试步骤5：删除记录过滤测试-确保已删除记录不被返回（id=9的记录被删除）
r($jobTest->getPairsTest(3, 'jenkins')) && p('5') && e('Jenkins任务3');