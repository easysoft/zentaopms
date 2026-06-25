#!/usr/bin/env php
<?php

/**

title=测试 pipelineModel::getList();
timeout=0
cid=0

- 测试获取所有流水线列表 @流水线1;流水线5
- 测试获取代码库级流水线列表 @流水线1;流水线5
- 测试获取空间级流水线列表 @流水线3;流水线4
- 测试按id正序获取第1条name属性 @流水线1
- 测试按id倒序获取第1条name属性 @流水线5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

global $app;
$app->rawModule = 'pipeline';
$app->rawMethod = 'browse';

$tester = new pipelineModelTest();
$dbh    = $tester->instance->dao->dbh;

/* 写入测试数据 */
$dbh->exec('DELETE FROM ' . TABLE_PIPELINE);
$dbh->exec('DELETE FROM ' . TABLE_PIPELINECONTENT);
$dbh->exec('DELETE FROM ' . TABLE_PROVIDER);

$dbh->exec('INSERT INTO ' . TABLE_PIPELINE . ' (id, name, engine, providerID, scope, spaceID, repoID, status, defaultBranch, deleted) VALUES
  (1, "流水线1", "gitfox",  1, "repo",  1, 1, "active", "main", 0),
  (2, "流水线2", "gitlab",  1, "repo",  1, 2, "active", "main", 0),
  (3, "流水线3", "jenkins", 0, "space", 1, 0, "active", "main", 0),
  (4, "流水线4", "gitfox",  0, "space", 1, 0, "active", "main", 0),
  (5, "流水线5", "gitlab",  1, "repo",  2, 3, "active", "main", 0)');

$dbh->exec("INSERT INTO " . TABLE_PROVIDER . " (id, type, name, url, deleted) VALUES (1, 'gitlab', 'GitLab', 'https://gitlabdev.qc.oop.cc', 0)");
$dbh->exec('INSERT INTO ' . TABLE_PIPELINECONTENT . ' (id, pipelineID, version, data, variables) VALUES (1,1,1,"","{}"),(2,2,1,"","{}"),(3,3,1,"","{}"),(4,4,1,"","{}"),(5,5,1,"","{}")');

su('admin');

r($tester->getListTest()) && p('1:name;5:name') && e('流水线1;流水线5');            // 全列表含id=1和id=5
r($tester->getListTest(0, 0, 'repo')) && p('1:name;5:name') && e('流水线1;流水线5'); // repo级含id=1和id=5
r($tester->getListTest(0, 0, 'space')) && p('3:name;4:name') && e('流水线3;流水线4');// space级含id=3和id=4
r($tester->getListTest(0, 0, '', 'id_asc')) && p('1:name') && e('流水线1');         // id正序首条
r($tester->getListTest(0, 0, '', 'id_desc')) && p('5:name') && e('流水线5');        // id倒序首条
