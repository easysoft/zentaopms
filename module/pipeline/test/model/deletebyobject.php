#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::deleteByObject();
timeout=0
cid=17343

- 执行pipelineTest模块的deleteByObjectTest方法，参数是0, 'gitlab'  @0
- 执行pipelineTest模块的deleteByObjectTest方法，参数是1, 'gitlab'  @0
- 执行pipelineTest模块的deleteByObjectTest方法，参数是2, 'sonarqube'  @0
- 执行pipelineTest模块的deleteByObjectTest方法，参数是5, 'jenkins' 属性deleted @1
- 执行pipelineTest模块的deleteByObjectTest方法，参数是4, 'gogs' 属性deleted @1
- 执行pipelineTest模块的deleteByObjectTest方法，参数是3, 'gitea' 属性deleted @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pipeline.unittest.class.php';

// 准备测试数据
zenData('user')->gen(5);
zenData('pipeline')->loadYaml('pipeline')->gen(6);

// 准备代码库数据（用于测试gitlab类型的关联检查）
$repoTable = zenData('repo');
$repoTable->SCM->range('Gitlab');
$repoTable->serviceHost->range('1');
$repoTable->deleted->range('0');
$repoTable->gen(1);

// 准备任务数据（用于测试sonarqube类型的关联检查）
$jobTable = zenData('job');
$jobTable->frame->range('sonarqube');
$jobTable->server->range('2');
$jobTable->deleted->range('0');
$jobTable->gen(1);

su('admin');

$pipelineTest = new pipelineTest();

// 测试步骤1：删除不存在的流水线
r($pipelineTest->deleteByObjectTest(0, 'gitlab')) && p() && e('0');

// 测试步骤2：删除有关联代码库的gitlab类型流水线（应该阻止删除）
r($pipelineTest->deleteByObjectTest(1, 'gitlab')) && p() && e('0');

// 测试步骤3：删除有关联任务的sonarqube类型流水线（应该阻止删除）
r($pipelineTest->deleteByObjectTest(2, 'sonarqube')) && p() && e('0');

// 测试步骤4：正常删除jenkins类型流水线
r($pipelineTest->deleteByObjectTest(5, 'jenkins')) && p('deleted') && e('1');

// 测试步骤5：删除gogs类型流水线且无关联数据
r($pipelineTest->deleteByObjectTest(4, 'gogs')) && p('deleted') && e('1');

// 测试步骤6：删除gitea类型流水线且无关联数据
r($pipelineTest->deleteByObjectTest(3, 'gitea')) && p('deleted') && e('1');