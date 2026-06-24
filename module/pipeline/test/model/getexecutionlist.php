#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getExecutionList();
timeout=0
cid=0

- 查询所有执行记录，期望返回3条 @3
- 按pipelineID=1过滤，期望返回2条 @2
- 按spaceID=1过滤，期望pipelineName属性 @流水线A
- 按repoID=1过滤，期望返回1条 @1
- 按type=space过滤，期望返回2条 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

global $dbh, $app;
$app->rawModule = 'pipeline';
$app->rawMethod = 'getexecutionlist';
$dbh->exec("CREATE TABLE IF NOT EXISTS `ops_pipeline` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `engine` varchar(30) NOT NULL DEFAULT '',
  `scope` varchar(30) NOT NULL DEFAULT '',
  `spaceID` int unsigned NOT NULL DEFAULT 0,
  `repoID` int unsigned NOT NULL DEFAULT 0,
  `status` varchar(30) NOT NULL DEFAULT '',
  `defaultBranch` varchar(255) NOT NULL DEFAULT '',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `yamlPath` varchar(255) NOT NULL DEFAULT '',
  `providerID` int unsigned NOT NULL DEFAULT 0,
  `desc` varchar(255) NOT NULL DEFAULT '',
  `latestVersion` int NOT NULL DEFAULT 0,
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime DEFAULT NULL,
  `lastExec` datetime DEFAULT NULL,
  `lastResult` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$dbh->exec("CREATE TABLE IF NOT EXISTS `ops_pipeline_executions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `pipelineID` int unsigned NOT NULL DEFAULT 0,
  `status` varchar(30) NOT NULL DEFAULT '',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime DEFAULT NULL,
  `trigger` varchar(30) NOT NULL DEFAULT '',
  `finishedDate` datetime DEFAULT NULL,
  `duration` int unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$dbh->exec("TRUNCATE TABLE `ops_pipeline_executions`");
$dbh->exec("TRUNCATE TABLE `ops_pipeline`");

$dbh->exec("INSERT INTO `ops_pipeline` (`id`,`name`,`engine`,`scope`,`spaceID`,`repoID`,`status`,`defaultBranch`,`createdBy`,`deleted`) VALUES
(1,'流水线A','gitfox','space',1,0,'active','master','admin',0),
(2,'流水线B','jenkins','repo',1,1,'active','master','admin',0)");

$dbh->exec("INSERT INTO `ops_pipeline_executions` (`id`,`pipelineID`,`status`,`createdBy`,`createdDate`,`trigger`) VALUES
(1,1,'success','admin','2024-06-01 10:00:00','commit'),
(2,1,'failure','admin','2024-06-02 10:00:00','tag'),
(3,2,'success','user','2024-06-03 10:00:00','manual')");

$tester = new pipelineModelTest();

$allList = $tester->getExecutionListTest();
r(count($allList)) && p() && e(3); // 步骤1：查询所有执行记录，共3条

$pipeList = $tester->getExecutionListTest(0, 0, '', 1, 'id_asc', 20, 1);
r(count($pipeList)) && p() && e(2); // 步骤2：按pipelineID=1过滤，共2条

$spaceList = $tester->getExecutionListTest(1, 0, '', 0, 'id_asc', 20, 1);
r(current($spaceList)) && p('pipelineName') && e('流水线A'); // 步骤3：按spaceID=1过滤，首条pipelineName为流水线A

$repoList = $tester->getExecutionListTest(0, 1, '', 0, 'id_asc', 20, 1);
r(count($repoList)) && p() && e(1); // 步骤4：按repoID=1过滤，共1条

$typeSpaceList = $tester->getExecutionListTest(0, 0, 'space', 0, 'id_asc', 20, 1);
r(count($typeSpaceList)) && p() && e(2); // 步骤5：按type=space过滤，共2条
